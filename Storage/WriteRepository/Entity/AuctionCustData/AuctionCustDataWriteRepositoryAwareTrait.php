<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionCustData;

trait AuctionCustDataWriteRepositoryAwareTrait
{
    protected ?AuctionCustDataWriteRepository $auctionCustDataWriteRepository = null;

    protected function getAuctionCustDataWriteRepository(): AuctionCustDataWriteRepository
    {
        if ($this->auctionCustDataWriteRepository === null) {
            $this->auctionCustDataWriteRepository = AuctionCustDataWriteRepository::new();
        }
        return $this->auctionCustDataWriteRepository;
    }

    /**
     * @param AuctionCustDataWriteRepository $auctionCustDataWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionCustDataWriteRepository(AuctionCustDataWriteRepository $auctionCustDataWriteRepository): static
    {
        $this->auctionCustDataWriteRepository = $auctionCustDataWriteRepository;
        return $this;
    }
}
