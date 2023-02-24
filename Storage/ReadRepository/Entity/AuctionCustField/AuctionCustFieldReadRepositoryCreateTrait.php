<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionCustField;

trait AuctionCustFieldReadRepositoryCreateTrait
{
    protected ?AuctionCustFieldReadRepository $auctionCustFieldReadRepository = null;

    protected function createAuctionCustFieldReadRepository(): AuctionCustFieldReadRepository
    {
        return $this->auctionCustFieldReadRepository ?: AuctionCustFieldReadRepository::new();
    }

    /**
     * @param AuctionCustFieldReadRepository $auctionCustFieldReadRepository
     * @return static
     * @internal
     */
    public function setAuctionCustFieldReadRepository(AuctionCustFieldReadRepository $auctionCustFieldReadRepository): static
    {
        $this->auctionCustFieldReadRepository = $auctionCustFieldReadRepository;
        return $this;
    }
}
