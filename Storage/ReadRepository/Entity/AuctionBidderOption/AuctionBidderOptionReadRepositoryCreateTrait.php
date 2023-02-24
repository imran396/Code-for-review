<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionBidderOption;

trait AuctionBidderOptionReadRepositoryCreateTrait
{
    protected ?AuctionBidderOptionReadRepository $auctionBidderOptionReadRepository = null;

    protected function createAuctionBidderOptionReadRepository(): AuctionBidderOptionReadRepository
    {
        return $this->auctionBidderOptionReadRepository ?: AuctionBidderOptionReadRepository::new();
    }

    /**
     * @param AuctionBidderOptionReadRepository $auctionBidderOptionReadRepository
     * @return static
     * @internal
     */
    public function setAuctionBidderOptionReadRepository(AuctionBidderOptionReadRepository $auctionBidderOptionReadRepository): static
    {
        $this->auctionBidderOptionReadRepository = $auctionBidderOptionReadRepository;
        return $this;
    }
}
