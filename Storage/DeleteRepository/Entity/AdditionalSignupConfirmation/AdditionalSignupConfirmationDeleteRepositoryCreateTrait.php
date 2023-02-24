<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\AdditionalSignupConfirmation;

trait AdditionalSignupConfirmationDeleteRepositoryCreateTrait
{
    protected ?AdditionalSignupConfirmationDeleteRepository $additionalSignupConfirmationDeleteRepository = null;

    protected function createAdditionalSignupConfirmationDeleteRepository(): AdditionalSignupConfirmationDeleteRepository
    {
        return $this->additionalSignupConfirmationDeleteRepository ?: AdditionalSignupConfirmationDeleteRepository::new();
    }

    /**
     * @param AdditionalSignupConfirmationDeleteRepository $additionalSignupConfirmationDeleteRepository
     * @return static
     * @internal
     */
    public function setAdditionalSignupConfirmationDeleteRepository(AdditionalSignupConfirmationDeleteRepository $additionalSignupConfirmationDeleteRepository): static
    {
        $this->additionalSignupConfirmationDeleteRepository = $additionalSignupConfirmationDeleteRepository;
        return $this;
    }
}
