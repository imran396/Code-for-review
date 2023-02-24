<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettlementAdditional;

trait SettlementAdditionalReadRepositoryCreateTrait
{
    protected ?SettlementAdditionalReadRepository $settlementAdditionalReadRepository = null;

    protected function createSettlementAdditionalReadRepository(): SettlementAdditionalReadRepository
    {
        return $this->settlementAdditionalReadRepository ?: SettlementAdditionalReadRepository::new();
    }

    /**
     * @param SettlementAdditionalReadRepository $settlementAdditionalReadRepository
     * @return static
     * @internal
     */
    public function setSettlementAdditionalReadRepository(SettlementAdditionalReadRepository $settlementAdditionalReadRepository): static
    {
        $this->settlementAdditionalReadRepository = $settlementAdditionalReadRepository;
        return $this;
    }
}
