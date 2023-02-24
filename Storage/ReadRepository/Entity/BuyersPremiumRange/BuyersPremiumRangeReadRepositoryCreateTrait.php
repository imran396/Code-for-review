<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\BuyersPremiumRange;

trait BuyersPremiumRangeReadRepositoryCreateTrait
{
    protected ?BuyersPremiumRangeReadRepository $buyersPremiumRangeReadRepository = null;

    protected function createBuyersPremiumRangeReadRepository(): BuyersPremiumRangeReadRepository
    {
        return $this->buyersPremiumRangeReadRepository ?: BuyersPremiumRangeReadRepository::new();
    }

    /**
     * @param BuyersPremiumRangeReadRepository $buyersPremiumRangeReadRepository
     * @return static
     * @internal
     */
    public function setBuyersPremiumRangeReadRepository(BuyersPremiumRangeReadRepository $buyersPremiumRangeReadRepository): static
    {
        $this->buyersPremiumRangeReadRepository = $buyersPremiumRangeReadRepository;
        return $this;
    }
}
