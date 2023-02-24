<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SyncNamespace;

trait SyncNamespaceDeleteRepositoryCreateTrait
{
    protected ?SyncNamespaceDeleteRepository $syncNamespaceDeleteRepository = null;

    protected function createSyncNamespaceDeleteRepository(): SyncNamespaceDeleteRepository
    {
        return $this->syncNamespaceDeleteRepository ?: SyncNamespaceDeleteRepository::new();
    }

    /**
     * @param SyncNamespaceDeleteRepository $syncNamespaceDeleteRepository
     * @return static
     * @internal
     */
    public function setSyncNamespaceDeleteRepository(SyncNamespaceDeleteRepository $syncNamespaceDeleteRepository): static
    {
        $this->syncNamespaceDeleteRepository = $syncNamespaceDeleteRepository;
        return $this;
    }
}
