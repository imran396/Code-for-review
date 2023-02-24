<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionLotItemChanges;

trait AuctionLotItemChangesDeleteRepositoryCreateTrait
{
    protected ?AuctionLotItemChangesDeleteRepository $auctionLotItemChangesDeleteRepository = null;

    protected function createAuctionLotItemChangesDeleteRepository(): AuctionLotItemChangesDeleteRepository
    {
        return $this->auctionLotItemChangesDeleteRepository ?: AuctionLotItemChangesDeleteRepository::new();
    }

    /**
     * @param AuctionLotItemChangesDeleteRepository $auctionLotItemChangesDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionLotItemChangesDeleteRepository(AuctionLotItemChangesDeleteRepository $auctionLotItemChangesDeleteRepository): static
    {
        $this->auctionLotItemChangesDeleteRepository = $auctionLotItemChangesDeleteRepository;
        return $this;
    }
}
