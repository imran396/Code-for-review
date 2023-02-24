<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TaxSchemaTaxDefinition;

trait TaxSchemaTaxDefinitionWriteRepositoryAwareTrait
{
    protected ?TaxSchemaTaxDefinitionWriteRepository $taxSchemaTaxDefinitionWriteRepository = null;

    protected function getTaxSchemaTaxDefinitionWriteRepository(): TaxSchemaTaxDefinitionWriteRepository
    {
        if ($this->taxSchemaTaxDefinitionWriteRepository === null) {
            $this->taxSchemaTaxDefinitionWriteRepository = TaxSchemaTaxDefinitionWriteRepository::new();
        }
        return $this->taxSchemaTaxDefinitionWriteRepository;
    }

    /**
     * @param TaxSchemaTaxDefinitionWriteRepository $taxSchemaTaxDefinitionWriteRepository
     * @return static
     * @internal
     */
    public function setTaxSchemaTaxDefinitionWriteRepository(TaxSchemaTaxDefinitionWriteRepository $taxSchemaTaxDefinitionWriteRepository): static
    {
        $this->taxSchemaTaxDefinitionWriteRepository = $taxSchemaTaxDefinitionWriteRepository;
        return $this;
    }
}
