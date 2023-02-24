<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\TaxSchemaTaxDefinition;

trait TaxSchemaTaxDefinitionReadRepositoryCreateTrait
{
    protected ?TaxSchemaTaxDefinitionReadRepository $taxSchemaTaxDefinitionReadRepository = null;

    protected function createTaxSchemaTaxDefinitionReadRepository(): TaxSchemaTaxDefinitionReadRepository
    {
        return $this->taxSchemaTaxDefinitionReadRepository ?: TaxSchemaTaxDefinitionReadRepository::new();
    }

    /**
     * @param TaxSchemaTaxDefinitionReadRepository $taxSchemaTaxDefinitionReadRepository
     * @return static
     * @internal
     */
    public function setTaxSchemaTaxDefinitionReadRepository(TaxSchemaTaxDefinitionReadRepository $taxSchemaTaxDefinitionReadRepository): static
    {
        $this->taxSchemaTaxDefinitionReadRepository = $taxSchemaTaxDefinitionReadRepository;
        return $this;
    }
}
