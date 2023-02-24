<?php

namespace Sam\AuctionLot\Load;

/**
 * Trait AuctionLotCacheLoaderAwareTrait
 * @package Sam\AuctionLot\Load
 */
trait AuctionLotCacheLoaderAwareTrait
{
    /**
     * @var AuctionLotCacheLoader|null
     */
    protected ?AuctionLotCacheLoader $auctionLotCacheLoader = null;

    /**
     * @return AuctionLotCacheLoader
     */
    protected function getAuctionLotCacheLoader(): AuctionLotCacheLoader
    {
        if ($this->auctionLotCacheLoader === null) {
            $this->auctionLotCacheLoader = AuctionLotCacheLoader::new();
        }
        return $this->auctionLotCacheLoader;
    }

    /**
     * @param AuctionLotCacheLoader $auctionLotCacheLoader
     * @return static
     * @internal
     */
    public function setAuctionLotCacheLoader(AuctionLotCacheLoader $auctionLotCacheLoader): static
    {
        $this->auctionLotCacheLoader = $auctionLotCacheLoader;
        return $this;
    }
}
