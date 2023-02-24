<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\AdditionalSignupConfirmation;

trait AdditionalSignupConfirmationWriteRepositoryAwareTrait
{
    protected ?AdditionalSignupConfirmationWriteRepository $additionalSignupConfirmationWriteRepository = null;

    protected function getAdditionalSignupConfirmationWriteRepository(): AdditionalSignupConfirmationWriteRepository
    {
        if ($this->additionalSignupConfirmationWriteRepository === null) {
            $this->additionalSignupConfirmationWriteRepository = AdditionalSignupConfirmationWriteRepository::new();
        }
        return $this->additionalSignupConfirmationWriteRepository;
    }

    /**
     * @param AdditionalSignupConfirmationWriteRepository $additionalSignupConfirmationWriteRepository
     * @return static
     * @internal
     */
    public function setAdditionalSignupConfirmationWriteRepository(AdditionalSignupConfirmationWriteRepository $additionalSignupConfirmationWriteRepository): static
    {
        $this->additionalSignupConfirmationWriteRepository = $additionalSignupConfirmationWriteRepository;
        return $this;
    }
}
