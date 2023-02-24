<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TaxSchemaLotCategory;

trait TaxSchemaLotCategoryWriteRepositoryAwareTrait
{
    protected ?TaxSchemaLotCategoryWriteRepository $taxSchemaLotCategoryWriteRepository = null;

    protected function getTaxSchemaLotCategoryWriteRepository(): TaxSchemaLotCategoryWriteRepository
    {
        if ($this->taxSchemaLotCategoryWriteRepository === null) {
            $this->taxSchemaLotCategoryWriteRepository = TaxSchemaLotCategoryWriteRepository::new();
        }
        return $this->taxSchemaLotCategoryWriteRepository;
    }

    /**
     * @param TaxSchemaLotCategoryWriteRepository $taxSchemaLotCategoryWriteRepository
     * @return static
     * @internal
     */
    public function setTaxSchemaLotCategoryWriteRepository(TaxSchemaLotCategoryWriteRepository $taxSchemaLotCategoryWriteRepository): static
    {
        $this->taxSchemaLotCategoryWriteRepository = $taxSchemaLotCategoryWriteRepository;
        return $this;
    }
}
