<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionBidderOptionSelection;

trait AuctionBidderOptionSelectionReadRepositoryCreateTrait
{
    protected ?AuctionBidderOptionSelectionReadRepository $auctionBidderOptionSelectionReadRepository = null;

    protected function createAuctionBidderOptionSelectionReadRepository(): AuctionBidderOptionSelectionReadRepository
    {
        return $this->auctionBidderOptionSelectionReadRepository ?: AuctionBidderOptionSelectionReadRepository::new();
    }

    /**
     * @param AuctionBidderOptionSelectionReadRepository $auctionBidderOptionSelectionReadRepository
     * @return static
     * @internal
     */
    public function setAuctionBidderOptionSelectionReadRepository(AuctionBidderOptionSelectionReadRepository $auctionBidderOptionSelectionReadRepository): static
    {
        $this->auctionBidderOptionSelectionReadRepository = $auctionBidderOptionSelectionReadRepository;
        return $this;
    }
}
