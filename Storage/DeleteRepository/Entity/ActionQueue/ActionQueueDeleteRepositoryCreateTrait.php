<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\ActionQueue;

trait ActionQueueDeleteRepositoryCreateTrait
{
    protected ?ActionQueueDeleteRepository $actionQueueDeleteRepository = null;

    protected function createActionQueueDeleteRepository(): ActionQueueDeleteRepository
    {
        return $this->actionQueueDeleteRepository ?: ActionQueueDeleteRepository::new();
    }

    /**
     * @param ActionQueueDeleteRepository $actionQueueDeleteRepository
     * @return static
     * @internal
     */
    public function setActionQueueDeleteRepository(ActionQueueDeleteRepository $actionQueueDeleteRepository): static
    {
        $this->actionQueueDeleteRepository = $actionQueueDeleteRepository;
        return $this;
    }
}
