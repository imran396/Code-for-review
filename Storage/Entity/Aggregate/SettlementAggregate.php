<?php
/**
 * SAM-4819: Entity aware traits
 *
 * Aggregate class can be used, when we need to operate with several Settlement entities in one class namespace.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\Aggregate;

use Sam\Settlement\Load\SettlementItemLoaderAwareTrait;
use Sam\Settlement\Load\SettlementLoaderAwareTrait;
use Settlement;
use SettlementItem;

/**
 * Class SettlementAggregate
 * @package Sam\Storage\Entity\Aggregate
 */
class SettlementAggregate extends EntityAggregateBase
{
    use SettlementItemLoaderAwareTrait;
    use SettlementLoaderAwareTrait;

    private ?int $settlementId = null;
    private ?Settlement $settlement = null;
    /** @var SettlementItem[] */
    private ?array $settlementItems = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Empty aggregated entities
     * @return static
     */
    public function clear(): static
    {
        $this->settlement = null;
        $this->settlementId = null;
        $this->settlementItems = null;
        return $this;
    }

    // --- settlement.id ---

    /**
     * @return int|null
     */
    public function getSettlementId(): ?int
    {
        return $this->settlementId;
    }

    /**
     * @param int|null $settlementId
     * @return static
     */
    public function setSettlementId(?int $settlementId): static
    {
        $settlementId = $settlementId ?: null;
        if ($this->settlementId !== $settlementId) {
            $this->clear();
        }
        $this->settlementId = $settlementId;
        return $this;
    }

    // --- Settlement ---

    /**
     * @return bool
     */
    public function hasSettlement(): bool
    {
        return ($this->settlement !== null);
    }

    /**
     * Return Settlement object
     * @param bool $isReadOnlyDb
     * @return Settlement|null
     */
    public function getSettlement(bool $isReadOnlyDb = false): ?Settlement
    {
        if ($this->settlement === null) {
            $this->settlement = $this->getSettlementLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->load($this->settlementId, $isReadOnlyDb);
        }
        return $this->settlement;
    }

    /**
     * @param Settlement|null $settlement
     * @return static
     */
    public function setSettlement(?Settlement $settlement = null): static
    {
        if (!$settlement) {
            $this->clear();
        } elseif ($settlement->Id !== $this->settlementId) {
            $this->clear();
            $this->settlementId = $settlement->Id;
        }
        $this->settlement = $settlement;
        return $this;
    }

    // --- SettlementItem[] ---

    /**
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function getSettlementItemIds(bool $isReadOnlyDb = false): array
    {
        $settlementItemIds = [];
        foreach ($this->getSettlementItems($isReadOnlyDb) as $settlementItem) {
            $settlementItemIds[] = $settlementItem->Id;
        }
        return $settlementItemIds;
    }

    /**
     * @return bool
     */
    public function hasSettlementItems(): bool
    {
        return ($this->settlementItems !== null);
    }

    /**
     * Return array of active settlement items. By default it loads all items, including deleted
     * @param bool $isReadOnlyDb
     * @return SettlementItem[]
     */
    public function getSettlementItems(bool $isReadOnlyDb = false): array
    {
        if ($this->settlementItems === null) {
            $this->settlementItems = $this->getSettlementItemLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadBySettlementId($this->settlementId, $isReadOnlyDb);
        }
        return $this->settlementItems;
    }

    /**
     * Find SettlementItem in array by id
     * @param int $settlementItemId
     * @param bool $isReadOnlyDb
     * @return SettlementItem|null
     */
    public function getSettlementItemById(int $settlementItemId, bool $isReadOnlyDb = false): ?SettlementItem
    {
        $settlementItems = $this->getSettlementItems($isReadOnlyDb);
        foreach ($settlementItems as $settlementItem) {
            if ($settlementItem->Id === $settlementItemId) {
                return $settlementItem;
            }
        }
        return null;
    }

    /**
     * @param SettlementItem[] $settlementItems
     * @return static
     */
    public function setSettlementItems(?array $settlementItems): static
    {
        $this->settlementItems = $settlementItems;
        return $this;
    }
}
