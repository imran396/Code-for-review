<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\TermsAndConditions;

trait TermsAndConditionsReadRepositoryCreateTrait
{
    protected ?TermsAndConditionsReadRepository $termsAndConditionsReadRepository = null;

    protected function createTermsAndConditionsReadRepository(): TermsAndConditionsReadRepository
    {
        return $this->termsAndConditionsReadRepository ?: TermsAndConditionsReadRepository::new();
    }

    /**
     * @param TermsAndConditionsReadRepository $termsAndConditionsReadRepository
     * @return static
     * @internal
     */
    public function setTermsAndConditionsReadRepository(TermsAndConditionsReadRepository $termsAndConditionsReadRepository): static
    {
        $this->termsAndConditionsReadRepository = $termsAndConditionsReadRepository;
        return $this;
    }
}
