<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SamTaxCountryStates;

trait SamTaxCountryStatesWriteRepositoryAwareTrait
{
    protected ?SamTaxCountryStatesWriteRepository $samTaxCountryStatesWriteRepository = null;

    protected function getSamTaxCountryStatesWriteRepository(): SamTaxCountryStatesWriteRepository
    {
        if ($this->samTaxCountryStatesWriteRepository === null) {
            $this->samTaxCountryStatesWriteRepository = SamTaxCountryStatesWriteRepository::new();
        }
        return $this->samTaxCountryStatesWriteRepository;
    }

    /**
     * @param SamTaxCountryStatesWriteRepository $samTaxCountryStatesWriteRepository
     * @return static
     * @internal
     */
    public function setSamTaxCountryStatesWriteRepository(SamTaxCountryStatesWriteRepository $samTaxCountryStatesWriteRepository): static
    {
        $this->samTaxCountryStatesWriteRepository = $samTaxCountryStatesWriteRepository;
        return $this;
    }
}
