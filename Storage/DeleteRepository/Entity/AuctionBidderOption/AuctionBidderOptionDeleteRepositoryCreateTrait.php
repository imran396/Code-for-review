<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionBidderOption;

trait AuctionBidderOptionDeleteRepositoryCreateTrait
{
    protected ?AuctionBidderOptionDeleteRepository $auctionBidderOptionDeleteRepository = null;

    protected function createAuctionBidderOptionDeleteRepository(): AuctionBidderOptionDeleteRepository
    {
        return $this->auctionBidderOptionDeleteRepository ?: AuctionBidderOptionDeleteRepository::new();
    }

    /**
     * @param AuctionBidderOptionDeleteRepository $auctionBidderOptionDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionBidderOptionDeleteRepository(AuctionBidderOptionDeleteRepository $auctionBidderOptionDeleteRepository): static
    {
        $this->auctionBidderOptionDeleteRepository = $auctionBidderOptionDeleteRepository;
        return $this;
    }
}
