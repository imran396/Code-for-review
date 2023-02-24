<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\TaxSchemaLotCategory;

trait TaxSchemaLotCategoryDeleteRepositoryCreateTrait
{
    protected ?TaxSchemaLotCategoryDeleteRepository $taxSchemaLotCategoryDeleteRepository = null;

    protected function createTaxSchemaLotCategoryDeleteRepository(): TaxSchemaLotCategoryDeleteRepository
    {
        return $this->taxSchemaLotCategoryDeleteRepository ?: TaxSchemaLotCategoryDeleteRepository::new();
    }

    /**
     * @param TaxSchemaLotCategoryDeleteRepository $taxSchemaLotCategoryDeleteRepository
     * @return static
     * @internal
     */
    public function setTaxSchemaLotCategoryDeleteRepository(TaxSchemaLotCategoryDeleteRepository $taxSchemaLotCategoryDeleteRepository): static
    {
        $this->taxSchemaLotCategoryDeleteRepository = $taxSchemaLotCategoryDeleteRepository;
        return $this;
    }
}
