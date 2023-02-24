<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionBidderOptionSelection;

trait AuctionBidderOptionSelectionDeleteRepositoryCreateTrait
{
    protected ?AuctionBidderOptionSelectionDeleteRepository $auctionBidderOptionSelectionDeleteRepository = null;

    protected function createAuctionBidderOptionSelectionDeleteRepository(): AuctionBidderOptionSelectionDeleteRepository
    {
        return $this->auctionBidderOptionSelectionDeleteRepository ?: AuctionBidderOptionSelectionDeleteRepository::new();
    }

    /**
     * @param AuctionBidderOptionSelectionDeleteRepository $auctionBidderOptionSelectionDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionBidderOptionSelectionDeleteRepository(AuctionBidderOptionSelectionDeleteRepository $auctionBidderOptionSelectionDeleteRepository): static
    {
        $this->auctionBidderOptionSelectionDeleteRepository = $auctionBidderOptionSelectionDeleteRepository;
        return $this;
    }
}
