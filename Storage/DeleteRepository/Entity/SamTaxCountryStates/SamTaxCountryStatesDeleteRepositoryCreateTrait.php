<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SamTaxCountryStates;

trait SamTaxCountryStatesDeleteRepositoryCreateTrait
{
    protected ?SamTaxCountryStatesDeleteRepository $samTaxCountryStatesDeleteRepository = null;

    protected function createSamTaxCountryStatesDeleteRepository(): SamTaxCountryStatesDeleteRepository
    {
        return $this->samTaxCountryStatesDeleteRepository ?: SamTaxCountryStatesDeleteRepository::new();
    }

    /**
     * @param SamTaxCountryStatesDeleteRepository $samTaxCountryStatesDeleteRepository
     * @return static
     * @internal
     */
    public function setSamTaxCountryStatesDeleteRepository(SamTaxCountryStatesDeleteRepository $samTaxCountryStatesDeleteRepository): static
    {
        $this->samTaxCountryStatesDeleteRepository = $samTaxCountryStatesDeleteRepository;
        return $this;
    }
}
