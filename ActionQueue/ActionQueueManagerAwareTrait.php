<?php

namespace Sam\ActionQueue;

/**
 * Trait ActionQueueManagerAwareTrait
 * @package Sam\ActionQueue
 */
trait ActionQueueManagerAwareTrait
{
    protected ?ActionQueueManager $actionQueueManager = null;

    /**
     * @param ActionQueueManager $actionQueueManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setActionQueueManager(ActionQueueManager $actionQueueManager): static
    {
        $this->actionQueueManager = $actionQueueManager;
        return $this;
    }

    /**
     * @return ActionQueueManager
     */
    protected function getActionQueueManager(): ActionQueueManager
    {
        if ($this->actionQueueManager === null) {
            $this->actionQueueManager = ActionQueueManager::new();
        }
        return $this->actionQueueManager;
    }
}
