<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TimedOnlineItem;

trait TimedOnlineItemWriteRepositoryAwareTrait
{
    protected ?TimedOnlineItemWriteRepository $timedOnlineItemWriteRepository = null;

    protected function getTimedOnlineItemWriteRepository(): TimedOnlineItemWriteRepository
    {
        if ($this->timedOnlineItemWriteRepository === null) {
            $this->timedOnlineItemWriteRepository = TimedOnlineItemWriteRepository::new();
        }
        return $this->timedOnlineItemWriteRepository;
    }

    /**
     * @param TimedOnlineItemWriteRepository $timedOnlineItemWriteRepository
     * @return static
     * @internal
     */
    public function setTimedOnlineItemWriteRepository(TimedOnlineItemWriteRepository $timedOnlineItemWriteRepository): static
    {
        $this->timedOnlineItemWriteRepository = $timedOnlineItemWriteRepository;
        return $this;
    }
}
