<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionLotItemChanges;

trait AuctionLotItemChangesReadRepositoryCreateTrait
{
    protected ?AuctionLotItemChangesReadRepository $auctionLotItemChangesReadRepository = null;

    protected function createAuctionLotItemChangesReadRepository(): AuctionLotItemChangesReadRepository
    {
        return $this->auctionLotItemChangesReadRepository ?: AuctionLotItemChangesReadRepository::new();
    }

    /**
     * @param AuctionLotItemChangesReadRepository $auctionLotItemChangesReadRepository
     * @return static
     * @internal
     */
    public function setAuctionLotItemChangesReadRepository(AuctionLotItemChangesReadRepository $auctionLotItemChangesReadRepository): static
    {
        $this->auctionLotItemChangesReadRepository = $auctionLotItemChangesReadRepository;
        return $this;
    }
}
