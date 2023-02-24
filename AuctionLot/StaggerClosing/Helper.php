<?php
/**
 * Helper methods for "Staggered closing" feature
 *
 * Related tickets:
 * SAM-2706: AuctionHQ - extend all with offset
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Mar 6, 2015
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\StaggerClosing;

use DateInterval;
use DateTime;
use Sam\Core\Service\CustomizableClass;

/**
 * Class Helper
 * @package Sam\AuctionLot\StaggerClosing
 */
class Helper extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calculate lot end date by formula:
     * auction start closing date + floor(lot order number - 1 / lots per interval)  * staggered closing interval
     *
     * @param DateTime $auctionStartClosingDate
     * @param int $lotsPerInterval
     * @param int $staggerClosing
     * @param int $lotOrderNumber
     * @return DateTime
     */
    public function calcEndDate(DateTime $auctionStartClosingDate, int $lotsPerInterval, int $staggerClosing, int $lotOrderNumber): DateTime
    {
        if ($lotOrderNumber <= 0) {
            log_warning('lotOrderNumber should be greater than 0');
            return clone $auctionStartClosingDate;
        }

        $minutes = floor(($lotOrderNumber - 1) / $lotsPerInterval) * $staggerClosing;
        $endDate = clone $auctionStartClosingDate;
        if ($minutes > 0) {
            $endDate = $endDate->add(new DateInterval("PT{$minutes}M"));
        }

        return $endDate;
    }
}
