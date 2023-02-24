<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionCache;

trait AuctionCacheWriteRepositoryAwareTrait
{
    protected ?AuctionCacheWriteRepository $auctionCacheWriteRepository = null;

    protected function getAuctionCacheWriteRepository(): AuctionCacheWriteRepository
    {
        if ($this->auctionCacheWriteRepository === null) {
            $this->auctionCacheWriteRepository = AuctionCacheWriteRepository::new();
        }
        return $this->auctionCacheWriteRepository;
    }

    /**
     * @param AuctionCacheWriteRepository $auctionCacheWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionCacheWriteRepository(AuctionCacheWriteRepository $auctionCacheWriteRepository): static
    {
        $this->auctionCacheWriteRepository = $auctionCacheWriteRepository;
        return $this;
    }
}
