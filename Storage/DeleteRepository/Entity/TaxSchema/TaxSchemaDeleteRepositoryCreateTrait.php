<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\TaxSchema;

trait TaxSchemaDeleteRepositoryCreateTrait
{
    protected ?TaxSchemaDeleteRepository $taxSchemaDeleteRepository = null;

    protected function createTaxSchemaDeleteRepository(): TaxSchemaDeleteRepository
    {
        return $this->taxSchemaDeleteRepository ?: TaxSchemaDeleteRepository::new();
    }

    /**
     * @param TaxSchemaDeleteRepository $taxSchemaDeleteRepository
     * @return static
     * @internal
     */
    public function setTaxSchemaDeleteRepository(TaxSchemaDeleteRepository $taxSchemaDeleteRepository): static
    {
        $this->taxSchemaDeleteRepository = $taxSchemaDeleteRepository;
        return $this;
    }
}
