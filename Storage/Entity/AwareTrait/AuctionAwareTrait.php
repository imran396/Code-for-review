<?php

/**
 * SAM-4819: Entity aware traits
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01/08/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\AwareTrait;

use Auction;
use AuctionCache;
use AuctionDynamic;
use AuctionRtbd;
use Sam\Storage\Entity\Aggregate\AuctionAggregate;

/**
 * Trait AuctionAwareTrait
 * @package Sam\Storage\Entity\AwareTrait
 */
trait AuctionAwareTrait
{
    protected ?AuctionAggregate $auctionAggregate = null;

    /**
     * @return int|null
     */
    public function getAuctionId(): ?int
    {
        return $this->getAuctionAggregate()->getAuctionId();
    }

    /**
     * @param int|mixed $auctionId
     * @return static
     */
    public function setAuctionId(?int $auctionId): static
    {
        $this->getAuctionAggregate()->setAuctionId($auctionId);
        return $this;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return Auction|null
     */
    public function getAuction(bool $isReadOnlyDb = false): ?Auction
    {
        return $this->getAuctionAggregate()->getAuction($isReadOnlyDb);
    }

    /**
     * @param Auction|null $auction
     * @return static
     */
    public function setAuction(?Auction $auction): static
    {
        $this->getAuctionAggregate()->setAuction($auction);
        return $this;
    }

    // --- AuctionCache ---

    /**
     * Return AuctionCache object
     * @param bool $isReadOnlyDb
     * @return AuctionCache|null
     */
    public function getAuctionCache(bool $isReadOnlyDb = false): ?AuctionCache
    {
        return $this->getAuctionAggregate()->getAuctionCache($isReadOnlyDb);
    }

    /**
     * @param AuctionCache|null $auctionCache
     * @return static
     */
    public function setAuctionCache(?AuctionCache $auctionCache = null): static
    {
        $this->getAuctionAggregate()->setAuctionCache($auctionCache);
        return $this;
    }

    // --- AuctionDynamic ---

    /**
     * Return AuctionDynamic object
     * @param bool $isReadOnlyDb
     * @return AuctionDynamic|null
     */
    public function getAuctionDynamic(bool $isReadOnlyDb = false): ?AuctionDynamic
    {
        return $this->getAuctionAggregate()->getAuctionDynamic($isReadOnlyDb);
    }

    /**
     * Return AuctionDynamic object. Create new if not exist
     * @param bool $isReadOnlyDb
     * @return AuctionDynamic
     */
    public function getAuctionDynamicOrCreate(bool $isReadOnlyDb = false): AuctionDynamic
    {
        return $this->getAuctionAggregate()->getAuctionDynamicOrCreate($isReadOnlyDb);
    }

    /**
     * Return AuctionDynamic object. Create new and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return AuctionDynamic
     */
    public function getAuctionDynamicOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): AuctionDynamic
    {
        return $this->getAuctionAggregate()->getAuctionDynamicOrCreatePersisted($editorUserId, $isReadOnlyDb);
    }

    /**
     * @param AuctionDynamic|null $auctionDynamic
     * @return static
     */
    public function setAuctionDynamic(?AuctionDynamic $auctionDynamic = null): static
    {
        $this->getAuctionAggregate()->setAuctionDynamic($auctionDynamic);
        return $this;
    }

    // --- AuctionRtbd ---

    /**
     * Return AuctionRtbd object. It is always created, if not exist
     * @param bool $isReadOnlyDb
     * @return AuctionRtbd|null
     */
    public function getAuctionRtbd(bool $isReadOnlyDb = false): ?AuctionRtbd
    {
        return $this->getAuctionAggregate()->getAuctionRtbd($isReadOnlyDb);
    }

    /**
     * Return AuctionRtbd object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return AuctionRtbd
     */
    public function getAuctionRtbdOrCreate(bool $isReadOnlyDb = false): AuctionRtbd
    {
        return $this->getAuctionAggregate()->getAuctionRtbdOrCreate($isReadOnlyDb);
    }

    /**
     * Return AuctionRtbd object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return AuctionRtbd
     */
    public function getAuctionRtbdOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): AuctionRtbd
    {
        return $this->getAuctionAggregate()->getAuctionRtbdOrCreatePersisted($isReadOnlyDb);
    }

    /**
     * @param AuctionRtbd|null $auctionRtbd
     * @return static
     */
    public function setAuctionRtbd(?AuctionRtbd $auctionRtbd = null): static
    {
        $this->getAuctionAggregate()->setAuctionRtbd($auctionRtbd);
        return $this;
    }

    // --- AuctionAggregate ---

    /**
     * @return AuctionAggregate
     */
    protected function getAuctionAggregate(): AuctionAggregate
    {
        if ($this->auctionAggregate === null) {
            $this->auctionAggregate = AuctionAggregate::new();
        }
        return $this->auctionAggregate;
    }
}
