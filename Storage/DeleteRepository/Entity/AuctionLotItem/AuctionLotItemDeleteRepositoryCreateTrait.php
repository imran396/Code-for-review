<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionLotItem;

trait AuctionLotItemDeleteRepositoryCreateTrait
{
    protected ?AuctionLotItemDeleteRepository $auctionLotItemDeleteRepository = null;

    protected function createAuctionLotItemDeleteRepository(): AuctionLotItemDeleteRepository
    {
        return $this->auctionLotItemDeleteRepository ?: AuctionLotItemDeleteRepository::new();
    }

    /**
     * @param AuctionLotItemDeleteRepository $auctionLotItemDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionLotItemDeleteRepository(AuctionLotItemDeleteRepository $auctionLotItemDeleteRepository): static
    {
        $this->auctionLotItemDeleteRepository = $auctionLotItemDeleteRepository;
        return $this;
    }
}
