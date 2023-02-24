<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionLotItem;

trait AuctionLotItemWriteRepositoryAwareTrait
{
    protected ?AuctionLotItemWriteRepository $auctionLotItemWriteRepository = null;

    protected function getAuctionLotItemWriteRepository(): AuctionLotItemWriteRepository
    {
        if ($this->auctionLotItemWriteRepository === null) {
            $this->auctionLotItemWriteRepository = AuctionLotItemWriteRepository::new();
        }
        return $this->auctionLotItemWriteRepository;
    }

    /**
     * @param AuctionLotItemWriteRepository $auctionLotItemWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionLotItemWriteRepository(AuctionLotItemWriteRepository $auctionLotItemWriteRepository): static
    {
        $this->auctionLotItemWriteRepository = $auctionLotItemWriteRepository;
        return $this;
    }
}
