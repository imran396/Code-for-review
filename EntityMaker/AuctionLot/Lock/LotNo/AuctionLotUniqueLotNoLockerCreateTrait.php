<?php
/**
 * SAM-10802: Supply uniqueness of auction lot fields: lot#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Lock\LotNo;

/**
 * Trait AuctionLotUniqueLotNoLockerCreateTrait
 * @package Sam\EntityMaker\AuctionLot
 */
trait AuctionLotUniqueLotNoLockerCreateTrait
{
    protected ?AuctionLotUniqueLotNoLocker $auctionLotUniqueLotNoLocker = null;

    /**
     * @return AuctionLotUniqueLotNoLocker
     */
    protected function createAuctionLotUniqueLotNoLocker(): AuctionLotUniqueLotNoLocker
    {
        return $this->auctionLotUniqueLotNoLocker ?: AuctionLotUniqueLotNoLocker::new();
    }

    /**
     * @param AuctionLotUniqueLotNoLocker $auctionLotUniqueLotNoLocker
     * @return $this
     * @internal
     */
    public function setAuctionLotUniqueLotNoLocker(AuctionLotUniqueLotNoLocker $auctionLotUniqueLotNoLocker): static
    {
        $this->auctionLotUniqueLotNoLocker = $auctionLotUniqueLotNoLocker;
        return $this;
    }
}
