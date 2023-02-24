<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AuctionCurrency;

trait AuctionCurrencyDeleteRepositoryCreateTrait
{
    protected ?AuctionCurrencyDeleteRepository $auctionCurrencyDeleteRepository = null;

    protected function createAuctionCurrencyDeleteRepository(): AuctionCurrencyDeleteRepository
    {
        return $this->auctionCurrencyDeleteRepository ?: AuctionCurrencyDeleteRepository::new();
    }

    /**
     * @param AuctionCurrencyDeleteRepository $auctionCurrencyDeleteRepository
     * @return static
     * @internal
     */
    public function setAuctionCurrencyDeleteRepository(AuctionCurrencyDeleteRepository $auctionCurrencyDeleteRepository): static
    {
        $this->auctionCurrencyDeleteRepository = $auctionCurrencyDeleteRepository;
        return $this;
    }
}
