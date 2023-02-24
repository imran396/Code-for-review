<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionImage;

trait AuctionImageDeleteRepositoryCreateTrait
{
    protected ?AuctionImageDeleteRepository $auctionImageDeleteRepository = null;

    protected function createAuctionImageDeleteRepository(): AuctionImageDeleteRepository
    {
        return $this->auctionImageDeleteRepository ?: AuctionImageDeleteRepository::new();
    }

    /**
     * @param AuctionImageDeleteRepository $auctionImageDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionImageDeleteRepository(AuctionImageDeleteRepository $auctionImageDeleteRepository): static
    {
        $this->auctionImageDeleteRepository = $auctionImageDeleteRepository;
        return $this;
    }
}
