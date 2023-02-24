<?php

namespace Sam\CustomField\Auction\Save;

/**
 * Trait AuctionCustomDataUpdaterAwareTrait
 * @package Sam\CustomField\Auction\Save
 */
trait AuctionCustomDataUpdaterAwareTrait
{
    protected ?AuctionCustomDataUpdater $auctionCustomDataUpdater = null;

    /**
     * @return AuctionCustomDataUpdater
     */
    protected function getAuctionCustomDataUpdater(): AuctionCustomDataUpdater
    {
        if ($this->auctionCustomDataUpdater === null) {
            $this->auctionCustomDataUpdater = AuctionCustomDataUpdater::new();
        }
        return $this->auctionCustomDataUpdater;
    }

    /**
     * @param AuctionCustomDataUpdater $updater
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionCustomDataUpdater(AuctionCustomDataUpdater $updater): static
    {
        $this->auctionCustomDataUpdater = $updater;
        return $this;
    }
}
