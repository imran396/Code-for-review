<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\BuyersPremium;

trait BuyersPremiumReadRepositoryCreateTrait
{
    protected ?BuyersPremiumReadRepository $buyersPremiumReadRepository = null;

    protected function createBuyersPremiumReadRepository(): BuyersPremiumReadRepository
    {
        return $this->buyersPremiumReadRepository ?: BuyersPremiumReadRepository::new();
    }

    /**
     * @param BuyersPremiumReadRepository $buyersPremiumReadRepository
     * @return static
     * @internal
     */
    public function setBuyersPremiumReadRepository(BuyersPremiumReadRepository $buyersPremiumReadRepository): static
    {
        $this->buyersPremiumReadRepository = $buyersPremiumReadRepository;
        return $this;
    }
}
