<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SyncNamespace;

trait SyncNamespaceReadRepositoryCreateTrait
{
    protected ?SyncNamespaceReadRepository $syncNamespaceReadRepository = null;

    protected function createSyncNamespaceReadRepository(): SyncNamespaceReadRepository
    {
        return $this->syncNamespaceReadRepository ?: SyncNamespaceReadRepository::new();
    }

    /**
     * @param SyncNamespaceReadRepository $syncNamespaceReadRepository
     * @return static
     * @internal
     */
    public function setSyncNamespaceReadRepository(SyncNamespaceReadRepository $syncNamespaceReadRepository): static
    {
        $this->syncNamespaceReadRepository = $syncNamespaceReadRepository;
        return $this;
    }
}
