<?php

namespace Sam\Bidder\AuctionBidder;

/**
 * Trait AuctionBidderNotifierAwareTrait
 * @package Sam\Bidder\AuctionBidder
 */
trait AuctionBidderNotifierAwareTrait
{
    protected ?Notifier $auctionBidderNotifier = null;

    /**
     * @return Notifier
     */
    protected function getAuctionBidderNotifier(): Notifier
    {
        if ($this->auctionBidderNotifier === null) {
            $this->auctionBidderNotifier = Notifier::new();
        }
        return $this->auctionBidderNotifier;
    }

    /**
     * @param Notifier $auctionBidderNotifier
     * @return static
     * @internal
     */
    public function setAuctionBidderNotifier(Notifier $auctionBidderNotifier): static
    {
        $this->auctionBidderNotifier = $auctionBidderNotifier;
        return $this;
    }
}
