<?php

/**
 * Help methods for location existence check.
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

namespace Sam\Location\Validate;

use Sam\Core\Constants;
use Sam\Core\Filter\EntityLoader\LocationAllFilterTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;

/**
 * Class LocationExistenceChecker
 * @package Sam\Location\Validate
 */
class LocationExistenceChecker extends CustomizableClass
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
     * Check if record with certain 'id' exists and is active
     * @param int $locationId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existById(int $locationId, bool $isReadOnlyDb = false): bool
    {
        if (!$locationId) {
            return false;
        }

        $fn = function () use ($locationId, $isReadOnlyDb) {
            $isFound = $this->prepareRepository($isReadOnlyDb)
                ->filterId($locationId)
                ->exist();
            return $isFound;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::LOCATION_ID, $locationId);
        $filterDescriptors = $this->collectFilterDescriptors();
        $isFound = $this->getEntityMemoryCacheManager()
            ->existWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $isFound;
    }

    /**
     * Check if record with certain 'name' and 'accountId' exists and is active
     * @param string $name
     * @param int|null $accountId
     * @param int[] $skipIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByName(string $name, ?int $accountId, array $skipIds = [], bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareRepository($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterName($name)
            ->skipId($skipIds)
            ->exist();
        return $isFound;
    }

    public function existByTypeAndEntityId(int $type, ?int $entityId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareRepository($isReadOnlyDb)
            ->filterEntityType($type)
            ->filterEntityId($entityId)
            ->exist();
        return $isFound;
    }
}
