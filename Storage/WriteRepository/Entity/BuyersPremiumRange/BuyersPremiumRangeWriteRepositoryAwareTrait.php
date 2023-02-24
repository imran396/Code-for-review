<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\BuyersPremiumRange;

trait BuyersPremiumRangeWriteRepositoryAwareTrait
{
    protected ?BuyersPremiumRangeWriteRepository $buyersPremiumRangeWriteRepository = null;

    protected function getBuyersPremiumRangeWriteRepository(): BuyersPremiumRangeWriteRepository
    {
        if ($this->buyersPremiumRangeWriteRepository === null) {
            $this->buyersPremiumRangeWriteRepository = BuyersPremiumRangeWriteRepository::new();
        }
        return $this->buyersPremiumRangeWriteRepository;
    }

    /**
     * @param BuyersPremiumRangeWriteRepository $buyersPremiumRangeWriteRepository
     * @return static
     * @internal
     */
    public function setBuyersPremiumRangeWriteRepository(BuyersPremiumRangeWriteRepository $buyersPremiumRangeWriteRepository): static
    {
        $this->buyersPremiumRangeWriteRepository = $buyersPremiumRangeWriteRepository;
        return $this;
    }
}
