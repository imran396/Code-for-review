<?php
/**
 * Help methods for Location entity loading
 *
 * SAM-3638 : Location repository and manager https://bidpath.atlassian.net/browse/SAM-3638
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 17, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Location\Load;

use Auction;
use Location;
use LotItem;
use Sam\Core\Constants;
use Sam\Core\Filter\EntityLoader\LocationAllFilterTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use User;

/**
 * Class LocationLoader
 * @package Sam\Location\Load
 */
class LocationLoader extends EntityLoaderBase
{
    use EntityMemoryCacheManagerAwareTrait;
    use LocationAllFilterTrait;

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
     * Load Location by id.
     * Memory cache used.
     *
     * @param int|null $locationId . null means location.id is missing
     * @param bool $isReadOnlyDb
     * @return Location|null
     */
    public function load(?int $locationId, bool $isReadOnlyDb = false): ?Location
    {
        if (!$locationId) {
            return null;
        }

        $fn = function () use ($locationId, $isReadOnlyDb) {
            $result = $this->prepareRepository($isReadOnlyDb)
                ->filterId($locationId)
                ->loadEntity();
            return $result;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::LOCATION_ID, $locationId);
        $filterDescriptors = $this->collectFilterDescriptors();
        $location = $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $location;
    }

    /**
     * Load all active records be 'accountId'
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return Location[]
     */
    public function loadAll(?int $accountId, bool $isReadOnlyDb = false): array
    {
        $result = $this->prepareRepository($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterEntityId(null)
            ->orderByName()
            ->loadEntities();
        return $result;
    }

    /**
     * Load active record by 'name'
     * @param string|null $name
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return Location|null
     */
    public function loadByName(?string $name, ?int $accountId, bool $isReadOnlyDb = false): ?Location
    {
        $result = $this->prepareRepository($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterEntityId(null)
            ->filterName($name)
            ->loadEntity();
        return $result;
    }

    /**
     * Load Location by sync key from namespace
     * @param string $key entity_sync.key
     * @param int $namespaceId entity_sync.namespace_id
     * @param bool $isReadOnlyDb
     * @return Location|null
     */
    public function loadBySyncKey(string $key, int $namespaceId, bool $isReadOnlyDb = false): ?Location
    {
        $result = $this->prepareRepository($isReadOnlyDb)
            ->joinLocationSyncFilterSyncNamespaceId($namespaceId)
            ->joinLocationSyncFilterKey($key)
            ->loadEntity();
        return $result;
    }

    public function loadByTypeAndEntityId(int $type, ?int $entityId, bool $isReadOnlyDb = false): ?Location
    {
        if (!$entityId) {
            return null;
        }

        return $this->prepareRepository($isReadOnlyDb)
            ->filterEntityType($type)
            ->filterEntityId($entityId)
            ->filterActive(true)
            ->loadEntity();
    }

    public function loadByTypeAndLogo(?int $type, string $logo, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterEntityType($type)
            ->filterLogo($logo)
            ->filterActive(true)
            ->loadEntities();
    }

    public function loadCommonOrSpecificLocation(int $type, Auction|LotItem|User|null $entity, bool $isReadOnlyDb = false): ?Location
    {
        if (!$entity) {
            return null;
        }

        $dbField = Constants\Location::TYPE_TO_DB_FIELD[$type];
        if ($entity->$dbField) {
            return $this->load($entity->$dbField, $isReadOnlyDb);
        }

        if ($entity->Id) {
            return $this->loadByTypeAndEntityId($type, $entity->Id, $isReadOnlyDb);
        }

        return null;
    }

    public function loadCommonOrSpecificLocationAsArray(int $type, Auction|LotItem|User|null $entity, bool $isReadOnlyDb = false): array
    {
        if (!$entity) {
            return [];
        }

        $dbField = Constants\Location::TYPE_TO_DB_FIELD[$type];
        if ($entity->$dbField) {
            return $this->prepareRepository($isReadOnlyDb)->filterId($entity->$dbField)->loadRow();
        }

        if ($entity->Id) {
            return $this->prepareRepository($isReadOnlyDb)
                ->filterEntityType($type)
                ->filterEntityId($entity->Id)
                ->filterActive(true)
                ->loadRow();
        }

        return [];
    }
}
