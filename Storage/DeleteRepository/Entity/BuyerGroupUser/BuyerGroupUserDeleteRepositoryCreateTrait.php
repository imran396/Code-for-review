<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\BuyerGroupUser;

trait BuyerGroupUserDeleteRepositoryCreateTrait
{
    protected ?BuyerGroupUserDeleteRepository $buyerGroupUserDeleteRepository = null;

    protected function createBuyerGroupUserDeleteRepository(): BuyerGroupUserDeleteRepository
    {
        return $this->buyerGroupUserDeleteRepository ?: BuyerGroupUserDeleteRepository::new();
    }

    /**
     * @param BuyerGroupUserDeleteRepository $buyerGroupUserDeleteRepository
     * @return static
     * @internal
     */
    public function setBuyerGroupUserDeleteRepository(BuyerGroupUserDeleteRepository $buyerGroupUserDeleteRepository): static
    {
        $this->buyerGroupUserDeleteRepository = $buyerGroupUserDeleteRepository;
        return $this;
    }
}
