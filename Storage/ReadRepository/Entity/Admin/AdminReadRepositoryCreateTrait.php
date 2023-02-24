<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Admin;

trait AdminReadRepositoryCreateTrait
{
    protected ?AdminReadRepository $adminReadRepository = null;

    protected function createAdminReadRepository(): AdminReadRepository
    {
        return $this->adminReadRepository ?: AdminReadRepository::new();
    }

    /**
     * @param AdminReadRepository $adminReadRepository
     * @return static
     * @internal
     */
    public function setAdminReadRepository(AdminReadRepository $adminReadRepository): static
    {
        $this->adminReadRepository = $adminReadRepository;
        return $this;
    }
}
