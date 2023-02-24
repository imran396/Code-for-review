<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotItemCustData;

trait LotItemCustDataWriteRepositoryAwareTrait
{
    protected ?LotItemCustDataWriteRepository $lotItemCustDataWriteRepository = null;

    protected function getLotItemCustDataWriteRepository(): LotItemCustDataWriteRepository
    {
        if ($this->lotItemCustDataWriteRepository === null) {
            $this->lotItemCustDataWriteRepository = LotItemCustDataWriteRepository::new();
        }
        return $this->lotItemCustDataWriteRepository;
    }

    /**
     * @param LotItemCustDataWriteRepository $lotItemCustDataWriteRepository
     * @return static
     * @internal
     */
    public function setLotItemCustDataWriteRepository(LotItemCustDataWriteRepository $lotItemCustDataWriteRepository): static
    {
        $this->lotItemCustDataWriteRepository = $lotItemCustDataWriteRepository;
        return $this;
    }
}
