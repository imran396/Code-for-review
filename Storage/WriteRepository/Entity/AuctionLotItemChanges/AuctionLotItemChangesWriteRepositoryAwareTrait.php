<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionLotItemChanges;

trait AuctionLotItemChangesWriteRepositoryAwareTrait
{
    protected ?AuctionLotItemChangesWriteRepository $auctionLotItemChangesWriteRepository = null;

    protected function getAuctionLotItemChangesWriteRepository(): AuctionLotItemChangesWriteRepository
    {
        if ($this->auctionLotItemChangesWriteRepository === null) {
            $this->auctionLotItemChangesWriteRepository = AuctionLotItemChangesWriteRepository::new();
        }
        return $this->auctionLotItemChangesWriteRepository;
    }

    /**
     * @param AuctionLotItemChangesWriteRepository $auctionLotItemChangesWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionLotItemChangesWriteRepository(AuctionLotItemChangesWriteRepository $auctionLotItemChangesWriteRepository): static
    {
        $this->auctionLotItemChangesWriteRepository = $auctionLotItemChangesWriteRepository;
        return $this;
    }
}
