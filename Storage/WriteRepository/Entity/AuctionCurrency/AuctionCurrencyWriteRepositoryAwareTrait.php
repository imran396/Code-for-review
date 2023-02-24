<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AuctionCurrency;

trait AuctionCurrencyWriteRepositoryAwareTrait
{
    protected ?AuctionCurrencyWriteRepository $auctionCurrencyWriteRepository = null;

    protected function getAuctionCurrencyWriteRepository(): AuctionCurrencyWriteRepository
    {
        if ($this->auctionCurrencyWriteRepository === null) {
            $this->auctionCurrencyWriteRepository = AuctionCurrencyWriteRepository::new();
        }
        return $this->auctionCurrencyWriteRepository;
    }

    /**
     * @param AuctionCurrencyWriteRepository $auctionCurrencyWriteRepository
     * @return static
     * @internal
     */
    public function setAuctionCurrencyWriteRepository(AuctionCurrencyWriteRepository $auctionCurrencyWriteRepository): static
    {
        $this->auctionCurrencyWriteRepository = $auctionCurrencyWriteRepository;
        return $this;
    }
}
