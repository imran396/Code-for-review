<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Admin;

trait AdminWriteRepositoryAwareTrait
{
    protected ?AdminWriteRepository $adminWriteRepository = null;

    protected function getAdminWriteRepository(): AdminWriteRepository
    {
        if ($this->adminWriteRepository === null) {
            $this->adminWriteRepository = AdminWriteRepository::new();
        }
        return $this->adminWriteRepository;
    }

    /**
     * @param AdminWriteRepository $adminWriteRepository
     * @return static
     * @internal
     */
    public function setAdminWriteRepository(AdminWriteRepository $adminWriteRepository): static
    {
        $this->adminWriteRepository = $adminWriteRepository;
        return $this;
    }
}
