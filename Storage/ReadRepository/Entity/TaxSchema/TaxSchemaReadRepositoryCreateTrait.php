<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\TaxSchema;

trait TaxSchemaReadRepositoryCreateTrait
{
    protected ?TaxSchemaReadRepository $taxSchemaReadRepository = null;

    protected function createTaxSchemaReadRepository(): TaxSchemaReadRepository
    {
        return $this->taxSchemaReadRepository ?: TaxSchemaReadRepository::new();
    }

    /**
     * @param TaxSchemaReadRepository $taxSchemaReadRepository
     * @return static
     * @internal
     */
    public function setTaxSchemaReadRepository(TaxSchemaReadRepository $taxSchemaReadRepository): static
    {
        $this->taxSchemaReadRepository = $taxSchemaReadRepository;
        return $this;
    }
}
