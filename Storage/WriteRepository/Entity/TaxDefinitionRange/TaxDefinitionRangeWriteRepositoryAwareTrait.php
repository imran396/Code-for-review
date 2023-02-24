<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TaxDefinitionRange;

trait TaxDefinitionRangeWriteRepositoryAwareTrait
{
    protected ?TaxDefinitionRangeWriteRepository $taxDefinitionRangeWriteRepository = null;

    protected function getTaxDefinitionRangeWriteRepository(): TaxDefinitionRangeWriteRepository
    {
        if ($this->taxDefinitionRangeWriteRepository === null) {
            $this->taxDefinitionRangeWriteRepository = TaxDefinitionRangeWriteRepository::new();
        }
        return $this->taxDefinitionRangeWriteRepository;
    }

    /**
     * @param TaxDefinitionRangeWriteRepository $taxDefinitionRangeWriteRepository
     * @return static
     * @internal
     */
    public function setTaxDefinitionRangeWriteRepository(TaxDefinitionRangeWriteRepository $taxDefinitionRangeWriteRepository): static
    {
        $this->taxDefinitionRangeWriteRepository = $taxDefinitionRangeWriteRepository;
        return $this;
    }
}
