<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionCustField;

trait AuctionCustFieldWriteRepositoryAwareTrait
{
    protected ?AuctionCustFieldWriteRepository $auctionCustFieldWriteRepository = null;

    protected function getAuctionCustFieldWriteRepository(): AuctionCustFieldWriteRepository
    {
        if ($this->auctionCustFieldWriteRepository === null) {
            $this->auctionCustFieldWriteRepository = AuctionCustFieldWriteRepository::new();
        }
        return $this->auctionCustFieldWriteRepository;
    }

    /**
     * @param AuctionCustFieldWriteRepository $auctionCustFieldWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionCustFieldWriteRepository(AuctionCustFieldWriteRepository $auctionCustFieldWriteRepository): static
    {
        $this->auctionCustFieldWriteRepository = $auctionCustFieldWriteRepository;
        return $this;
    }
}
