<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\TaxDefinitionRange;

trait TaxDefinitionRangeDeleteRepositoryCreateTrait
{
    protected ?TaxDefinitionRangeDeleteRepository $taxDefinitionRangeDeleteRepository = null;

    protected function createTaxDefinitionRangeDeleteRepository(): TaxDefinitionRangeDeleteRepository
    {
        return $this->taxDefinitionRangeDeleteRepository ?: TaxDefinitionRangeDeleteRepository::new();
    }

    /**
     * @param TaxDefinitionRangeDeleteRepository $taxDefinitionRangeDeleteRepository
     * @return static
     * @internal
     */
    public function setTaxDefinitionRangeDeleteRepository(TaxDefinitionRangeDeleteRepository $taxDefinitionRangeDeleteRepository): static
    {
        $this->taxDefinitionRangeDeleteRepository = $taxDefinitionRangeDeleteRepository;
        return $this;
    }
}
