<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\ActionQueue;

trait ActionQueueWriteRepositoryAwareTrait
{
    protected ?ActionQueueWriteRepository $actionQueueWriteRepository = null;

    protected function getActionQueueWriteRepository(): ActionQueueWriteRepository
    {
        if ($this->actionQueueWriteRepository === null) {
            $this->actionQueueWriteRepository = ActionQueueWriteRepository::new();
        }
        return $this->actionQueueWriteRepository;
    }

    /**
     * @param ActionQueueWriteRepository $actionQueueWriteRepository
     * @return static
     * @internal
     */
    public function setActionQueueWriteRepository(ActionQueueWriteRepository $actionQueueWriteRepository): static
    {
        $this->actionQueueWriteRepository = $actionQueueWriteRepository;
        return $this;
    }
}
