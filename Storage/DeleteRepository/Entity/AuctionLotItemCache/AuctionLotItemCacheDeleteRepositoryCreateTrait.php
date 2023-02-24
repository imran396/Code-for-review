<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionLotItemCache;

trait AuctionLotItemCacheDeleteRepositoryCreateTrait
{
    protected ?AuctionLotItemCacheDeleteRepository $auctionLotItemCacheDeleteRepository = null;

    protected function createAuctionLotItemCacheDeleteRepository(): AuctionLotItemCacheDeleteRepository
    {
        return $this->auctionLotItemCacheDeleteRepository ?: AuctionLotItemCacheDeleteRepository::new();
    }

    /**
     * @param AuctionLotItemCacheDeleteRepository $auctionLotItemCacheDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionLotItemCacheDeleteRepository(AuctionLotItemCacheDeleteRepository $auctionLotItemCacheDeleteRepository): static
    {
        $this->auctionLotItemCacheDeleteRepository = $auctionLotItemCacheDeleteRepository;
        return $this;
    }
}
