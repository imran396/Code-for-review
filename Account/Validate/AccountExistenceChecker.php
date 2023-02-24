<?php
/**
 * Help methods for different account validations for existence
 *
 * SAM-3942: Account entity maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 2, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Validate;

use Sam\Core\Constants;
use Sam\Core\Filter\EntityLoader\AccountAllFilterTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;

/**
 * Class AccountExistenceChecker
 * @package Sam\Account\Validate
 */
class AccountExistenceChecker extends CustomizableClass
{
    use AccountAllFilterTrait;
    use EntityMemoryCacheManagerAwareTrait;

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
     * Check, if account.id exists
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existById(?int $accountId, bool $isReadOnlyDb = false): bool
    {
        if (!$accountId) {
            return false;
        }

        $fn = function () use ($accountId, $isReadOnlyDb) {
            $isFound = $this->prepareRepository($isReadOnlyDb)
                ->filterId($accountId)
                ->exist();
            return $isFound;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::ACCOUNT_ID, $accountId);
        $filterDescriptors = $this->collectFilterDescriptors();
        $isFound = $this->getEntityMemoryCacheManager()
            ->existWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $isFound;
    }

    /**
     * Check, if account.name exists
     * @param string $name
     * @param int[] $skipIds don't check these account.id
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByName(string $name, array $skipIds = [], bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareRepository($isReadOnlyDb)
            ->filterName($name)
            ->skipId($skipIds)
            ->exist();
        return $isFound;
    }

    /**
     * Check, if UrlDomain is in use
     * @param string $urlDomain
     * @param int[] $skipIds don't check these account.id
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByUrlDomain(string $urlDomain, array $skipIds = [], bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareRepository($isReadOnlyDb)
            ->filterUrlDomain($urlDomain)
            ->skipId($skipIds)
            ->exist();
        return $isFound;
    }
}
