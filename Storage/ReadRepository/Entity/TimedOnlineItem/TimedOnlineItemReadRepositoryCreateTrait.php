<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\TimedOnlineItem;

trait TimedOnlineItemReadRepositoryCreateTrait
{
    protected ?TimedOnlineItemReadRepository $timedOnlineItemReadRepository = null;

    protected function createTimedOnlineItemReadRepository(): TimedOnlineItemReadRepository
    {
        return $this->timedOnlineItemReadRepository ?: TimedOnlineItemReadRepository::new();
    }

    /**
     * @param TimedOnlineItemReadRepository $timedOnlineItemReadRepository
     * @return static
     * @internal
     */
    public function setTimedOnlineItemReadRepository(TimedOnlineItemReadRepository $timedOnlineItemReadRepository): static
    {
        $this->timedOnlineItemReadRepository = $timedOnlineItemReadRepository;
        return $this;
    }
}
