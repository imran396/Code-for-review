<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\TaxDefinition;

trait TaxDefinitionReadRepositoryCreateTrait
{
    protected ?TaxDefinitionReadRepository $taxDefinitionReadRepository = null;

    protected function createTaxDefinitionReadRepository(): TaxDefinitionReadRepository
    {
        return $this->taxDefinitionReadRepository ?: TaxDefinitionReadRepository::new();
    }

    /**
     * @param TaxDefinitionReadRepository $taxDefinitionReadRepository
     * @return static
     * @internal
     */
    public function setTaxDefinitionReadRepository(TaxDefinitionReadRepository $taxDefinitionReadRepository): static
    {
        $this->taxDefinitionReadRepository = $taxDefinitionReadRepository;
        return $this;
    }
}
