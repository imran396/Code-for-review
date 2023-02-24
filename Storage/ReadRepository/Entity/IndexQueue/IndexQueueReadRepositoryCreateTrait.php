<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\IndexQueue;

trait IndexQueueReadRepositoryCreateTrait
{
    protected ?IndexQueueReadRepository $indexQueueReadRepository = null;

    protected function createIndexQueueReadRepository(): IndexQueueReadRepository
    {
        return $this->indexQueueReadRepository ?: IndexQueueReadRepository::new();
    }

    /**
     * @param IndexQueueReadRepository $indexQueueReadRepository
     * @return static
     * @internal
     */
    public function setIndexQueueReadRepository(IndexQueueReadRepository $indexQueueReadRepository): static
    {
        $this->indexQueueReadRepository = $indexQueueReadRepository;
        return $this;
    }
}
