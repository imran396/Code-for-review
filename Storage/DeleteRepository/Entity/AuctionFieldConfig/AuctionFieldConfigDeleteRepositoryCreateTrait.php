<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionFieldConfig;

trait AuctionFieldConfigDeleteRepositoryCreateTrait
{
    protected ?AuctionFieldConfigDeleteRepository $auctionFieldConfigDeleteRepository = null;

    protected function createAuctionFieldConfigDeleteRepository(): AuctionFieldConfigDeleteRepository
    {
        return $this->auctionFieldConfigDeleteRepository ?: AuctionFieldConfigDeleteRepository::new();
    }

    /**
     * @param AuctionFieldConfigDeleteRepository $auctionFieldConfigDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionFieldConfigDeleteRepository(AuctionFieldConfigDeleteRepository $auctionFieldConfigDeleteRepository): static
    {
        $this->auctionFieldConfigDeleteRepository = $auctionFieldConfigDeleteRepository;
        return $this;
    }
}
