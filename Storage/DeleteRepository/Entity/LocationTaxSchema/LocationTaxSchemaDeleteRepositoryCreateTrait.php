<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LocationTaxSchema;

trait LocationTaxSchemaDeleteRepositoryCreateTrait
{
    protected ?LocationTaxSchemaDeleteRepository $locationTaxSchemaDeleteRepository = null;

    protected function createLocationTaxSchemaDeleteRepository(): LocationTaxSchemaDeleteRepository
    {
        return $this->locationTaxSchemaDeleteRepository ?: LocationTaxSchemaDeleteRepository::new();
    }

    /**
     * @param LocationTaxSchemaDeleteRepository $locationTaxSchemaDeleteRepository
     * @return static
     * @internal
     */
    public function setLocationTaxSchemaDeleteRepository(LocationTaxSchemaDeleteRepository $locationTaxSchemaDeleteRepository): static
    {
        $this->locationTaxSchemaDeleteRepository = $locationTaxSchemaDeleteRepository;
        return $this;
    }
}
