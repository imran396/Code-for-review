<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionDynamic;

trait AuctionDynamicDeleteRepositoryCreateTrait
{
    protected ?AuctionDynamicDeleteRepository $auctionDynamicDeleteRepository = null;

    protected function createAuctionDynamicDeleteRepository(): AuctionDynamicDeleteRepository
    {
        return $this->auctionDynamicDeleteRepository ?: AuctionDynamicDeleteRepository::new();
    }

    /**
     * @param AuctionDynamicDeleteRepository $auctionDynamicDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionDynamicDeleteRepository(AuctionDynamicDeleteRepository $auctionDynamicDeleteRepository): static
    {
        $this->auctionDynamicDeleteRepository = $auctionDynamicDeleteRepository;
        return $this;
    }
}
