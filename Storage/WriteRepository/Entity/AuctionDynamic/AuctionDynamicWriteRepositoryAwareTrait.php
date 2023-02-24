<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionDynamic;

trait AuctionDynamicWriteRepositoryAwareTrait
{
    protected ?AuctionDynamicWriteRepository $auctionDynamicWriteRepository = null;

    protected function getAuctionDynamicWriteRepository(): AuctionDynamicWriteRepository
    {
        if ($this->auctionDynamicWriteRepository === null) {
            $this->auctionDynamicWriteRepository = AuctionDynamicWriteRepository::new();
        }
        return $this->auctionDynamicWriteRepository;
    }

    /**
     * @param AuctionDynamicWriteRepository $auctionDynamicWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionDynamicWriteRepository(AuctionDynamicWriteRepository $auctionDynamicWriteRepository): static
    {
        $this->auctionDynamicWriteRepository = $auctionDynamicWriteRepository;
        return $this;
    }
}
