<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\TaxSchemaLotCategory;

trait TaxSchemaLotCategoryReadRepositoryCreateTrait
{
    protected ?TaxSchemaLotCategoryReadRepository $taxSchemaLotCategoryReadRepository = null;

    protected function createTaxSchemaLotCategoryReadRepository(): TaxSchemaLotCategoryReadRepository
    {
        return $this->taxSchemaLotCategoryReadRepository ?: TaxSchemaLotCategoryReadRepository::new();
    }

    /**
     * @param TaxSchemaLotCategoryReadRepository $taxSchemaLotCategoryReadRepository
     * @return static
     * @internal
     */
    public function setTaxSchemaLotCategoryReadRepository(TaxSchemaLotCategoryReadRepository $taxSchemaLotCategoryReadRepository): static
    {
        $this->taxSchemaLotCategoryReadRepository = $taxSchemaLotCategoryReadRepository;
        return $this;
    }
}
