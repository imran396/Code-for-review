<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\BuyerGroupUser;

trait BuyerGroupUserReadRepositoryCreateTrait
{
    protected ?BuyerGroupUserReadRepository $buyerGroupUserReadRepository = null;

    protected function createBuyerGroupUserReadRepository(): BuyerGroupUserReadRepository
    {
        return $this->buyerGroupUserReadRepository ?: BuyerGroupUserReadRepository::new();
    }

    /**
     * @param BuyerGroupUserReadRepository $buyerGroupUserReadRepository
     * @return static
     * @internal
     */
    public function setBuyerGroupUserReadRepository(BuyerGroupUserReadRepository $buyerGroupUserReadRepository): static
    {
        $this->buyerGroupUserReadRepository = $buyerGroupUserReadRepository;
        return $this;
    }
}
