<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\BuyersPremiumRange;

trait BuyersPremiumRangeDeleteRepositoryCreateTrait
{
    protected ?BuyersPremiumRangeDeleteRepository $buyersPremiumRangeDeleteRepository = null;

    protected function createBuyersPremiumRangeDeleteRepository(): BuyersPremiumRangeDeleteRepository
    {
        return $this->buyersPremiumRangeDeleteRepository ?: BuyersPremiumRangeDeleteRepository::new();
    }

    /**
     * @param BuyersPremiumRangeDeleteRepository $buyersPremiumRangeDeleteRepository
     * @return static
     * @internal
     */
    public function setBuyersPremiumRangeDeleteRepository(BuyersPremiumRangeDeleteRepository $buyersPremiumRangeDeleteRepository): static
    {
        $this->buyersPremiumRangeDeleteRepository = $buyersPremiumRangeDeleteRepository;
        return $this;
    }
}
