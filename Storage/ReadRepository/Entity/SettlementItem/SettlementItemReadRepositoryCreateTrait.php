<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettlementItem;

trait SettlementItemReadRepositoryCreateTrait
{
    protected ?SettlementItemReadRepository $settlementItemReadRepository = null;

    protected function createSettlementItemReadRepository(): SettlementItemReadRepository
    {
        return $this->settlementItemReadRepository ?: SettlementItemReadRepository::new();
    }

    /**
     * @param SettlementItemReadRepository $settlementItemReadRepository
     * @return static
     * @internal
     */
    public function setSettlementItemReadRepository(SettlementItemReadRepository $settlementItemReadRepository): static
    {
        $this->settlementItemReadRepository = $settlementItemReadRepository;
        return $this;
    }
}
