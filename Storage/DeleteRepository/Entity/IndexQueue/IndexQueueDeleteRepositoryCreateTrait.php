<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\IndexQueue;

trait IndexQueueDeleteRepositoryCreateTrait
{
    protected ?IndexQueueDeleteRepository $indexQueueDeleteRepository = null;

    protected function createIndexQueueDeleteRepository(): IndexQueueDeleteRepository
    {
        return $this->indexQueueDeleteRepository ?: IndexQueueDeleteRepository::new();
    }

    /**
     * @param IndexQueueDeleteRepository $indexQueueDeleteRepository
     * @return static
     * @internal
     */
    public function setIndexQueueDeleteRepository(IndexQueueDeleteRepository $indexQueueDeleteRepository): static
    {
        $this->indexQueueDeleteRepository = $indexQueueDeleteRepository;
        return $this;
    }
}
