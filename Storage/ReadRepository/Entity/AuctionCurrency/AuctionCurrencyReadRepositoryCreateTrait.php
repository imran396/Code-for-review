<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AuctionCurrency;

trait AuctionCurrencyReadRepositoryCreateTrait
{
    protected ?AuctionCurrencyReadRepository $auctionCurrencyReadRepository = null;

    protected function createAuctionCurrencyReadRepository(): AuctionCurrencyReadRepository
    {
        return $this->auctionCurrencyReadRepository ?: AuctionCurrencyReadRepository::new();
    }

    /**
     * @param AuctionCurrencyReadRepository $auctionCurrencyReadRepository
     * @return static
     * @internal
     */
    public function setAuctionCurrencyReadRepository(AuctionCurrencyReadRepository $auctionCurrencyReadRepository): static
    {
        $this->auctionCurrencyReadRepository = $auctionCurrencyReadRepository;
        return $this;
    }
}
