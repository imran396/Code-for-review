<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionCustData;

trait AuctionCustDataReadRepositoryCreateTrait
{
    protected ?AuctionCustDataReadRepository $auctionCustDataReadRepository = null;

    protected function createAuctionCustDataReadRepository(): AuctionCustDataReadRepository
    {
        return $this->auctionCustDataReadRepository ?: AuctionCustDataReadRepository::new();
    }

    /**
     * @param AuctionCustDataReadRepository $auctionCustDataReadRepository
     * @return static
     * @internal
     */
    public function setAuctionCustDataReadRepository(AuctionCustDataReadRepository $auctionCustDataReadRepository): static
    {
        $this->auctionCustDataReadRepository = $auctionCustDataReadRepository;
        return $this;
    }
}
