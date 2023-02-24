<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Admin;

trait AdminDeleteRepositoryCreateTrait
{
    protected ?AdminDeleteRepository $adminDeleteRepository = null;

    protected function createAdminDeleteRepository(): AdminDeleteRepository
    {
        return $this->adminDeleteRepository ?: AdminDeleteRepository::new();
    }

    /**
     * @param AdminDeleteRepository $adminDeleteRepository
     * @return static
     * @internal
     */
    public function setAdminDeleteRepository(AdminDeleteRepository $adminDeleteRepository): static
    {
        $this->adminDeleteRepository = $adminDeleteRepository;
        return $this;
    }
}
