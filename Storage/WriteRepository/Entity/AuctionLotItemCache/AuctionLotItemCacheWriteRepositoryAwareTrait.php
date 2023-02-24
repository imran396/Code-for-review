<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionLotItemCache;

trait AuctionLotItemCacheWriteRepositoryAwareTrait
{
    protected ?AuctionLotItemCacheWriteRepository $auctionLotItemCacheWriteRepository = null;

    protected function getAuctionLotItemCacheWriteRepository(): AuctionLotItemCacheWriteRepository
    {
        if ($this->auctionLotItemCacheWriteRepository === null) {
            $this->auctionLotItemCacheWriteRepository = AuctionLotItemCacheWriteRepository::new();
        }
        return $this->auctionLotItemCacheWriteRepository;
    }

    /**
     * @param AuctionLotItemCacheWriteRepository $auctionLotItemCacheWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionLotItemCacheWriteRepository(AuctionLotItemCacheWriteRepository $auctionLotItemCacheWriteRepository): static
    {
        $this->auctionLotItemCacheWriteRepository = $auctionLotItemCacheWriteRepository;
        return $this;
    }
}
