<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\CachedQueue;

trait CachedQueueReadRepositoryCreateTrait
{
    protected ?CachedQueueReadRepository $cachedQueueReadRepository = null;

    protected function createCachedQueueReadRepository(): CachedQueueReadRepository
    {
        return $this->cachedQueueReadRepository ?: CachedQueueReadRepository::new();
    }

    /**
     * @param CachedQueueReadRepository $cachedQueueReadRepository
     * @return static
     * @internal
     */
    public function setCachedQueueReadRepository(CachedQueueReadRepository $cachedQueueReadRepository): static
    {
        $this->cachedQueueReadRepository = $cachedQueueReadRepository;
        return $this;
    }
}
