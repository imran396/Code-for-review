<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Settlement;

trait SettlementReadRepositoryCreateTrait
{
    protected ?SettlementReadRepository $settlementReadRepository = null;

    protected function createSettlementReadRepository(): SettlementReadRepository
    {
        return $this->settlementReadRepository ?: SettlementReadRepository::new();
    }

    /**
     * @param SettlementReadRepository $settlementReadRepository
     * @return static
     * @internal
     */
    public function setSettlementReadRepository(SettlementReadRepository $settlementReadRepository): static
    {
        $this->settlementReadRepository = $settlementReadRepository;
        return $this;
    }
}
