<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionLotItemBidderTerms;

trait AuctionLotItemBidderTermsWriteRepositoryAwareTrait
{
    protected ?AuctionLotItemBidderTermsWriteRepository $auctionLotItemBidderTermsWriteRepository = null;

    protected function getAuctionLotItemBidderTermsWriteRepository(): AuctionLotItemBidderTermsWriteRepository
    {
        if ($this->auctionLotItemBidderTermsWriteRepository === null) {
            $this->auctionLotItemBidderTermsWriteRepository = AuctionLotItemBidderTermsWriteRepository::new();
        }
        return $this->auctionLotItemBidderTermsWriteRepository;
    }

    /**
     * @param AuctionLotItemBidderTermsWriteRepository $auctionLotItemBidderTermsWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionLotItemBidderTermsWriteRepository(AuctionLotItemBidderTermsWriteRepository $auctionLotItemBidderTermsWriteRepository): static
    {
        $this->auctionLotItemBidderTermsWriteRepository = $auctionLotItemBidderTermsWriteRepository;
        return $this;
    }
}
