<?php
/**
 * SAM-5658: Multiple Auction Reorderer for lots
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 30, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Order\Reorder\Auction;

/**
 * Trait AuctionLotMultipleAuctionReordererCreateTrait
 * @package Sam\AuctionLot\Order\Reorder\Auction
 */
trait AuctionLotMultipleAuctionReordererCreateTrait
{
    protected ?AuctionLotMultipleAuctionReorderer $auctionLotMultipleAuctionReorderer = null;

    /**
     * @return AuctionLotMultipleAuctionReorderer
     */
    protected function createAuctionLotMultipleAuctionReorderer(): AuctionLotMultipleAuctionReorderer
    {
        return $this->auctionLotMultipleAuctionReorderer ?: AuctionLotMultipleAuctionReorderer::new();
    }

    /**
     * @param AuctionLotMultipleAuctionReorderer $auctionLotMultipleAuctionReorderer
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionLotMultipleAuctionReorderer(AuctionLotMultipleAuctionReorderer $auctionLotMultipleAuctionReorderer): static
    {
        $this->auctionLotMultipleAuctionReorderer = $auctionLotMultipleAuctionReorderer;
        return $this;
    }
}
