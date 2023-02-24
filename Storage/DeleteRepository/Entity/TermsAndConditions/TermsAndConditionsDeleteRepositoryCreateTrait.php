<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\TermsAndConditions;

trait TermsAndConditionsDeleteRepositoryCreateTrait
{
    protected ?TermsAndConditionsDeleteRepository $termsAndConditionsDeleteRepository = null;

    protected function createTermsAndConditionsDeleteRepository(): TermsAndConditionsDeleteRepository
    {
        return $this->termsAndConditionsDeleteRepository ?: TermsAndConditionsDeleteRepository::new();
    }

    /**
     * @param TermsAndConditionsDeleteRepository $termsAndConditionsDeleteRepository
     * @return static
     * @internal
     */
    public function setTermsAndConditionsDeleteRepository(TermsAndConditionsDeleteRepository $termsAndConditionsDeleteRepository): static
    {
        $this->termsAndConditionsDeleteRepository = $termsAndConditionsDeleteRepository;
        return $this;
    }
}
