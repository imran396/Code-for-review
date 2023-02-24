<?php
/** @noinspection PhpUnused */

/**
 * SAM-4819: Entity aware traits
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/14/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\AwareTrait;

use AuctionLotItem;
use AuctionLotItemCache;
use Sam\Storage\Entity\Aggregate\AuctionLotAggregate;

/**
 * Trait AuctionLotAwareTrait
 * @package Sam\Storage\Entity\AwareTrait
 */
trait AuctionLotAwareTrait
{
    protected ?AuctionLotAggregate $auctionLotAggregate = null;

    /**
     * Return Id of AuctionLotItem
     * @return int|null
     */
    public function getAuctionLotId(): ?int
    {
        return $this->getAuctionLotAggregate()->getAuctionLotId();
    }

    /**
     * Set Id of AuctionLotItem
     * @param int|null $auctionLotId
     * @return static
     */
    public function setAuctionLotId(?int $auctionLotId): static
    {
        $this->getAuctionLotAggregate()->setAuctionLotId($auctionLotId);
        return $this;
    }

    // --- AuctionLotItem ---

    /**
     * Return AuctionLotItem object
     * @param bool $isReadOnlyDb
     * @return AuctionLotItem|null
     */
    public function getAuctionLot(bool $isReadOnlyDb = false): ?AuctionLotItem
    {
        return $this->getAuctionLotAggregate()->getAuctionLot($isReadOnlyDb);
    }

    /**
     * Set AuctionLotItem object
     * @param AuctionLotItem|null $auctionLot
     * @return static
     */
    public function setAuctionLot(?AuctionLotItem $auctionLot): static
    {
        $this->getAuctionLotAggregate()->setAuctionLot($auctionLot);
        return $this;
    }

    // AuctionLotItemCache

    /**
     * Return AuctionLotItemCache object.
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemCache|null
     */
    public function getAuctionLotCache(bool $isReadOnlyDb = false): ?AuctionLotItemCache
    {
        return $this->getAuctionLotAggregate()->getAuctionLotCache($isReadOnlyDb);
    }

    /**
     * Return AuctionLotItemCache object. It is always created, if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemCache
     */
    public function getAuctionLotCacheOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): AuctionLotItemCache
    {
        return $this->getAuctionLotAggregate()->getAuctionLotCacheOrCreatePersisted($editorUserId, $isReadOnlyDb);
    }

    /**
     * @param AuctionLotItemCache|null $auctionLotCache
     * @return static
     */
    public function setAuctionLotCache(?AuctionLotItemCache $auctionLotCache): static
    {
        $this->getAuctionLotAggregate()->setAuctionLotCache($auctionLotCache);
        return $this;
    }

    // --- AuctionLotAggregate ---

    /**
     * @return AuctionLotAggregate
     */
    protected function getAuctionLotAggregate(): AuctionLotAggregate
    {
        if ($this->auctionLotAggregate === null) {
            $this->auctionLotAggregate = AuctionLotAggregate::new();
        }
        return $this->auctionLotAggregate;
    }
}
