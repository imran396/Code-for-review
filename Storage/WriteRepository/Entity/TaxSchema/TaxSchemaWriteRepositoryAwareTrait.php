<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TaxSchema;

trait TaxSchemaWriteRepositoryAwareTrait
{
    protected ?TaxSchemaWriteRepository $taxSchemaWriteRepository = null;

    protected function getTaxSchemaWriteRepository(): TaxSchemaWriteRepository
    {
        if ($this->taxSchemaWriteRepository === null) {
            $this->taxSchemaWriteRepository = TaxSchemaWriteRepository::new();
        }
        return $this->taxSchemaWriteRepository;
    }

    /**
     * @param TaxSchemaWriteRepository $taxSchemaWriteRepository
     * @return static
     * @internal
     */
    public function setTaxSchemaWriteRepository(TaxSchemaWriteRepository $taxSchemaWriteRepository): static
    {
        $this->taxSchemaWriteRepository = $taxSchemaWriteRepository;
        return $this;
    }
}
