<?php

namespace Sam\Bidding\TimedBid\Notify;

/**
 * Trait TimedBidNotifierAwareTrait
 * @package Sam\Bidding\TimedBid\Notify
 */
trait TimedBidNotifierAwareTrait
{
    /**
     * @var TimedBidNotifier|null
     */
    protected ?TimedBidNotifier $timedBidNotifier = null;

    /**
     * @return TimedBidNotifier
     */
    protected function getTimedBidNotifier(): TimedBidNotifier
    {
        if ($this->timedBidNotifier === null) {
            $this->timedBidNotifier = TimedBidNotifier::new();
        }
        return $this->timedBidNotifier;
    }

    /**
     * @param TimedBidNotifier $timedBidNotifier
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setTimedBidNotifier(TimedBidNotifier $timedBidNotifier): static
    {
        $this->timedBidNotifier = $timedBidNotifier;
        return $this;
    }
}
