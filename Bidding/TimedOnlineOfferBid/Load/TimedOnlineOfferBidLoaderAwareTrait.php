<?php
/**
 *
 * SAM-4745: TimedOnlineOfferBid loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-31
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\TimedOnlineOfferBid\Load;

/**
 * Trait TimedOnlineOfferBidLoaderAwareTrait
 * @package Sam\Bidding\TimedOnlineOfferBid\Load
 */
trait TimedOnlineOfferBidLoaderAwareTrait
{
    /**
     * @var TimedOnlineOfferBidLoader|null
     */
    protected ?TimedOnlineOfferBidLoader $timedOnlineOfferBidLoader = null;

    /**
     * @return TimedOnlineOfferBidLoader
     */
    protected function getTimedOnlineOfferBidLoader(): TimedOnlineOfferBidLoader
    {
        if ($this->timedOnlineOfferBidLoader === null) {
            $this->timedOnlineOfferBidLoader = TimedOnlineOfferBidLoader::new();
        }
        return $this->timedOnlineOfferBidLoader;
    }

    /**
     * @param TimedOnlineOfferBidLoader $timedOnlineOfferBidLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setTimedOnlineOfferBidLoader(TimedOnlineOfferBidLoader $timedOnlineOfferBidLoader): static
    {
        $this->timedOnlineOfferBidLoader = $timedOnlineOfferBidLoader;
        return $this;
    }
}
