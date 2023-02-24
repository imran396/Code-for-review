<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\BuyersPremium;

trait BuyersPremiumWriteRepositoryAwareTrait
{
    protected ?BuyersPremiumWriteRepository $buyersPremiumWriteRepository = null;

    protected function getBuyersPremiumWriteRepository(): BuyersPremiumWriteRepository
    {
        if ($this->buyersPremiumWriteRepository === null) {
            $this->buyersPremiumWriteRepository = BuyersPremiumWriteRepository::new();
        }
        return $this->buyersPremiumWriteRepository;
    }

    /**
     * @param BuyersPremiumWriteRepository $buyersPremiumWriteRepository
     * @return static
     * @internal
     */
    public function setBuyersPremiumWriteRepository(BuyersPremiumWriteRepository $buyersPremiumWriteRepository): static
    {
        $this->buyersPremiumWriteRepository = $buyersPremiumWriteRepository;
        return $this;
    }
}
