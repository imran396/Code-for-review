<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\BuyerGroup;

trait BuyerGroupDeleteRepositoryCreateTrait
{
    protected ?BuyerGroupDeleteRepository $buyerGroupDeleteRepository = null;

    protected function createBuyerGroupDeleteRepository(): BuyerGroupDeleteRepository
    {
        return $this->buyerGroupDeleteRepository ?: BuyerGroupDeleteRepository::new();
    }

    /**
     * @param BuyerGroupDeleteRepository $buyerGroupDeleteRepository
     * @return static
     * @internal
     */
    public function setBuyerGroupDeleteRepository(BuyerGroupDeleteRepository $buyerGroupDeleteRepository): static
    {
        $this->buyerGroupDeleteRepository = $buyerGroupDeleteRepository;
        return $this;
    }
}
