<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettlementItem;

trait SettlementItemDeleteRepositoryCreateTrait
{
    protected ?SettlementItemDeleteRepository $settlementItemDeleteRepository = null;

    protected function createSettlementItemDeleteRepository(): SettlementItemDeleteRepository
    {
        return $this->settlementItemDeleteRepository ?: SettlementItemDeleteRepository::new();
    }

    /**
     * @param SettlementItemDeleteRepository $settlementItemDeleteRepository
     * @return static
     * @internal
     */
    public function setSettlementItemDeleteRepository(SettlementItemDeleteRepository $settlementItemDeleteRepository): static
    {
        $this->settlementItemDeleteRepository = $settlementItemDeleteRepository;
        return $this;
    }
}
