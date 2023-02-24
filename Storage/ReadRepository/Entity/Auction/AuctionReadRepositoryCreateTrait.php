<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Auction;

trait AuctionReadRepositoryCreateTrait
{
    protected ?AuctionReadRepository $auctionReadRepository = null;

    protected function createAuctionReadRepository(): AuctionReadRepository
    {
        return $this->auctionReadRepository ?: AuctionReadRepository::new();
    }

    /**
     * @param AuctionReadRepository $auctionReadRepository
     * @return static
     * @internal
     */
    public function setAuctionReadRepository(AuctionReadRepository $auctionReadRepository): static
    {
        $this->auctionReadRepository = $auctionReadRepository;
        return $this;
    }
}
