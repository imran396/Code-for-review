<?php

namespace Sam\AuctionLot\Load;

/**
 * Trait AuctionLotLoaderAwareTrait
 * @package Sam\AuctionLot\Load
 */
trait AuctionLotLoaderAwareTrait
{
    /**
     * @var AuctionLotLoader|null
     */
    protected ?AuctionLotLoader $auctionLotLoader = null;

    /**
     * @return AuctionLotLoader
     */
    protected function getAuctionLotLoader(): AuctionLotLoader
    {
        if ($this->auctionLotLoader === null) {
            $this->auctionLotLoader = AuctionLotLoader::new();
        }
        return $this->auctionLotLoader;
    }

    /**
     * @param AuctionLotLoader $auctionLotLoader
     * @return static
     * @internal
     */
    public function setAuctionLotLoader(AuctionLotLoader $auctionLotLoader): static
    {
        $this->auctionLotLoader = $auctionLotLoader;
        return $this;
    }
}
