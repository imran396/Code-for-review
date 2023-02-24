<?php
/**
 * SAM-4819: Entity aware traits
 *
 * Aggregate class can be used, when we need to operate we several AuctionLot entities in one class namespace.
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

use AuctionLotItemCache;
use Sam\AuctionLot\Load\AuctionLotCacheLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use AuctionLotItem;

/**
 * Class AuctionLotAggregate
 * @package Sam\Storage\Entity\Aggregate
 */
class AuctionLotAggregate extends EntityAggregateBase
{
    use AuctionLotCacheLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;

    protected ?int $auctionLotId = null;
    protected ?AuctionLotItem $auctionLot = null;
    protected ?AuctionLotItemCache $auctionLotCache = null;

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
        $this->auctionLotId = null;
        $this->auctionLot = null;
        return $this;
    }

    // --- auction_lot_item.id ---

    /**
     * @return int|null
     */
    public function getAuctionLotId(): ?int
    {
        return $this->auctionLotId;
    }

    /**
     * @param int|null $auctionLotId
     * @return static
     */
    public function setAuctionLotId(?int $auctionLotId): static
    {
        if ($this->auctionLotId !== $auctionLotId) {
            $this->clear();
        }
        $this->auctionLotId = $auctionLotId;
        return $this;
    }

    // --- AuctionLotItem ---

    /**
     * @return bool
     */
    public function hasAuctionLot(): bool
    {
        return ($this->auctionLot !== null);
    }

    /**
     * Return AuctionLotItem object
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem|null
     */
    public function getAuctionLot(bool $isReadOnlyDb = false): ?AuctionLotItem
    {
        if ($this->auctionLot === null) {
            $this->auctionLot = $this->getAuctionLotLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadById($this->auctionLotId, $isReadOnlyDb);
        }
        return $this->auctionLot;
    }

    /**
     * @param AuctionLotItem|null $auctionLot
     * @return static
     */
    public function setAuctionLot(?AuctionLotItem $auctionLot = null): static
    {
        if (!$auctionLot) {
            $this->clear();
        } elseif ($auctionLot->Id !== $this->auctionLotId) {
            $this->clear();
            $this->auctionLotId = $auctionLot->Id;
        }
        $this->auctionLot = $auctionLot;
        return $this;
    }

    // AuctionLotItemCache

    /**
     * @return bool
     */
    public function hasAuctionLotCache(): bool
    {
        return ($this->auctionLotCache !== null);
    }

    /**
     * Return AuctionLotItemCache object
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemCache|null
     */
    public function getAuctionLotCache(bool $isReadOnlyDb = false): ?AuctionLotItemCache
    {
        if ($this->auctionLotCache === null) {
            $this->auctionLotCache = $this->getAuctionLotCacheLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadById($this->auctionLotId, $isReadOnlyDb);
        }
        return $this->auctionLotCache;
    }

    /**
     * Return AuctionLotItemCache object. It is always created, if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemCache
     */
    public function getAuctionLotCacheOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): AuctionLotItemCache
    {
        if ($this->auctionLotCache === null) {
            $this->auctionLotCache = $this->getAuctionLotCacheLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadByIdOrCreatePersisted($this->auctionLotId, $editorUserId, $isReadOnlyDb);
        }
        return $this->auctionLotCache;
    }

    /**
     * @param AuctionLotItemCache|null $auctionLotCache
     * @return static
     */
    public function setAuctionLotCache(?AuctionLotItemCache $auctionLotCache = null): static
    {
        if (
            $auctionLotCache
            && $auctionLotCache->AuctionLotItemId !== $this->auctionLotId
        ) {
            $this->clear();
            $this->auctionLotId = $auctionLotCache->AuctionLotItemId;
        }
        $this->auctionLotCache = $auctionLotCache;
        return $this;
    }
}
