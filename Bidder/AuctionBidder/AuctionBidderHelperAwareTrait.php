<?php

namespace Sam\Bidder\AuctionBidder;

/**
 * Trait AuctionBidderHelperAwareTrait
 * @package Sam\Bidder\AuctionBidder
 */
trait AuctionBidderHelperAwareTrait
{
    protected ?Helper $auctionBidderHelper = null;

    /**
     * @return Helper
     */
    protected function getAuctionBidderHelper(): Helper
    {
        if ($this->auctionBidderHelper === null) {
            $this->auctionBidderHelper = Helper::new();
        }
        return $this->auctionBidderHelper;
    }

    /**
     * @param Helper $auctionBidderHelper
     * @return static
     * @internal
     */
    public function setAuctionBidderHelper(Helper $auctionBidderHelper): static
    {
        $this->auctionBidderHelper = $auctionBidderHelper;
        return $this;
    }
}
