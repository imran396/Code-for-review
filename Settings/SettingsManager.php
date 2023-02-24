<?php
/**
 * System parameters are stored in AuctionParameters entity and their reading is provided by this service.
 * System parameters data is located in DB, in file cache and in memory cache.
 * However, DB is single source of truth, settings data in file cache should always contain actual values. This is achieved by deleting file cache on DB update.
 * This cannot be always true for data in memory cache, it may become stale, because of changes made in DB by parallel process.
 * According above, file cache should not created by data loaded from memory cache, but only by data loaded from DB.
 *
 * SAM-9684: Reduce effects of stale cached data on system parameters
 * SAM-3636: Manager class for AuctionParameters
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           24 Feb, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings;

use RuntimeException;
use Sam\Account\Main\MainAccountDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\Cache\SettingsCacheManagerAwareTrait;
use Sam\Settings\Exception\CouldNotFindSettings;
use Sam\Settings\Repository\SettingsRepositoryProviderCreateTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class SettingsManager
 * @package Sam\Settings
 */
class SettingsManager extends CustomizableClass implements SettingsManagerInterface
{
    use MainAccountDetectorCreateTrait;
    use SettingsCacheManagerAwareTrait;
    use SettingsRepositoryProviderCreateTrait;
    use SystemAccountAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function setFileCacheTtl(int $fileCacheTtl): static
    {
        $this->getSettingsCacheManager()->setFileCacheTtl($fileCacheTtl);
        return $this;
    }

    public function setMemoryCacheTtl(int $memoryCacheTtl): static
    {
        $this->getSettingsCacheManager()->setMemoryCacheTtl($memoryCacheTtl);
        return $this;
    }

    /**
     * Get a particular system parameter
     *
     * @param string $name System parameter name
     * @param int $accountId
     * @return mixed
     */
    public function get(string $name, int $accountId): mixed
    {
        if (!array_key_exists($name, Constants\Setting::$typeMap)) {
            $message = "Unknown settings option" . composeSuffix(['name' => $name]);
            log_error($message);
            throw new RuntimeException($message);
        }

        $className = Constants\Setting::$typeMap[$name]['entity'];
        $settingsObject = $this->load($className, $accountId);
        if ($settingsObject) {
            return $settingsObject->$name;
        }

        log_errorBackTrace('system_parameters for account not found!' . composeSuffix(['acc' => $accountId]));
        $parameterMetadata = Constants\Setting::$typeMap[$name];
        $isNullable = $parameterMetadata['nullable'] ?? false;
        $isString = $parameterMetadata['type'] === Constants\Type::T_STRING;
        if (
            $isString
            && !$isNullable
        ) {
            return '';
        }
        return null;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getForMain(string $name): mixed
    {
        return $this->get($name, $this->detectMainAccountId());
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getForSystem(string $name): mixed
    {
        $accountId = $this->getSystemAccountId();
        return $this->get($name, $accountId);
    }

    /**
     * Load settings for account
     *
     * They are memory/file cached up to self::TTL seconds.
     * We drop cached data, when settings object is saved (AuctionParameters::Save()).
     *
     * @param string $className
     * @param int $accountId
     * @return object|null
     */
    protected function load(string $className, int $accountId): ?object
    {
        $settingsEntity = $this->getSettingsCacheManager()->get($className, $accountId);
        if (!$settingsEntity) {
            $settingsEntity = $this->loadFromDb($className, $accountId);
            $this->getSettingsCacheManager()->set($className, $accountId, $settingsEntity);
        }
        return $settingsEntity;
    }

    /**
     * Load non-cached settings entity directly from db.
     * @template T of object
     * @param class-string<T> $className
     * @param int $accountId
     * @return T
     * @throws CouldNotFindSettings is RuntimeException because it isn't expected situation when settings entity is missed for account of entities registered in system.
     */
    protected function loadFromDb(string $className, int $accountId): object
    {
        $entity = $this->createSettingsRepositoryProvider()
            ->getReadRepository($className)
            ->filterAccountId($accountId)
            ->loadEntity();
        if (!$entity) {
            throw CouldNotFindSettings::forClassNameWithAccountId($className, $accountId);
        }
        return $entity;
    }

    /**
     * Results with id of main account fetched from installation configuration.
     * @return int
     */
    protected function detectMainAccountId(): int
    {
        return $this->createMainAccountDetector()->id();
    }
}
