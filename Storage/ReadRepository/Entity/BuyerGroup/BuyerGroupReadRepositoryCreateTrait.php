<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\BuyerGroup;

trait BuyerGroupReadRepositoryCreateTrait
{
    protected ?BuyerGroupReadRepository $buyerGroupReadRepository = null;

    protected function createBuyerGroupReadRepository(): BuyerGroupReadRepository
    {
        return $this->buyerGroupReadRepository ?: BuyerGroupReadRepository::new();
    }

    /**
     * @param BuyerGroupReadRepository $buyerGroupReadRepository
     * @return static
     * @internal
     */
    public function setBuyerGroupReadRepository(BuyerGroupReadRepository $buyerGroupReadRepository): static
    {
        $this->buyerGroupReadRepository = $buyerGroupReadRepository;
        return $this;
    }
}
