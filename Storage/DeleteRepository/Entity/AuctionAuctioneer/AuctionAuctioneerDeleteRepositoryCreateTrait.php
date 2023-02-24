<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionAuctioneer;

trait AuctionAuctioneerDeleteRepositoryCreateTrait
{
    protected ?AuctionAuctioneerDeleteRepository $auctionAuctioneerDeleteRepository = null;

    protected function createAuctionAuctioneerDeleteRepository(): AuctionAuctioneerDeleteRepository
    {
        return $this->auctionAuctioneerDeleteRepository ?: AuctionAuctioneerDeleteRepository::new();
    }

    /**
     * @param AuctionAuctioneerDeleteRepository $auctionAuctioneerDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionAuctioneerDeleteRepository(AuctionAuctioneerDeleteRepository $auctionAuctioneerDeleteRepository): static
    {
        $this->auctionAuctioneerDeleteRepository = $auctionAuctioneerDeleteRepository;
        return $this;
    }
}
