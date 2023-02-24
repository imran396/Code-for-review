<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LocationTaxSchema;

trait LocationTaxSchemaReadRepositoryCreateTrait
{
    protected ?LocationTaxSchemaReadRepository $locationTaxSchemaReadRepository = null;

    protected function createLocationTaxSchemaReadRepository(): LocationTaxSchemaReadRepository
    {
        return $this->locationTaxSchemaReadRepository ?: LocationTaxSchemaReadRepository::new();
    }

    /**
     * @param LocationTaxSchemaReadRepository $locationTaxSchemaReadRepository
     * @return static
     * @internal
     */
    public function setLocationTaxSchemaReadRepository(LocationTaxSchemaReadRepository $locationTaxSchemaReadRepository): static
    {
        $this->locationTaxSchemaReadRepository = $locationTaxSchemaReadRepository;
        return $this;
    }
}
