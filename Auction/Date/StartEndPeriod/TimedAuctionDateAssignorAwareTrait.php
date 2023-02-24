<?php
/**
 * SAM-4914: Lot-to-Auction and Auction-to-Lot Start/End date assigning modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/23/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Date\StartEndPeriod;

/**
 * Trait TimedAuctionDateAssignorAwareTrait
 * @package Sam\Auction\Date\StartEndPeriod
 */
trait TimedAuctionDateAssignorAwareTrait
{
    protected ?TimedAuctionDateAssignor $timedAuctionDateAssignor = null;

    /**
     * @return TimedAuctionDateAssignor
     */
    protected function getTimedAuctionDateAssignor(): TimedAuctionDateAssignor
    {
        if ($this->timedAuctionDateAssignor === null) {
            $this->timedAuctionDateAssignor = TimedAuctionDateAssignor::new();
        }
        return $this->timedAuctionDateAssignor;
    }

    /**
     * @param TimedAuctionDateAssignor $timedAuctionDateAssignor
     * @return static
     * @internal
     */
    public function setTimedAuctionDateAssignor(TimedAuctionDateAssignor $timedAuctionDateAssignor): static
    {
        $this->timedAuctionDateAssignor = $timedAuctionDateAssignor;
        return $this;
    }
}
