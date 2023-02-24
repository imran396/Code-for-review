<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SamTaxCountryStates;

trait SamTaxCountryStatesReadRepositoryCreateTrait
{
    protected ?SamTaxCountryStatesReadRepository $samTaxCountryStatesReadRepository = null;

    protected function createSamTaxCountryStatesReadRepository(): SamTaxCountryStatesReadRepository
    {
        return $this->samTaxCountryStatesReadRepository ?: SamTaxCountryStatesReadRepository::new();
    }

    /**
     * @param SamTaxCountryStatesReadRepository $samTaxCountryStatesReadRepository
     * @return static
     * @internal
     */
    public function setSamTaxCountryStatesReadRepository(SamTaxCountryStatesReadRepository $samTaxCountryStatesReadRepository): static
    {
        $this->samTaxCountryStatesReadRepository = $samTaxCountryStatesReadRepository;
        return $this;
    }
}
