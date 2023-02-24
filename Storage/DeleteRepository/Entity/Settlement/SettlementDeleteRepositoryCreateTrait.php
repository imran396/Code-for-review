<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Settlement;

trait SettlementDeleteRepositoryCreateTrait
{
    protected ?SettlementDeleteRepository $settlementDeleteRepository = null;

    protected function createSettlementDeleteRepository(): SettlementDeleteRepository
    {
        return $this->settlementDeleteRepository ?: SettlementDeleteRepository::new();
    }

    /**
     * @param SettlementDeleteRepository $settlementDeleteRepository
     * @return static
     * @internal
     */
    public function setSettlementDeleteRepository(SettlementDeleteRepository $settlementDeleteRepository): static
    {
        $this->settlementDeleteRepository = $settlementDeleteRepository;
        return $this;
    }
}
