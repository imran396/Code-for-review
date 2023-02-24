<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TermsAndConditions;

trait TermsAndConditionsWriteRepositoryAwareTrait
{
    protected ?TermsAndConditionsWriteRepository $termsAndConditionsWriteRepository = null;

    protected function getTermsAndConditionsWriteRepository(): TermsAndConditionsWriteRepository
    {
        if ($this->termsAndConditionsWriteRepository === null) {
            $this->termsAndConditionsWriteRepository = TermsAndConditionsWriteRepository::new();
        }
        return $this->termsAndConditionsWriteRepository;
    }

    /**
     * @param TermsAndConditionsWriteRepository $termsAndConditionsWriteRepository
     * @return static
     * @internal
     */
    public function setTermsAndConditionsWriteRepository(TermsAndConditionsWriteRepository $termsAndConditionsWriteRepository): static
    {
        $this->termsAndConditionsWriteRepository = $termsAndConditionsWriteRepository;
        return $this;
    }
}
