<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LocationTaxSchema;

trait LocationTaxSchemaWriteRepositoryAwareTrait
{
    protected ?LocationTaxSchemaWriteRepository $locationTaxSchemaWriteRepository = null;

    protected function getLocationTaxSchemaWriteRepository(): LocationTaxSchemaWriteRepository
    {
        if ($this->locationTaxSchemaWriteRepository === null) {
            $this->locationTaxSchemaWriteRepository = LocationTaxSchemaWriteRepository::new();
        }
        return $this->locationTaxSchemaWriteRepository;
    }

    /**
     * @param LocationTaxSchemaWriteRepository $locationTaxSchemaWriteRepository
     * @return static
     * @internal
     */
    public function setLocationTaxSchemaWriteRepository(LocationTaxSchemaWriteRepository $locationTaxSchemaWriteRepository): static
    {
        $this->locationTaxSchemaWriteRepository = $locationTaxSchemaWriteRepository;
        return $this;
    }
}
