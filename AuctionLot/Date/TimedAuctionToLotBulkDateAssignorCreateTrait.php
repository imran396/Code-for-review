<?php
/**
 * SAM-6776: Performance improvements for assigning auction lot dates after the CSV upload process
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Date;

/**
 * Trait TimedAuctionToLotBulkDateAssignorCreateTrait
 * @package Sam\AuctionLot\Date
 */
trait TimedAuctionToLotBulkDateAssignorCreateTrait
{
    protected ?TimedAuctionToLotBulkDateAssignor $timedAuctionToLotBulkDateAssignor = null;

    /**
     * @return TimedAuctionToLotBulkDateAssignor
     */
    protected function createTimedAuctionToLotBulkDateAssignor(): TimedAuctionToLotBulkDateAssignor
    {
        return $this->timedAuctionToLotBulkDateAssignor ?: TimedAuctionToLotBulkDateAssignor::new();
    }

    /**
     * @param TimedAuctionToLotBulkDateAssignor $timedAuctionToLotBulkDateAssignor
     * @return static
     * @internal
     */
    public function setTimedAuctionToLotBulkDateAssignor(TimedAuctionToLotBulkDateAssignor $timedAuctionToLotBulkDateAssignor): static
    {
        $this->timedAuctionToLotBulkDateAssignor = $timedAuctionToLotBulkDateAssignor;
        return $this;
    }
}
