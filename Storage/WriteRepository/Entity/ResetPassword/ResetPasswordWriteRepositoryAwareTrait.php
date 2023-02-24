<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\ResetPassword;

trait ResetPasswordWriteRepositoryAwareTrait
{
    protected ?ResetPasswordWriteRepository $resetPasswordWriteRepository = null;

    protected function getResetPasswordWriteRepository(): ResetPasswordWriteRepository
    {
        if ($this->resetPasswordWriteRepository === null) {
            $this->resetPasswordWriteRepository = ResetPasswordWriteRepository::new();
        }
        return $this->resetPasswordWriteRepository;
    }

    /**
     * @param ResetPasswordWriteRepository $resetPasswordWriteRepository
     * @return static
     * @internal
     */
    public function setResetPasswordWriteRepository(ResetPasswordWriteRepository $resetPasswordWriteRepository): static
    {
        $this->resetPasswordWriteRepository = $resetPasswordWriteRepository;
        return $this;
    }
}
