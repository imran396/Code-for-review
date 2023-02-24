<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionLotItemCache;

trait AuctionLotItemCacheReadRepositoryCreateTrait
{
    protected ?AuctionLotItemCacheReadRepository $auctionLotItemCacheReadRepository = null;

    protected function createAuctionLotItemCacheReadRepository(): AuctionLotItemCacheReadRepository
    {
        return $this->auctionLotItemCacheReadRepository ?: AuctionLotItemCacheReadRepository::new();
    }

    /**
     * @param AuctionLotItemCacheReadRepository $auctionLotItemCacheReadRepository
     * @return static
     * @internal
     */
    public function setAuctionLotItemCacheReadRepository(AuctionLotItemCacheReadRepository $auctionLotItemCacheReadRepository): static
    {
        $this->auctionLotItemCacheReadRepository = $auctionLotItemCacheReadRepository;
        return $this;
    }
}
