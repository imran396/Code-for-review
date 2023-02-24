<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionDynamic;

trait AuctionDynamicReadRepositoryCreateTrait
{
    protected ?AuctionDynamicReadRepository $auctionDynamicReadRepository = null;

    protected function createAuctionDynamicReadRepository(): AuctionDynamicReadRepository
    {
        return $this->auctionDynamicReadRepository ?: AuctionDynamicReadRepository::new();
    }

    /**
     * @param AuctionDynamicReadRepository $auctionDynamicReadRepository
     * @return static
     * @internal
     */
    public function setAuctionDynamicReadRepository(AuctionDynamicReadRepository $auctionDynamicReadRepository): static
    {
        $this->auctionDynamicReadRepository = $auctionDynamicReadRepository;
        return $this;
    }
}
