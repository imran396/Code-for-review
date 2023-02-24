<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\EntitySync;

trait EntitySyncDeleteRepositoryCreateTrait
{
    protected ?EntitySyncDeleteRepository $entitySyncDeleteRepository = null;

    protected function createEntitySyncDeleteRepository(): EntitySyncDeleteRepository
    {
        return $this->entitySyncDeleteRepository ?: EntitySyncDeleteRepository::new();
    }

    /**
     * @param EntitySyncDeleteRepository $entitySyncDeleteRepository
     * @return static
     * @internal
     */
    public function setEntitySyncDeleteRepository(EntitySyncDeleteRepository $entitySyncDeleteRepository): static
    {
        $this->entitySyncDeleteRepository = $entitySyncDeleteRepository;
        return $this;
    }
}
