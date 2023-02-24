<?php
/**
 * SAM-11182: Extract timed lot bidding logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\TimedBid\Place;

/**
 * Trait TimedBidSaverCreateTrait
 * @package Sam\Bidding\TimedBid\Place
 */
trait TimedBidSaverCreateTrait
{
    protected ?TimedBidSaver $timedBidSaver = null;

    /**
     * @return TimedBidSaver
     */
    protected function createTimedBidSaver(): TimedBidSaver
    {
        return $this->timedBidSaver ?: TimedBidSaver::new();
    }

    /**
     * @param TimedBidSaver $timedBidSaver
     * @return $this
     * @internal
     */
    public function setTimedBidSaver(TimedBidSaver $timedBidSaver): static
    {
        $this->timedBidSaver = $timedBidSaver;
        return $this;
    }
}
