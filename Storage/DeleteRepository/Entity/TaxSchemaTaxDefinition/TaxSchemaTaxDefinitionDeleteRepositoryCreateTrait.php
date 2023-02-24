<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\TaxSchemaTaxDefinition;

trait TaxSchemaTaxDefinitionDeleteRepositoryCreateTrait
{
    protected ?TaxSchemaTaxDefinitionDeleteRepository $taxSchemaTaxDefinitionDeleteRepository = null;

    protected function createTaxSchemaTaxDefinitionDeleteRepository(): TaxSchemaTaxDefinitionDeleteRepository
    {
        return $this->taxSchemaTaxDefinitionDeleteRepository ?: TaxSchemaTaxDefinitionDeleteRepository::new();
    }

    /**
     * @param TaxSchemaTaxDefinitionDeleteRepository $taxSchemaTaxDefinitionDeleteRepository
     * @return static
     * @internal
     */
    public function setTaxSchemaTaxDefinitionDeleteRepository(TaxSchemaTaxDefinitionDeleteRepository $taxSchemaTaxDefinitionDeleteRepository): static
    {
        $this->taxSchemaTaxDefinitionDeleteRepository = $taxSchemaTaxDefinitionDeleteRepository;
        return $this;
    }
}
