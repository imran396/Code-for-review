<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionLotItem;

trait AuctionLotItemReadRepositoryCreateTrait
{
    protected ?AuctionLotItemReadRepository $auctionLotItemReadRepository = null;

    protected function createAuctionLotItemReadRepository(): AuctionLotItemReadRepository
    {
        return $this->auctionLotItemReadRepository ?: AuctionLotItemReadRepository::new();
    }

    /**
     * @param AuctionLotItemReadRepository $auctionLotItemReadRepository
     * @return static
     * @internal
     */
    public function setAuctionLotItemReadRepository(AuctionLotItemReadRepository $auctionLotItemReadRepository): static
    {
        $this->auctionLotItemReadRepository = $auctionLotItemReadRepository;
        return $this;
    }
}
