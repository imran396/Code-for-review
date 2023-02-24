<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserShipping;

trait UserShippingWriteRepositoryAwareTrait
{
    protected ?UserShippingWriteRepository $userShippingWriteRepository = null;

    protected function getUserShippingWriteRepository(): UserShippingWriteRepository
    {
        if ($this->userShippingWriteRepository === null) {
            $this->userShippingWriteRepository = UserShippingWriteRepository::new();
        }
        return $this->userShippingWriteRepository;
    }

    /**
     * @param UserShippingWriteRepository $userShippingWriteRepository
     * @return static
     * @internal
     */
    public function setUserShippingWriteRepository(UserShippingWriteRepository $userShippingWriteRepository): static
    {
        $this->userShippingWriteRepository = $userShippingWriteRepository;
        return $this;
    }
}
