<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SyncNamespace;

trait SyncNamespaceWriteRepositoryAwareTrait
{
    protected ?SyncNamespaceWriteRepository $syncNamespaceWriteRepository = null;

    protected function getSyncNamespaceWriteRepository(): SyncNamespaceWriteRepository
    {
        if ($this->syncNamespaceWriteRepository === null) {
            $this->syncNamespaceWriteRepository = SyncNamespaceWriteRepository::new();
        }
        return $this->syncNamespaceWriteRepository;
    }

    /**
     * @param SyncNamespaceWriteRepository $syncNamespaceWriteRepository
     * @return static
     * @internal
     */
    public function setSyncNamespaceWriteRepository(SyncNamespaceWriteRepository $syncNamespaceWriteRepository): static
    {
        $this->syncNamespaceWriteRepository = $syncNamespaceWriteRepository;
        return $this;
    }
}
