<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettlementCheck;

trait SettlementCheckWriteRepositoryAwareTrait
{
    protected ?SettlementCheckWriteRepository $settlementCheckWriteRepository = null;

    protected function getSettlementCheckWriteRepository(): SettlementCheckWriteRepository
    {
        if ($this->settlementCheckWriteRepository === null) {
            $this->settlementCheckWriteRepository = SettlementCheckWriteRepository::new();
        }
        return $this->settlementCheckWriteRepository;
    }

    /**
     * @param SettlementCheckWriteRepository $settlementCheckWriteRepository
     * @return static
     * @internal
     */
    public function setSettlementCheckWriteRepository(SettlementCheckWriteRepository $settlementCheckWriteRepository): static
    {
        $this->settlementCheckWriteRepository = $settlementCheckWriteRepository;
        return $this;
    }
}
