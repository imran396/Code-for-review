<?php
/**
 * SAM-4819: Entity aware traits
 *
 * Aggregate class can be used, when we need to operate we several Auction entities in one class namespace.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\Aggregate;

use Auction;
use AuctionCache;
use AuctionDynamic;
use AuctionRtbd;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\Auction\Load\AuctionDynamicLoaderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Rtb\Pool\Auction\Load\AuctionRtbdLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionDynamic\AuctionDynamicWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionRtbd\AuctionRtbdWriteRepositoryAwareTrait;

/**
 * Class AuctionAggregate
 * @package Sam\Storage\Entity\Aggregate
 */
class AuctionAggregate extends EntityAggregateBase
{
    use AuctionCacheLoaderAwareTrait;
    use AuctionDynamicLoaderAwareTrait;
    use AuctionDynamicWriteRepositoryAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionRtbdLoaderAwareTrait;
    use AuctionRtbdWriteRepositoryAwareTrait;

    private ?int $auctionId = null;
    private ?Auction $auction = null;
    private ?AuctionCache $auctionCache = null;
    private ?AuctionDynamic $auctionDynamic = null;
    private ?AuctionRtbd $auctionRtbd = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Unset aggregated entities cached in class properties
     * @return static
     */
    public function clear(): static
    {
        $this->auctionId = null;
        $this->auction = null;
        $this->auctionCache = null;
        $this->auctionDynamic = null;
        $this->auctionRtbd = null;
        return $this;
    }

    // --- auction.id ---

    /**
     * @return int|null
     */
    public function getAuctionId(): ?int
    {
        return $this->auctionId;
    }

    /**
     * We clear stored entities in case of setAuctionId() call by default
     * @param int|null $auctionId
     * @return static
     */
    public function setAuctionId(?int $auctionId): static
    {
        $auctionId = $auctionId ?: null;
        if ($this->auctionId !== $auctionId) {
            $this->clear();
        }
        $this->auctionId = $auctionId;
        return $this;
    }

    // --- Auction ---

    /**
     * @return bool
     */
    public function hasAuction(): bool
    {
        return ($this->auction !== null);
    }

    /**
     * Return Auction object
     * @param bool $isReadOnlyDb
     * @return Auction|null
     */
    public function getAuction(bool $isReadOnlyDb = false): ?Auction
    {
        if ($this->auction === null) {
            $this->auction = $this->getAuctionLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->load($this->auctionId, $isReadOnlyDb);
        }
        return $this->auction;
    }

    /**
     * Set Auction entity.
     * It is general entity of aggregate, hence we clear all aggregated entities, when none passed.
     * Such behavior is not actual for related entities in aggregate.
     * @param Auction|null $auction
     * @return static
     */
    public function setAuction(?Auction $auction = null): static
    {
        if (!$auction) {
            $this->clear();
        } elseif ($auction->Id !== $this->auctionId) {
            $this->clear();
            $this->auctionId = $auction->Id;
        }
        $this->auction = $auction;
        return $this;
    }

    // --- AuctionCache ---

    /**
     * Return AuctionCache object. It is always created, if not exist
     * @param bool $isReadOnlyDb
     * @return AuctionCache|null
     */
    public function getAuctionCache(bool $isReadOnlyDb = false): ?AuctionCache
    {
        if ($this->auctionCache === null) {
            $this->auctionCache = $this->getAuctionCacheLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->load($this->auctionId, $isReadOnlyDb);
        }
        return $this->auctionCache;
    }

    /**
     * @param AuctionCache|null $auctionCache
     * @return static
     */
    public function setAuctionCache(?AuctionCache $auctionCache = null): static
    {
        $this->auctionCache = $auctionCache;
        if (
            $auctionCache
            && $auctionCache->AuctionId !== $this->auctionId
        ) {
            $this->clear();
            $this->auctionId = $auctionCache->AuctionId;
        }
        return $this;
    }

