<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionFieldConfig;

trait AuctionFieldConfigWriteRepositoryAwareTrait
{
    protected ?AuctionFieldConfigWriteRepository $auctionFieldConfigWriteRepository = null;

    protected function getAuctionFieldConfigWriteRepository(): AuctionFieldConfigWriteRepository
    {
        if ($this->auctionFieldConfigWriteRepository === null) {
            $this->auctionFieldConfigWriteRepository = AuctionFieldConfigWriteRepository::new();
        }
        return $this->auctionFieldConfigWriteRepository;
    }

    /**
     * @param AuctionFieldConfigWriteRepository $auctionFieldConfigWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionFieldConfigWriteRepository(AuctionFieldConfigWriteRepository $auctionFieldConfigWriteRepository): static
    {
        $this->auctionFieldConfigWriteRepository = $auctionFieldConfigWriteRepository;
        return $this;
    }
}
