<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionCache;

trait AuctionCacheDeleteRepositoryCreateTrait
{
    protected ?AuctionCacheDeleteRepository $auctionCacheDeleteRepository = null;

    protected function createAuctionCacheDeleteRepository(): AuctionCacheDeleteRepository
    {
        return $this->auctionCacheDeleteRepository ?: AuctionCacheDeleteRepository::new();
    }

    /**
     * @param AuctionCacheDeleteRepository $auctionCacheDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionCacheDeleteRepository(AuctionCacheDeleteRepository $auctionCacheDeleteRepository): static
    {
        $this->auctionCacheDeleteRepository = $auctionCacheDeleteRepository;
        return $this;
    }
}