    /**
     * Return AuctionDynamic object
     * @param bool $isReadOnlyDb
     * @return AuctionDynamic|null
     */
    public function getAuctionDynamic(bool $isReadOnlyDb = false): ?AuctionDynamic
    {
        if ($this->auctionDynamic === null) {
            $this->auctionDynamic = $this->getAuctionDynamicLoader()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->load($this->auctionId, $isReadOnlyDb);
        }
        return $this->auctionDynamic;
    }

    /**
     * Return AuctionDynamic object. It is always created, if not exist
     * @param bool $isReadOnlyDb
     * @return AuctionDynamic
     */
    public function getAuctionDynamicOrCreate(bool $isReadOnlyDb = false): AuctionDynamic
    {
        if ($this->auctionDynamic === null && $this->auctionId) {
            $this->auctionDynamic = $this->getAuctionDynamicLoader()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadOrCreate($this->auctionId, $isReadOnlyDb);
        }
        return $this->auctionDynamic;
    }

    /**
     * Return AuctionDynamic object. Create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return AuctionDynamic
     */
    public function getAuctionDynamicOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): AuctionDynamic
    {
        if ($this->auctionDynamic === null) {
            $this->auctionDynamic = $this->getAuctionDynamic($isReadOnlyDb);
            if (!$this->auctionDynamic) {
                $this->auctionDynamic = $this->getAuctionDynamicOrCreate($isReadOnlyDb);
                $this->getAuctionDynamicWriteRepository()->saveWithModifier($this->auctionDynamic, $editorUserId);
            }
        }
        return $this->auctionDynamic;
    }

    /**
     * @param AuctionDynamic|null $auctionDynamic
     * @return static
     */
    public function setAuctionDynamic(?AuctionDynamic $auctionDynamic = null): static
    {
        $this->auctionDynamic = $auctionDynamic;
        if (
            $auctionDynamic
            && $auctionDynamic->AuctionId !== $this->auctionId
        ) {
            $this->clear();
            $this->auctionId = $auctionDynamic->AuctionId;
        }
        return $this;
    }

    // --- AuctionRtbd ---

    /**
     * Return AuctionRtbd object, if exists
     * @param bool $isReadOnlyDb
     * @return AuctionRtbd|null
     */
    public function getAuctionRtbd(bool $isReadOnlyDb = false): ?AuctionRtbd
    {
        if ($this->auctionRtbd === null) {
            $this->auctionRtbd = $this->getAuctionRtbdLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->load($this->auctionId, $isReadOnlyDb);
        }
        return $this->auctionRtbd;
    }

    /**
     * Return AuctionRtbd object, if exists
     * @param bool $isReadOnlyDb
     * @return AuctionRtbd
     */
    public function getAuctionRtbdOrCreate(bool $isReadOnlyDb = false): AuctionRtbd
    {
        if ($this->auctionRtbd === null) {
            $this->auctionRtbd = $this->getAuctionRtbdLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadOrCreate($this->auctionId, $isReadOnlyDb);
        }
        return $this->auctionRtbd;
    }

    /**
     * Return AuctionRtbd object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return AuctionRtbd
     */
    public function getAuctionRtbdOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): AuctionRtbd
    {
        $this->auctionRtbd = $this->getAuctionRtbdOrCreate($isReadOnlyDb);
        if (!$this->auctionRtbd->AuctionId) {
            $this->getAuctionRtbdWriteRepository()->saveWithModifier($this->auctionRtbd, $editorUserId);
        }
        return $this->auctionRtbd;
    }

    /**
     * @param AuctionRtbd|null $auctionRtbd
     * @return static
     */
    public function setAuctionRtbd(?AuctionRtbd $auctionRtbd = null): static
    {
        $this->auctionRtbd = $auctionRtbd;
        if (
            $auctionRtbd
            && $auctionRtbd->AuctionId !== $this->auctionId
        ) {
            $this->clear();
            $this->auctionId = $auctionRtbd->AuctionId;
        }
        return $this;
    }
}
