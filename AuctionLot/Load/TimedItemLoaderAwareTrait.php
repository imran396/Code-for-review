<?php

namespace Sam\AuctionLot\Load;

/**
 * Trait TimedItemLoaderAwareTrait
 * @package Sam\AuctionLot\Load
 */
trait TimedItemLoaderAwareTrait
{
    /**
     * @var TimedItemLoader|null
     */
    protected ?TimedItemLoader $timedItemLoader = null;

    /**
     * @return TimedItemLoader
     */
    protected function getTimedItemLoader(): TimedItemLoader
    {
        if ($this->timedItemLoader === null) {
            $this->timedItemLoader = TimedItemLoader::new();
        }
        return $this->timedItemLoader;
    }

    /**
     * @param TimedItemLoader $timedItemLoader
     * @return static
     * @internal
     */
    public function setTimedOnlineItemLoader(TimedItemLoader $timedItemLoader): static
    {
        $this->timedItemLoader = $timedItemLoader;
        return $this;
    }
}
