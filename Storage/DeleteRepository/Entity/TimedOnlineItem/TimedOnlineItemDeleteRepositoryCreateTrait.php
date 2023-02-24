<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\TimedOnlineItem;

trait TimedOnlineItemDeleteRepositoryCreateTrait
{
    protected ?TimedOnlineItemDeleteRepository $timedOnlineItemDeleteRepository = null;

    protected function createTimedOnlineItemDeleteRepository(): TimedOnlineItemDeleteRepository
    {
        return $this->timedOnlineItemDeleteRepository ?: TimedOnlineItemDeleteRepository::new();
    }

    /**
     * @param TimedOnlineItemDeleteRepository $timedOnlineItemDeleteRepository
     * @return static
     * @internal
     */
    public function setTimedOnlineItemDeleteRepository(TimedOnlineItemDeleteRepository $timedOnlineItemDeleteRepository): static
    {
        $this->timedOnlineItemDeleteRepository = $timedOnlineItemDeleteRepository;
        return $this;
    }
}
