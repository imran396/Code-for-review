<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\BuyerGroup;

trait BuyerGroupWriteRepositoryAwareTrait
{
    protected ?BuyerGroupWriteRepository $buyerGroupWriteRepository = null;

    protected function getBuyerGroupWriteRepository(): BuyerGroupWriteRepository
    {
        if ($this->buyerGroupWriteRepository === null) {
            $this->buyerGroupWriteRepository = BuyerGroupWriteRepository::new();
        }
        return $this->buyerGroupWriteRepository;
    }

    /**
     * @param BuyerGroupWriteRepository $buyerGroupWriteRepository
     * @return static
     * @internal
     */
    public function setBuyerGroupWriteRepository(BuyerGroupWriteRepository $buyerGroupWriteRepository): static
    {
        $this->buyerGroupWriteRepository = $buyerGroupWriteRepository;
        return $this;
    }
}
