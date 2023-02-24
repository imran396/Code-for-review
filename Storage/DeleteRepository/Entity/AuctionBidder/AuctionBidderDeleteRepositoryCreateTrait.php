<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionBidder;

trait AuctionBidderDeleteRepositoryCreateTrait
{
    protected ?AuctionBidderDeleteRepository $auctionBidderDeleteRepository = null;

    protected function createAuctionBidderDeleteRepository(): AuctionBidderDeleteRepository
    {
        return $this->auctionBidderDeleteRepository ?: AuctionBidderDeleteRepository::new();
    }

    /**
     * @param AuctionBidderDeleteRepository $auctionBidderDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionBidderDeleteRepository(AuctionBidderDeleteRepository $auctionBidderDeleteRepository): static
    {
        $this->auctionBidderDeleteRepository = $auctionBidderDeleteRepository;
        return $this;
    }
}
