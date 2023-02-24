<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionLotItemBidderTerms;

trait AuctionLotItemBidderTermsReadRepositoryCreateTrait
{
    protected ?AuctionLotItemBidderTermsReadRepository $auctionLotItemBidderTermsReadRepository = null;

    protected function createAuctionLotItemBidderTermsReadRepository(): AuctionLotItemBidderTermsReadRepository
    {
        return $this->auctionLotItemBidderTermsReadRepository ?: AuctionLotItemBidderTermsReadRepository::new();
    }

    /**
     * @param AuctionLotItemBidderTermsReadRepository $auctionLotItemBidderTermsReadRepository
     * @return static
     * @internal
     */
    public function setAuctionLotItemBidderTermsReadRepository(AuctionLotItemBidderTermsReadRepository $auctionLotItemBidderTermsReadRepository): static
    {
        $this->auctionLotItemBidderTermsReadRepository = $auctionLotItemBidderTermsReadRepository;
        return $this;
    }
}
