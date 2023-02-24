<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TaxDefinition;

trait TaxDefinitionWriteRepositoryAwareTrait
{
    protected ?TaxDefinitionWriteRepository $taxDefinitionWriteRepository = null;

    protected function getTaxDefinitionWriteRepository(): TaxDefinitionWriteRepository
    {
        if ($this->taxDefinitionWriteRepository === null) {
            $this->taxDefinitionWriteRepository = TaxDefinitionWriteRepository::new();
        }
        return $this->taxDefinitionWriteRepository;
    }

    /**
     * @param TaxDefinitionWriteRepository $taxDefinitionWriteRepository
     * @return static
     * @internal
     */
    public function setTaxDefinitionWriteRepository(TaxDefinitionWriteRepository $taxDefinitionWriteRepository): static
    {
        $this->taxDefinitionWriteRepository = $taxDefinitionWriteRepository;
        return $this;
    }
}
