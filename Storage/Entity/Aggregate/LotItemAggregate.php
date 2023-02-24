<?php
/**
 * SAM-4819: Entity aware traits
 *
 * Aggregate class can be used, when we need to operate we several LotItem entities in one class namespace.
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

use Sam\Lot\Load\LotItemLoaderAwareTrait;
use LotItem;

/**
 * Class LotItemAggregate
 * @package Sam\Storage\Entity\Aggregate
 */
class LotItemAggregate extends EntityAggregateBase
{
    use LotItemLoaderAwareTrait;

    private ?int $lotItemId = null;
    private ?LotItem $lotItem = null;

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
        $this->lotItemId = null;
        $this->lotItem = null;
        return $this;
    }

    // --- lot_online_item.id ---

    /**
     * @return int|null
     */
    public function getLotItemId(): ?int
    {
        return $this->lotItemId;
    }

    /**
     * @param int|null $lotItemId
     * @return static
     */
    public function setLotItemId(?int $lotItemId): static
    {
        $lotItemId = $lotItemId ?: null;
        if ($this->lotItemId !== $lotItemId) {
            $this->clear();
        }
        $this->lotItemId = $lotItemId;
        return $this;
    }

    // --- LotItem ---

    /**
     * @return bool
     */
    public function hasLotItem(): bool
    {
        return ($this->lotItem !== null);
    }

    /**
     * Return LotItem object
     * @param bool $isReadOnlyDb
     * @return LotItem|null
     */
    public function getLotItem(bool $isReadOnlyDb = false): ?LotItem
    {
        if ($this->lotItem === null) {
            $this->lotItem = $this->getLotItemLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->load($this->lotItemId, $isReadOnlyDb);
        }
        return $this->lotItem;
    }

    /**
     * @param LotItem|null $lotItem
     * @return static
     */
    public function setLotItem(?LotItem $lotItem = null): static
    {
        if (!$lotItem) {
            $this->clear();
        } elseif ($lotItem->Id !== $this->lotItemId) {
            $this->clear();
            $this->lotItemId = $lotItem->Id;
        }
        $this->lotItem = $lotItem;
        return $this;
    }
}
