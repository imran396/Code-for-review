<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionAuctioneer;

trait AuctionAuctioneerWriteRepositoryAwareTrait
{
    protected ?AuctionAuctioneerWriteRepository $auctionAuctioneerWriteRepository = null;

    protected function getAuctionAuctioneerWriteRepository(): AuctionAuctioneerWriteRepository
    {
        if ($this->auctionAuctioneerWriteRepository === null) {
            $this->auctionAuctioneerWriteRepository = AuctionAuctioneerWriteRepository::new();
        }
        return $this->auctionAuctioneerWriteRepository;
    }

    /**
     * @param AuctionAuctioneerWriteRepository $auctionAuctioneerWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionAuctioneerWriteRepository(AuctionAuctioneerWriteRepository $auctionAuctioneerWriteRepository): static
    {
        $this->auctionAuctioneerWriteRepository = $auctionAuctioneerWriteRepository;
        return $this;
    }
}
