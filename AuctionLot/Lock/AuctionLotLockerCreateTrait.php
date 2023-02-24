<?php
/**
 * SAM-6374: Move auction lot locking in transaction to separate class
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Lock;

/**
 * Trait AuctionLotLockerCreateTrait
 */
trait AuctionLotLockerCreateTrait
{
    /**
     * @var AuctionLotLocker|null
     */
    protected ?AuctionLotLocker $auctionLotLocker = null;

    /**
     * @return AuctionLotLocker
     */
    protected function createAuctionLotLocker(): AuctionLotLocker
    {
        return $this->auctionLotLocker ?: AuctionLotLocker::new();
    }

    /**
     * @param AuctionLotLocker $auctionLotLocker
     * @return $this
     */
    public function setAuctionLotLocker(AuctionLotLocker $auctionLotLocker): static
    {
        $this->auctionLotLocker = $auctionLotLocker;
        return $this;
    }
}
