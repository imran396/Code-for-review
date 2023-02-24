<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionAuctioneer;

trait AuctionAuctioneerReadRepositoryCreateTrait
{
    protected ?AuctionAuctioneerReadRepository $auctionAuctioneerReadRepository = null;

    protected function createAuctionAuctioneerReadRepository(): AuctionAuctioneerReadRepository
    {
        return $this->auctionAuctioneerReadRepository ?: AuctionAuctioneerReadRepository::new();
    }

    /**
     * @param AuctionAuctioneerReadRepository $auctionAuctioneerReadRepository
     * @return static
     * @internal
     */
    public function setAuctionAuctioneerReadRepository(AuctionAuctioneerReadRepository $auctionAuctioneerReadRepository): static
    {
        $this->auctionAuctioneerReadRepository = $auctionAuctioneerReadRepository;
        return $this;
    }
}
