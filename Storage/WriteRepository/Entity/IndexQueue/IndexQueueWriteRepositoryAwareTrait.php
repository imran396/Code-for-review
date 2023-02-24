<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\IndexQueue;

trait IndexQueueWriteRepositoryAwareTrait
{
    protected ?IndexQueueWriteRepository $indexQueueWriteRepository = null;

    protected function getIndexQueueWriteRepository(): IndexQueueWriteRepository
    {
        if ($this->indexQueueWriteRepository === null) {
            $this->indexQueueWriteRepository = IndexQueueWriteRepository::new();
        }
        return $this->indexQueueWriteRepository;
    }

    /**
     * @param IndexQueueWriteRepository $indexQueueWriteRepository
     * @return static
     * @internal
     */
    public function setIndexQueueWriteRepository(IndexQueueWriteRepository $indexQueueWriteRepository): static
    {
        $this->indexQueueWriteRepository = $indexQueueWriteRepository;
        return $this;
    }
}
