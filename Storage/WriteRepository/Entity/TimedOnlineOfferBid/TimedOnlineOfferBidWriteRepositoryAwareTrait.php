<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TimedOnlineOfferBid;

trait TimedOnlineOfferBidWriteRepositoryAwareTrait
{
    protected ?TimedOnlineOfferBidWriteRepository $timedOnlineOfferBidWriteRepository = null;

    protected function getTimedOnlineOfferBidWriteRepository(): TimedOnlineOfferBidWriteRepository
    {
        if ($this->timedOnlineOfferBidWriteRepository === null) {
            $this->timedOnlineOfferBidWriteRepository = TimedOnlineOfferBidWriteRepository::new();
        }
        return $this->timedOnlineOfferBidWriteRepository;
    }

    /**
     * @param TimedOnlineOfferBidWriteRepository $timedOnlineOfferBidWriteRepository
     * @return static
     * @internal
     */
    public function setTimedOnlineOfferBidWriteRepository(TimedOnlineOfferBidWriteRepository $timedOnlineOfferBidWriteRepository): static
    {
        $this->timedOnlineOfferBidWriteRepository = $timedOnlineOfferBidWriteRepository;
        return $this;
    }
}
