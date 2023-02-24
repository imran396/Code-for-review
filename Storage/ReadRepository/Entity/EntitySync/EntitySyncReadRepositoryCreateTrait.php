<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\EntitySync;

trait EntitySyncReadRepositoryCreateTrait
{
    protected ?EntitySyncReadRepository $entitySyncReadRepository = null;

    protected function createEntitySyncReadRepository(): EntitySyncReadRepository
    {
        return $this->entitySyncReadRepository ?: EntitySyncReadRepository::new();
    }

    /**
     * @param EntitySyncReadRepository $entitySyncReadRepository
     * @return static
     * @internal
     */
    public function setEntitySyncReadRepository(EntitySyncReadRepository $entitySyncReadRepository): static
    {
        $this->entitySyncReadRepository = $entitySyncReadRepository;
        return $this;
    }
}
