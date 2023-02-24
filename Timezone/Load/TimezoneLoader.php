<?php
/**
 * Helping methods for timezone loading
 *
 * SAM-4022: TimezoneLoader and TimezoneExistenceChecker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 15, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Timezone\Load;

use RuntimeException;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Filter\EntityLoader\TimezoneAllFilterTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Timezone\ApplicationTimezoneProviderAwareTrait;
use Sam\Timezone\Save\TimezoneProducerCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Timezone;

/**
 * Class TimezoneLoader
 */
class TimezoneLoader extends EntityLoaderBase
{
    use ApplicationTimezoneProviderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use EditorUserAwareTrait;
    use EntityMemoryCacheManagerAwareTrait;
    use FilesystemCacheManagerAwareTrait;
    use UserLoaderAwareTrait;
    use SystemAccountAwareTrait;
    use TimezoneAllFilterTrait;
    use TimezoneProducerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->initFilter();
        return $this;
    }

    /**
     * Cached version of Timezone::Load
     * Memory cache timezone for up to 5 mins or load from file before querying database
     * @param int|null $tzId null leads to null result
     * @param bool $isReadOnlyDb
     * @return Timezone|null
     */
    public function load(?int $tzId, bool $isReadOnlyDb = false): ?Timezone
    {
        if (!$tzId) {
            return null;
        }

        $fn = function () use ($tzId, $isReadOnlyDb) {
            return $this->prepareRepository($isReadOnlyDb)
                ->filterId($tzId)
                ->loadEntity();
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::TIMEZONE_ID, $tzId);
        $filterDescriptors = $this->collectFilterDescriptors();

        return $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
    }

    /**
     * @param array $select
     * @param int|null $tzId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelected(array $select, ?int $tzId, bool $isReadOnlyDb = false): array
    {
        if (!$tzId) {
            return [];
        }

        return $this->prepareRepository($isReadOnlyDb)
            ->filterId($tzId)
            ->select($select)
            ->loadRow();
    }

    /**
     * @param string $location
     * @param bool $isReadOnlyDb
     * @return Timezone|null
     */
    public function loadByLocation(string $location, bool $isReadOnlyDb = false): ?Timezone
    {
        if (!$location) {
            return null;
        }

        $fn = function () use ($location, $isReadOnlyDb) {
            return $this->prepareRepository($isReadOnlyDb)
                ->filterLocation($location)
                ->loadEntity();
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::TIMEZONE_LOCATION, $location);
        $filterDescriptors = $this->collectFilterDescriptors();

        return $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return Timezone[]
     */
    public function loadAll(bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->orderByLocation()
            ->loadEntities();
    }

    /**
     * Return system timezone.
     * If we are out of application context, it returns system timezone for main account
     * @param int|null $accountId null leads to system account id
     * @return Timezone
     * @throws RuntimeException when timezone cannot be found
     */
    public function loadSystemTimezone(?int $accountId = null): Timezone
    {
        $accountId = Cast::toInt($accountId, Constants\Type::F_INT_POSITIVE);
        if (!$accountId) {
            $accountId = $this->getSystemAccountId();
        }

        $fn = function () use ($accountId) {
            return $this->loadSystemTimezoneFromFileOrDb($accountId);
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::TIMEZONE_ACCOUNT_ID, $accountId);
        $timezone = $this->getEntityMemoryCacheManager()->load($entityKey, $fn);

        if (!$timezone instanceof Timezone) {
            throw new RuntimeException('Timezone cannot be found by account' . composeSuffix(['acc' => $accountId]));
        }

        return $timezone;
    }

    /**
     * Load Timezone by location string or create new entity without saving in DB.
     * @param string $timezoneLocation
     * @return Timezone
     */
    public function loadByLocationOrCreate(string $timezoneLocation): Timezone
    {
        $timezone = $this->loadByLocation($timezoneLocation);
        if ($timezone) {
            return $timezone;
        }
        return $this->createTimezoneProducer()->create($timezoneLocation);
    }

    /**
     * Load Timezone by location string or create new entity and save in DB.
     * @param string $timezoneLocation
     * @param int|null $editorUserId
     * @return Timezone
     */
    public function loadByLocationOrCreatePersisted(string $timezoneLocation, ?int $editorUserId = null): Timezone
    {
        $timezone = $this->loadByLocation($timezoneLocation);
        if ($timezone) {
            return $timezone;
        }
        /**
         * TODO: #CQS-violation, IK, 2021-10: we shouldn't detect editor user there from context and db, but in caller,
         * so $editorUserId argument must be non-nullable int,
         * or there shouldn't be such method that performs command action inside of query logic responsibility.
         */
        $editorUserId = $editorUserId ?? $this->detectModifierUserId();
        return $this->createTimezoneProducer()->createPersisted($timezoneLocation, $editorUserId);
    }

    /**
     * Return entity modifier user - he is either authorized user, or system user when the current user is anonymous or not defined.
     * @return int
     */
    protected function detectModifierUserId(): int
    {
        return $this->getEditorUserId() ?: $this->getUserLoader()->loadSystemUserId();
    }

    /**
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return Timezone|null
     */
    protected function loadSystemTimezoneFromFileOrDb(int $accountId, bool $isReadOnlyDb = false): ?Timezone
    {
        $entityKey = sprintf(Constants\FileCache::TIMEZONE_ACCOUNT_ID, $accountId);
        $fsCacheManager = $this->getFilesystemCacheManager()
            ->setNamespace('timezone')
            ->setExtension('txt');
        // Load from file cache
        $timezone = $fsCacheManager->get($entityKey);
        if ($timezone) {
            log_trace('Timezone un-serialized data from cache' . composeSuffix(['acc' => $accountId]));
        }
        if (!$timezone) {
            // Load from db
            $timezone = $this->prepareRepository($isReadOnlyDb)
                ->joinSettingSystemFilterAccountId($accountId)
                ->loadEntity();
            // Store in file cache
            $fsCacheManager->set($entityKey, $timezone, $this->cfg()->get('core->entity->timezone->fileCache->ttl'));
            log_trace('Timezone fetched from db and saved in cache' . composeSuffix(['acc' => $accountId]));
        }
        return $timezone;
    }
}
