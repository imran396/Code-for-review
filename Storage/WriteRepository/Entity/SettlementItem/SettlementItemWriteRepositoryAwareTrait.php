<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettlementItem;

trait SettlementItemWriteRepositoryAwareTrait
{
    protected ?SettlementItemWriteRepository $settlementItemWriteRepository = null;

    protected function getSettlementItemWriteRepository(): SettlementItemWriteRepository
    {
        if ($this->settlementItemWriteRepository === null) {
            $this->settlementItemWriteRepository = SettlementItemWriteRepository::new();
        }
        return $this->settlementItemWriteRepository;
    }

    /**
     * @param SettlementItemWriteRepository $settlementItemWriteRepository
     * @return static
     * @internal
     */
    public function setSettlementItemWriteRepository(SettlementItemWriteRepository $settlementItemWriteRepository): static
    {
        $this->settlementItemWriteRepository = $settlementItemWriteRepository;
        return $this;
    }
}
