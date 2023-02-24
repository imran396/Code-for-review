<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionFieldConfig;

trait AuctionFieldConfigReadRepositoryCreateTrait
{
    protected ?AuctionFieldConfigReadRepository $auctionFieldConfigReadRepository = null;

    protected function createAuctionFieldConfigReadRepository(): AuctionFieldConfigReadRepository
    {
        return $this->auctionFieldConfigReadRepository ?: AuctionFieldConfigReadRepository::new();
    }

    /**
     * @param AuctionFieldConfigReadRepository $auctionFieldConfigReadRepository
     * @return static
     * @internal
     */
    public function setAuctionFieldConfigReadRepository(AuctionFieldConfigReadRepository $auctionFieldConfigReadRepository): static
    {
        $this->auctionFieldConfigReadRepository = $auctionFieldConfigReadRepository;
        return $this;
    }
}
