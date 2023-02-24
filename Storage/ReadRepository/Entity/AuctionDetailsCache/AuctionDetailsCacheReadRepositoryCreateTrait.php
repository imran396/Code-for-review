<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionDetailsCache;

trait AuctionDetailsCacheReadRepositoryCreateTrait
{
    protected ?AuctionDetailsCacheReadRepository $auctionDetailsCacheReadRepository = null;

    protected function createAuctionDetailsCacheReadRepository(): AuctionDetailsCacheReadRepository
    {
        return $this->auctionDetailsCacheReadRepository ?: AuctionDetailsCacheReadRepository::new();
    }

    /**
     * @param AuctionDetailsCacheReadRepository $auctionDetailsCacheReadRepository
     * @return static
     * @internal
     */
    public function setAuctionDetailsCacheReadRepository(AuctionDetailsCacheReadRepository $auctionDetailsCacheReadRepository): static
    {
        $this->auctionDetailsCacheReadRepository = $auctionDetailsCacheReadRepository;
        return $this;
    }
}
