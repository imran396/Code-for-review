<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionBidderOptionSelection;

trait AuctionBidderOptionSelectionWriteRepositoryAwareTrait
{
    protected ?AuctionBidderOptionSelectionWriteRepository $auctionBidderOptionSelectionWriteRepository = null;

    protected function getAuctionBidderOptionSelectionWriteRepository(): AuctionBidderOptionSelectionWriteRepository
    {
        if ($this->auctionBidderOptionSelectionWriteRepository === null) {
            $this->auctionBidderOptionSelectionWriteRepository = AuctionBidderOptionSelectionWriteRepository::new();
        }
        return $this->auctionBidderOptionSelectionWriteRepository;
    }

    /**
     * @param AuctionBidderOptionSelectionWriteRepository $auctionBidderOptionSelectionWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionBidderOptionSelectionWriteRepository(AuctionBidderOptionSelectionWriteRepository $auctionBidderOptionSelectionWriteRepository): static
    {
        $this->auctionBidderOptionSelectionWriteRepository = $auctionBidderOptionSelectionWriteRepository;
        return $this;
    }
}
