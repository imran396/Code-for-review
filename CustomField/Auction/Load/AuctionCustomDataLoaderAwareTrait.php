<?php

namespace Sam\CustomField\Auction\Load;

/**
 * Trait AuctionCustomDataLoaderAwareTrait
 * @package Sam\CustomField\Auction\Load
 */
trait AuctionCustomDataLoaderAwareTrait
{
    protected ?AuctionCustomDataLoader $auctionCustomDataLoader = null;

    /**
     * @return AuctionCustomDataLoader
     */
    protected function getAuctionCustomDataLoader(): AuctionCustomDataLoader
    {
        if ($this->auctionCustomDataLoader === null) {
            $this->auctionCustomDataLoader = AuctionCustomDataLoader::new();
        }
        return $this->auctionCustomDataLoader;
    }

    /**
     * @param AuctionCustomDataLoader $loader
     * @return static
     * @internal
     */
    public function setAuctionCustomDataLoader(AuctionCustomDataLoader $loader): static
    {
        $this->auctionCustomDataLoader = $loader;
        return $this;
    }
}
