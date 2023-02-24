<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\AdditionalSignupConfirmation;

trait AdditionalSignupConfirmationReadRepositoryCreateTrait
{
    protected ?AdditionalSignupConfirmationReadRepository $additionalSignupConfirmationReadRepository = null;

    protected function createAdditionalSignupConfirmationReadRepository(): AdditionalSignupConfirmationReadRepository
    {
        return $this->additionalSignupConfirmationReadRepository ?: AdditionalSignupConfirmationReadRepository::new();
    }

    /**
     * @param AdditionalSignupConfirmationReadRepository $additionalSignupConfirmationReadRepository
     * @return static
     * @internal
     */
    public function setAdditionalSignupConfirmationReadRepository(AdditionalSignupConfirmationReadRepository $additionalSignupConfirmationReadRepository): static
    {
        $this->additionalSignupConfirmationReadRepository = $additionalSignupConfirmationReadRepository;
        return $this;
    }
}
