<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\TimedOnlineOfferBid;

trait TimedOnlineOfferBidReadRepositoryCreateTrait
{
    protected ?TimedOnlineOfferBidReadRepository $timedOnlineOfferBidReadRepository = null;

    protected function createTimedOnlineOfferBidReadRepository(): TimedOnlineOfferBidReadRepository
    {
        return $this->timedOnlineOfferBidReadRepository ?: TimedOnlineOfferBidReadRepository::new();
    }

    /**
     * @param TimedOnlineOfferBidReadRepository $timedOnlineOfferBidReadRepository
     * @return static
     * @internal
     */
    public function setTimedOnlineOfferBidReadRepository(TimedOnlineOfferBidReadRepository $timedOnlineOfferBidReadRepository): static
    {
        $this->timedOnlineOfferBidReadRepository = $timedOnlineOfferBidReadRepository;
        return $this;
    }
}
