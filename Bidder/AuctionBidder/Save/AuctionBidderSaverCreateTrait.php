<?php
/**
 * SAM-6719: Apply WriteRepository and unit tests to auction bidder command services
 */

namespace Sam\Bidder\AuctionBidder\Save;

/**
 * Trait AuctionBidderSaverAwareTrait
 * @package Sam\Bidder\AuctionBidder
 */
trait AuctionBidderSaverCreateTrait
{
    protected ?AuctionBidderSaver $auctionBidderSaver = null;

    /**
     * @return AuctionBidderSaver
     */
    protected function createAuctionBidderSaver(): AuctionBidderSaver
    {
        return $this->auctionBidderSaver ?: AuctionBidderSaver::new();
    }

    /**
     * @param AuctionBidderSaver $auctionBidderSaver
     * @return static
     * @internal
     */
    public function setAuctionBidderSaver(AuctionBidderSaver $auctionBidderSaver): static
    {
        $this->auctionBidderSaver = $auctionBidderSaver;
        return $this;
    }
}
