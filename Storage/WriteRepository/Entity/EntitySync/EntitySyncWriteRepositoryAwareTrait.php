<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\EntitySync;

trait EntitySyncWriteRepositoryAwareTrait
{
    protected ?EntitySyncWriteRepository $entitySyncWriteRepository = null;

    protected function getEntitySyncWriteRepository(): EntitySyncWriteRepository
    {
        if ($this->entitySyncWriteRepository === null) {
            $this->entitySyncWriteRepository = EntitySyncWriteRepository::new();
        }
        return $this->entitySyncWriteRepository;
    }

    /**
     * @param EntitySyncWriteRepository $entitySyncWriteRepository
     * @return static
     * @internal
     */
    public function setEntitySyncWriteRepository(EntitySyncWriteRepository $entitySyncWriteRepository): static
    {
        $this->entitySyncWriteRepository = $entitySyncWriteRepository;
        return $this;
    }
}
