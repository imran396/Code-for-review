<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettlementCheck;

trait SettlementCheckDeleteRepositoryCreateTrait
{
    protected ?SettlementCheckDeleteRepository $settlementCheckDeleteRepository = null;

    protected function createSettlementCheckDeleteRepository(): SettlementCheckDeleteRepository
    {
        return $this->settlementCheckDeleteRepository ?: SettlementCheckDeleteRepository::new();
    }

    /**
     * @param SettlementCheckDeleteRepository $settlementCheckDeleteRepository
     * @return static
     * @internal
     */
    public function setSettlementCheckDeleteRepository(SettlementCheckDeleteRepository $settlementCheckDeleteRepository): static
    {
        $this->settlementCheckDeleteRepository = $settlementCheckDeleteRepository;
        return $this;
    }
}
