<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionDetailsCache;

trait AuctionDetailsCacheWriteRepositoryAwareTrait
{
    protected ?AuctionDetailsCacheWriteRepository $auctionDetailsCacheWriteRepository = null;

    protected function getAuctionDetailsCacheWriteRepository(): AuctionDetailsCacheWriteRepository
    {
        if ($this->auctionDetailsCacheWriteRepository === null) {
            $this->auctionDetailsCacheWriteRepository = AuctionDetailsCacheWriteRepository::new();
        }
        return $this->auctionDetailsCacheWriteRepository;
    }

    /**
     * @param AuctionDetailsCacheWriteRepository $auctionDetailsCacheWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionDetailsCacheWriteRepository(AuctionDetailsCacheWriteRepository $auctionDetailsCacheWriteRepository): static
    {
        $this->auctionDetailsCacheWriteRepository = $auctionDetailsCacheWriteRepository;
        return $this;
    }
}
