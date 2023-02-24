<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\CachedQueue;

trait CachedQueueWriteRepositoryAwareTrait
{
    protected ?CachedQueueWriteRepository $cachedQueueWriteRepository = null;

    protected function getCachedQueueWriteRepository(): CachedQueueWriteRepository
    {
        if ($this->cachedQueueWriteRepository === null) {
            $this->cachedQueueWriteRepository = CachedQueueWriteRepository::new();
        }
        return $this->cachedQueueWriteRepository;
    }

    /**
     * @param CachedQueueWriteRepository $cachedQueueWriteRepository
     * @return static
     * @internal
     */
    public function setCachedQueueWriteRepository(CachedQueueWriteRepository $cachedQueueWriteRepository): static
    {
        $this->cachedQueueWriteRepository = $cachedQueueWriteRepository;
        return $this;
    }
}
