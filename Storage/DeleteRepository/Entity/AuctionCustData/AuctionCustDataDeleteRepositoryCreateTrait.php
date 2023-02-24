<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionCustData;

trait AuctionCustDataDeleteRepositoryCreateTrait
{
    protected ?AuctionCustDataDeleteRepository $auctionCustDataDeleteRepository = null;

    protected function createAuctionCustDataDeleteRepository(): AuctionCustDataDeleteRepository
    {
        return $this->auctionCustDataDeleteRepository ?: AuctionCustDataDeleteRepository::new();
    }

    /**
     * @param AuctionCustDataDeleteRepository $auctionCustDataDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionCustDataDeleteRepository(AuctionCustDataDeleteRepository $auctionCustDataDeleteRepository): static
    {
        $this->auctionCustDataDeleteRepository = $auctionCustDataDeleteRepository;
        return $this;
    }
}
