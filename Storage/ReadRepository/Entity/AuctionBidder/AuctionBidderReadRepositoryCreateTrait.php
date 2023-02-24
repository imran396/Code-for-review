<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionBidder;

trait AuctionBidderReadRepositoryCreateTrait
{
    protected ?AuctionBidderReadRepository $auctionBidderReadRepository = null;

    protected function createAuctionBidderReadRepository(): AuctionBidderReadRepository
    {
        return $this->auctionBidderReadRepository ?: AuctionBidderReadRepository::new();
    }

    /**
     * @param AuctionBidderReadRepository $auctionBidderReadRepository
     * @return static
     * @internal
     */
    public function setAuctionBidderReadRepository(AuctionBidderReadRepository $auctionBidderReadRepository): static
    {
        $this->auctionBidderReadRepository = $auctionBidderReadRepository;
        return $this;
    }
}
