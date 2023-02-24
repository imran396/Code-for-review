<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\BuyersPremium;

trait BuyersPremiumDeleteRepositoryCreateTrait
{
    protected ?BuyersPremiumDeleteRepository $buyersPremiumDeleteRepository = null;

    protected function createBuyersPremiumDeleteRepository(): BuyersPremiumDeleteRepository
    {
        return $this->buyersPremiumDeleteRepository ?: BuyersPremiumDeleteRepository::new();
    }

    /**
     * @param BuyersPremiumDeleteRepository $buyersPremiumDeleteRepository
     * @return static
     * @internal
     */
    public function setBuyersPremiumDeleteRepository(BuyersPremiumDeleteRepository $buyersPremiumDeleteRepository): static
    {
        $this->buyersPremiumDeleteRepository = $buyersPremiumDeleteRepository;
        return $this;
    }
}
