<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\CachedQueue;

trait CachedQueueDeleteRepositoryCreateTrait
{
    protected ?CachedQueueDeleteRepository $cachedQueueDeleteRepository = null;

    protected function createCachedQueueDeleteRepository(): CachedQueueDeleteRepository
    {
        return $this->cachedQueueDeleteRepository ?: CachedQueueDeleteRepository::new();
    }

    /**
     * @param CachedQueueDeleteRepository $cachedQueueDeleteRepository
     * @return static
     * @internal
     */
    public function setCachedQueueDeleteRepository(CachedQueueDeleteRepository $cachedQueueDeleteRepository): static
    {
        $this->cachedQueueDeleteRepository = $cachedQueueDeleteRepository;
        return $this;
    }
}
