<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettlementAdditional;

trait SettlementAdditionalWriteRepositoryAwareTrait
{
    protected ?SettlementAdditionalWriteRepository $settlementAdditionalWriteRepository = null;

    protected function getSettlementAdditionalWriteRepository(): SettlementAdditionalWriteRepository
    {
        if ($this->settlementAdditionalWriteRepository === null) {
            $this->settlementAdditionalWriteRepository = SettlementAdditionalWriteRepository::new();
        }
        return $this->settlementAdditionalWriteRepository;
    }

    /**
     * @param SettlementAdditionalWriteRepository $settlementAdditionalWriteRepository
     * @return static
     * @internal
     */
    public function setSettlementAdditionalWriteRepository(SettlementAdditionalWriteRepository $settlementAdditionalWriteRepository): static
    {
        $this->settlementAdditionalWriteRepository = $settlementAdditionalWriteRepository;
        return $this;
    }
}
