<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionDetailsCache;

trait AuctionDetailsCacheDeleteRepositoryCreateTrait
{
    protected ?AuctionDetailsCacheDeleteRepository $auctionDetailsCacheDeleteRepository = null;

    protected function createAuctionDetailsCacheDeleteRepository(): AuctionDetailsCacheDeleteRepository
    {
        return $this->auctionDetailsCacheDeleteRepository ?: AuctionDetailsCacheDeleteRepository::new();
    }

    /**
     * @param AuctionDetailsCacheDeleteRepository $auctionDetailsCacheDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionDetailsCacheDeleteRepository(AuctionDetailsCacheDeleteRepository $auctionDetailsCacheDeleteRepository): static
    {
        $this->auctionDetailsCacheDeleteRepository = $auctionDetailsCacheDeleteRepository;
        return $this;
    }
}
