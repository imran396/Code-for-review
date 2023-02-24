<?php
/**
 * Help methods for Credit Card loading
 *
 * SAM-4088: CreditCardLoader and CreditCardExistenceChecker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis, Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 23, 2018
 */

namespace Sam\Billing\CreditCard\Load;

use CreditCard;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\CreditCard\CreditCardReadRepository;
use Sam\Storage\ReadRepository\Entity\CreditCard\CreditCardReadRepositoryCreateTrait;

/**
 * Class CreditCardLoader
 */
class CreditCardLoader extends EntityLoaderBase
{
    use CreditCardReadRepositoryCreateTrait;
    use MemoryCacheManagerAwareTrait;

    protected ?bool $filterActive = null;

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
        $this->filterActive(true);
        return $this;
    }

    /**
     * @return static
     */
    public function clear(): static
    {
        $this->dropFilterActive();
        return $this;
    }

    /**
     * @param bool $active
     * @return static
     */
    public function filterActive(bool $active): static
    {
        $this->filterActive = $active;
        return $this;
    }

    /**
     * Drop filtering by cc.active
     * @return static
     */
    public function dropFilterActive(): static
    {
        $this->filterActive = null;
        return $this;
    }

    /**
     * Load Credit Card
     * @param int|null $ccId
     * @param bool $isReadOnlyDb
     * @return CreditCard|null
     */
    public function load(?int $ccId, bool $isReadOnlyDb = false): ?CreditCard
    {
        $ccId = Cast::toInt($ccId, Constants\Type::F_INT_POSITIVE);
        if (!$ccId) {
            return null;
        }

        $fn = function () use ($ccId, $isReadOnlyDb) {
            $creditCard = $this->prepareRepository($isReadOnlyDb)
                ->filterId($ccId)
                ->loadEntity();
            return $creditCard;
        };

        $cacheKey = sprintf(Constants\MemoryCache::CREDIT_CARD_ID, $ccId);
        $creditCard = $this->getMemoryCacheManager()->load($cacheKey, $fn);
        return $creditCard;
    }

    /**
     * Load active record by 'name'
     * @param string $name
     * @param bool $isReadOnlyDb
     * @return CreditCard|null
     */
    public function loadByName(string $name, bool $isReadOnlyDb = false): ?CreditCard
    {
        if (!$name) {
            return null;
        }

        $creditCard = $this->prepareRepository($isReadOnlyDb)
            ->filterName($name)
            ->loadEntity();
        return $creditCard;
    }

    /**
     * Load all active records
     * @param bool $isReadOnlyDb
     * @return CreditCard[]
     */
    public function loadAll(bool $isReadOnlyDb = false): array
    {
        $creditCards = $this->prepareRepository($isReadOnlyDb)
            ->loadEntities();
        return $creditCards;
    }

    public function loadSelectedAll(array $select, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->select($select)
            ->loadRows();
    }

    /**
     * @param bool $isReadOnlyDb
     * @return CreditCardReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb = false): CreditCardReadRepository
    {
        $repo = $this->createCreditCardReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb);
        if ($this->filterActive !== null) {
            $repo->filterActive($this->filterActive);
        }
        return $repo;
    }
}
