<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionCustField;

trait AuctionCustFieldDeleteRepositoryCreateTrait
{
    protected ?AuctionCustFieldDeleteRepository $auctionCustFieldDeleteRepository = null;

    protected function createAuctionCustFieldDeleteRepository(): AuctionCustFieldDeleteRepository
    {
        return $this->auctionCustFieldDeleteRepository ?: AuctionCustFieldDeleteRepository::new();
    }

    /**
     * @param AuctionCustFieldDeleteRepository $auctionCustFieldDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionCustFieldDeleteRepository(AuctionCustFieldDeleteRepository $auctionCustFieldDeleteRepository): static
    {
        $this->auctionCustFieldDeleteRepository = $auctionCustFieldDeleteRepository;
        return $this;
    }
}
