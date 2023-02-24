<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\ResetPassword;

trait ResetPasswordReadRepositoryCreateTrait
{
    protected ?ResetPasswordReadRepository $resetPasswordReadRepository = null;

    protected function createResetPasswordReadRepository(): ResetPasswordReadRepository
    {
        return $this->resetPasswordReadRepository ?: ResetPasswordReadRepository::new();
    }

    /**
     * @param ResetPasswordReadRepository $resetPasswordReadRepository
     * @return static
     * @internal
     */
    public function setResetPasswordReadRepository(ResetPasswordReadRepository $resetPasswordReadRepository): static
    {
        $this->resetPasswordReadRepository = $resetPasswordReadRepository;
        return $this;
    }
}
