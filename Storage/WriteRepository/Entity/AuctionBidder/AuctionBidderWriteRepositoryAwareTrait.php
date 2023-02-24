<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionBidder;

trait AuctionBidderWriteRepositoryAwareTrait
{
    protected ?AuctionBidderWriteRepository $auctionBidderWriteRepository = null;

    protected function getAuctionBidderWriteRepository(): AuctionBidderWriteRepository
    {
        if ($this->auctionBidderWriteRepository === null) {
            $this->auctionBidderWriteRepository = AuctionBidderWriteRepository::new();
        }
        return $this->auctionBidderWriteRepository;
    }

    /**
     * @param AuctionBidderWriteRepository $auctionBidderWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionBidderWriteRepository(AuctionBidderWriteRepository $auctionBidderWriteRepository): static
    {
        $this->auctionBidderWriteRepository = $auctionBidderWriteRepository;
        return $this;
    }
}
