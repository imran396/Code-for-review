<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\TaxDefinitionRange;

trait TaxDefinitionRangeReadRepositoryCreateTrait
{
    protected ?TaxDefinitionRangeReadRepository $taxDefinitionRangeReadRepository = null;

    protected function createTaxDefinitionRangeReadRepository(): TaxDefinitionRangeReadRepository
    {
        return $this->taxDefinitionRangeReadRepository ?: TaxDefinitionRangeReadRepository::new();
    }

    /**
     * @param TaxDefinitionRangeReadRepository $taxDefinitionRangeReadRepository
     * @return static
     * @internal
     */
    public function setTaxDefinitionRangeReadRepository(TaxDefinitionRangeReadRepository $taxDefinitionRangeReadRepository): static
    {
        $this->taxDefinitionRangeReadRepository = $taxDefinitionRangeReadRepository;
        return $this;
    }
}
