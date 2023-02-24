<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Settlement;

trait SettlementWriteRepositoryAwareTrait
{
    protected ?SettlementWriteRepository $settlementWriteRepository = null;

    protected function getSettlementWriteRepository(): SettlementWriteRepository
    {
        if ($this->settlementWriteRepository === null) {
            $this->settlementWriteRepository = SettlementWriteRepository::new();
        }
        return $this->settlementWriteRepository;
    }

    /**
     * @param SettlementWriteRepository $settlementWriteRepository
     * @return static
     * @internal
     */
    public function setSettlementWriteRepository(SettlementWriteRepository $settlementWriteRepository): static
    {
        $this->settlementWriteRepository = $settlementWriteRepository;
        return $this;
    }
}
