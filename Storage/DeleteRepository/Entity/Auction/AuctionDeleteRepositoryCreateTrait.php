<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Auction;

trait AuctionDeleteRepositoryCreateTrait
{
    protected ?AuctionDeleteRepository $auctionDeleteRepository = null;

    protected function createAuctionDeleteRepository(): AuctionDeleteRepository
    {
        return $this->auctionDeleteRepository ?: AuctionDeleteRepository::new();
    }

    /**
     * @param AuctionDeleteRepository $auctionDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionDeleteRepository(AuctionDeleteRepository $auctionDeleteRepository): static
    {
        $this->auctionDeleteRepository = $auctionDeleteRepository;
        return $this;
    }
}
