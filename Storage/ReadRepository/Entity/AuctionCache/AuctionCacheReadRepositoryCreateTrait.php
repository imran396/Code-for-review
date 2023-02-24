<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionCache;

trait AuctionCacheReadRepositoryCreateTrait
{
    protected ?AuctionCacheReadRepository $auctionCacheReadRepository = null;

    protected function createAuctionCacheReadRepository(): AuctionCacheReadRepository
    {
        return $this->auctionCacheReadRepository ?: AuctionCacheReadRepository::new();
    }

    /**
     * @param AuctionCacheReadRepository $auctionCacheReadRepository
     * @return static
     * @internal
     */
    public function setAuctionCacheReadRepository(AuctionCacheReadRepository $auctionCacheReadRepository): static
    {
        $this->auctionCacheReadRepository = $auctionCacheReadRepository;
        return $this;
    }
}
