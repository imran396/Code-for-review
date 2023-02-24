<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettlementAdditional;

trait SettlementAdditionalDeleteRepositoryCreateTrait
{
    protected ?SettlementAdditionalDeleteRepository $settlementAdditionalDeleteRepository = null;

    protected function createSettlementAdditionalDeleteRepository(): SettlementAdditionalDeleteRepository
    {
        return $this->settlementAdditionalDeleteRepository ?: SettlementAdditionalDeleteRepository::new();
    }

    /**
     * @param SettlementAdditionalDeleteRepository $settlementAdditionalDeleteRepository
     * @return static
     * @internal
     */
    public function setSettlementAdditionalDeleteRepository(SettlementAdditionalDeleteRepository $settlementAdditionalDeleteRepository): static
    {
        $this->settlementAdditionalDeleteRepository = $settlementAdditionalDeleteRepository;
        return $this;
    }
}
