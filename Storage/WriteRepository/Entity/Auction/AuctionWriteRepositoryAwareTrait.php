<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Auction;

trait AuctionWriteRepositoryAwareTrait
{
    protected ?AuctionWriteRepository $auctionWriteRepository = null;

    protected function getAuctionWriteRepository(): AuctionWriteRepository
    {
        if ($this->auctionWriteRepository === null) {
            $this->auctionWriteRepository = AuctionWriteRepository::new();
        }
        return $this->auctionWriteRepository;
    }

    /**
     * @param AuctionWriteRepository $auctionWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionWriteRepository(AuctionWriteRepository $auctionWriteRepository): static
    {
        $this->auctionWriteRepository = $auctionWriteRepository;
        return $this;
    }
}
