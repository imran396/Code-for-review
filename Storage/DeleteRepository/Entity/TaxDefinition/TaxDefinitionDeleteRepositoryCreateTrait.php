<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\TaxDefinition;

trait TaxDefinitionDeleteRepositoryCreateTrait
{
    protected ?TaxDefinitionDeleteRepository $taxDefinitionDeleteRepository = null;

    protected function createTaxDefinitionDeleteRepository(): TaxDefinitionDeleteRepository
    {
        return $this->taxDefinitionDeleteRepository ?: TaxDefinitionDeleteRepository::new();
    }

    /**
     * @param TaxDefinitionDeleteRepository $taxDefinitionDeleteRepository
     * @return static
     * @internal
     */
    public function setTaxDefinitionDeleteRepository(TaxDefinitionDeleteRepository $taxDefinitionDeleteRepository): static
    {
        $this->taxDefinitionDeleteRepository = $taxDefinitionDeleteRepository;
        return $this;
    }
}
