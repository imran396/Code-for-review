<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettlementCheck;

trait SettlementCheckReadRepositoryCreateTrait
{
    protected ?SettlementCheckReadRepository $settlementCheckReadRepository = null;

    protected function createSettlementCheckReadRepository(): SettlementCheckReadRepository
    {
        return $this->settlementCheckReadRepository ?: SettlementCheckReadRepository::new();
    }

    /**
     * @param SettlementCheckReadRepository $settlementCheckReadRepository
     * @return static
     * @internal
     */
    public function setSettlementCheckReadRepository(SettlementCheckReadRepository $settlementCheckReadRepository): static
    {
        $this->settlementCheckReadRepository = $settlementCheckReadRepository;
        return $this;
    }
}
