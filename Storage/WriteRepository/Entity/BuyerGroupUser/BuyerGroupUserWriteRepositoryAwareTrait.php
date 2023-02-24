<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\BuyerGroupUser;

trait BuyerGroupUserWriteRepositoryAwareTrait
{
    protected ?BuyerGroupUserWriteRepository $buyerGroupUserWriteRepository = null;

    protected function getBuyerGroupUserWriteRepository(): BuyerGroupUserWriteRepository
    {
        if ($this->buyerGroupUserWriteRepository === null) {
            $this->buyerGroupUserWriteRepository = BuyerGroupUserWriteRepository::new();
        }
        return $this->buyerGroupUserWriteRepository;
    }

    /**
     * @param BuyerGroupUserWriteRepository $buyerGroupUserWriteRepository
     * @return static
     * @internal
     */
    public function setBuyerGroupUserWriteRepository(BuyerGroupUserWriteRepository $buyerGroupUserWriteRepository): static
    {
        $this->buyerGroupUserWriteRepository = $buyerGroupUserWriteRepository;
        return $this;
    }
}
