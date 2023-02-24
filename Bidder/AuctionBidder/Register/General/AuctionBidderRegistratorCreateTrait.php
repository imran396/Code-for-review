<?php

namespace Sam\Bidder\AuctionBidder\Register\General;

/**
 * Trait AuctionBidderRegistratorCreateTrait
 * @package Sam\Bidder\AuctionBidder\Register
 */
trait AuctionBidderRegistratorCreateTrait
{
    protected ?AuctionBidderRegistrator $auctionBidderRegistrator = null;

    /**
     * @return AuctionBidderRegistrator
     */
    protected function createAuctionBidderRegistrator(): AuctionBidderRegistrator
    {
        return $this->auctionBidderRegistrator ?: AuctionBidderRegistrator::new();
    }

    /**
     * @param AuctionBidderRegistrator $auctionBidderRegistrator
     * @return static
     */
    public function setAuctionBidderRegistrator(AuctionBidderRegistrator $auctionBidderRegistrator): static
    {
        $this->auctionBidderRegistrator = $auctionBidderRegistrator;
        return $this;
    }
}
