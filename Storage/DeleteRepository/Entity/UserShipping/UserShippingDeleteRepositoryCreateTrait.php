<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserShipping;

trait UserShippingDeleteRepositoryCreateTrait
{
    protected ?UserShippingDeleteRepository $userShippingDeleteRepository = null;

    protected function createUserShippingDeleteRepository(): UserShippingDeleteRepository
    {
        return $this->userShippingDeleteRepository ?: UserShippingDeleteRepository::new();
    }

    /**
     * @param UserShippingDeleteRepository $userShippingDeleteRepository
     * @return static
     * @internal
     */
    public function setUserShippingDeleteRepository(UserShippingDeleteRepository $userShippingDeleteRepository): static
    {
        $this->userShippingDeleteRepository = $userShippingDeleteRepository;
        return $this;
    }
}
