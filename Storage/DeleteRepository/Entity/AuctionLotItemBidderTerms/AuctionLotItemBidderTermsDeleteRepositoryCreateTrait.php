<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionLotItemBidderTerms;

trait AuctionLotItemBidderTermsDeleteRepositoryCreateTrait
{
    protected ?AuctionLotItemBidderTermsDeleteRepository $auctionLotItemBidderTermsDeleteRepository = null;

    protected function createAuctionLotItemBidderTermsDeleteRepository(): AuctionLotItemBidderTermsDeleteRepository
    {
        return $this->auctionLotItemBidderTermsDeleteRepository ?: AuctionLotItemBidderTermsDeleteRepository::new();
    }

    /**
     * @param AuctionLotItemBidderTermsDeleteRepository $auctionLotItemBidderTermsDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionLotItemBidderTermsDeleteRepository(AuctionLotItemBidderTermsDeleteRepository $auctionLotItemBidderTermsDeleteRepository): static
    {
        $this->auctionLotItemBidderTermsDeleteRepository = $auctionLotItemBidderTermsDeleteRepository;
        return $this;
    }
}
