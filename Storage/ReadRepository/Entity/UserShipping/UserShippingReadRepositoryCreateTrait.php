<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserShipping;

trait UserShippingReadRepositoryCreateTrait
{
    protected ?UserShippingReadRepository $userShippingReadRepository = null;

    protected function createUserShippingReadRepository(): UserShippingReadRepository
    {
        return $this->userShippingReadRepository ?: UserShippingReadRepository::new();
    }

    /**
     * @param UserShippingReadRepository $userShippingReadRepository
     * @return static
     * @internal
     */
    public function setUserShippingReadRepository(UserShippingReadRepository $userShippingReadRepository): static
    {
        $this->userShippingReadRepository = $userShippingReadRepository;
        return $this;
    }
}
