<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionImage;

trait AuctionImageWriteRepositoryAwareTrait
{
    protected ?AuctionImageWriteRepository $auctionImageWriteRepository = null;

    protected function getAuctionImageWriteRepository(): AuctionImageWriteRepository
    {
        if ($this->auctionImageWriteRepository === null) {
            $this->auctionImageWriteRepository = AuctionImageWriteRepository::new();
        }
        return $this->auctionImageWriteRepository;
    }

    /**
     * @param AuctionImageWriteRepository $auctionImageWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionImageWriteRepository(AuctionImageWriteRepository $auctionImageWriteRepository): static
    {
        $this->auctionImageWriteRepository = $auctionImageWriteRepository;
        return $this;
    }
}
