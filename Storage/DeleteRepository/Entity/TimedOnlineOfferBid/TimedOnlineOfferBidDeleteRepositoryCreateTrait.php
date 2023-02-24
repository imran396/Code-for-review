<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\TimedOnlineOfferBid;

trait TimedOnlineOfferBidDeleteRepositoryCreateTrait
{
    protected ?TimedOnlineOfferBidDeleteRepository $timedOnlineOfferBidDeleteRepository = null;

    protected function createTimedOnlineOfferBidDeleteRepository(): TimedOnlineOfferBidDeleteRepository
    {
        return $this->timedOnlineOfferBidDeleteRepository ?: TimedOnlineOfferBidDeleteRepository::new();
    }

    /**
     * @param TimedOnlineOfferBidDeleteRepository $timedOnlineOfferBidDeleteRepository
     * @return static
     * @internal
     */
    public function setTimedOnlineOfferBidDeleteRepository(TimedOnlineOfferBidDeleteRepository $timedOnlineOfferBidDeleteRepository): static
    {
        $this->timedOnlineOfferBidDeleteRepository = $timedOnlineOfferBidDeleteRepository;
        return $this;
    }
}
