<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\ResetPassword;

trait ResetPasswordDeleteRepositoryCreateTrait
{
    protected ?ResetPasswordDeleteRepository $resetPasswordDeleteRepository = null;

    protected function createResetPasswordDeleteRepository(): ResetPasswordDeleteRepository
    {
        return $this->resetPasswordDeleteRepository ?: ResetPasswordDeleteRepository::new();
    }

    /**
     * @param ResetPasswordDeleteRepository $resetPasswordDeleteRepository
     * @return static
     * @internal
     */
    public function setResetPasswordDeleteRepository(ResetPasswordDeleteRepository $resetPasswordDeleteRepository): static
    {
        $this->resetPasswordDeleteRepository = $resetPasswordDeleteRepository;
        return $this;
    }
}
