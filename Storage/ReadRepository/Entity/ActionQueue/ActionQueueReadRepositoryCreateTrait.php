<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\ActionQueue;

trait ActionQueueReadRepositoryCreateTrait
{
    protected ?ActionQueueReadRepository $actionQueueReadRepository = null;

    protected function createActionQueueReadRepository(): ActionQueueReadRepository
    {
        return $this->actionQueueReadRepository ?: ActionQueueReadRepository::new();
    }

    /**
     * @param ActionQueueReadRepository $actionQueueReadRepository
     * @return static
     * @internal
     */
    public function setActionQueueReadRepository(ActionQueueReadRepository $actionQueueReadRepository): static
    {
        $this->actionQueueReadRepository = $actionQueueReadRepository;
        return $this;
    }
}
