<?php
/**
 * SAM-6314: Unit tests for hybrid countdown calculator
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Hybrid\Countdown;


use Sam\Core\Service\CustomizableClass;
use DateTime;
use RuntimeException;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelper;

/**
 * Class HybridCountdownCalculator
 * @package Sam\Auction\Hybrid\Countdown
 */
class HybridCountdownCalculator extends CustomizableClass
{
    use CurrentDateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Find time period in seconds, when lot expected to close, if it is running
     * or when lot expected to start, if it has not been started yet
     *
     * @param int $orderNum order number of lot we are interested in
     * @param int $runningLotOrderNum order number of running lot
     * @param DateTime|null $runningLotEndDate lot end date of running lot (utc) - null means that $firstLotSecondsBefore value is counted by current date
     * @param DateTime|null $pauseDate auction/lot pause date (utc) - null means that $runningLotSecondsLeft value is counted by current date
     * @param int|null $extendTime extend time of auction (a.extend_time) - null means 0 sec.
     * @param int $lotStartGapTime lot start gap time (a.lot_start_gap_time, SAM-3371)
     * @param DateTime $auctionStartDate auction start date (utc)
     * @return array [seconds before lot start, seconds left till lot end]
     */
    public function calcTimeLeft(
        int $orderNum,
        int $runningLotOrderNum,
        ?DateTime $runningLotEndDate,
        ?DateTime $pauseDate,
        ?int $extendTime,
        int $lotStartGapTime,
        DateTime $auctionStartDate
    ): array {
        $secondsBefore = $secondsLeft = null;
        $currentDateUtc = $this->getCurrentDateUtc();
        $fullExtendTime = (int)$extendTime + $lotStartGapTime;
        if ($orderNum >= $runningLotOrderNum) {
            /**
             * Find seconds difference till current lot
             */
            if ($runningLotEndDate) {
                if ($pauseDate) {
                    $runningLotSecondsLeft = $runningLotEndDate->getTimestamp() - $pauseDate->getTimestamp();
                } else {
                    $runningLotSecondsLeft = $runningLotEndDate->getTimestamp() - $currentDateUtc->getTimestamp();
                }
                if ($orderNum > $runningLotOrderNum) {
                    $secondsBefore = $runningLotSecondsLeft + ($orderNum - $runningLotOrderNum - 1) * $fullExtendTime;
                } else {
                    $secondsLeft = $runningLotSecondsLeft;
                }
            } else {
                $firstLotSecondsBefore = $auctionStartDate->getTimestamp() - $currentDateUtc->getTimestamp();
                $secondsBefore = $firstLotSecondsBefore + ($orderNum - 1) * $fullExtendTime;
            }
        }
        $secondsBefore = $secondsBefore < 0 ? 0 : (int)$secondsBefore;
        $secondsLeft = $secondsLeft < 0 ? 0 : (int)$secondsLeft;
        return [$secondsBefore, $secondsLeft];
    }

    /**
     * Find countdown date
     *
     * @param int $timePeriod Seconds left till lot close or seconds before lot start
     * @param string $tzLocation Auction time zone location
     * @return int
     */
    public function calcDateTs(int $timePeriod, string $tzLocation): int
    {
        // timestamp of current date in time zone of auction
        $currentDateAuctionTz = DateHelper::new()
            ->convertTimezone($this->getCurrentDateUtc(), 'UTC', $tzLocation);
        $currentDateAuctionTz = new DateTime($currentDateAuctionTz->format('Y-m-d\TH:i:s'), new \DateTimeZone('UTC'));
        $ts = $currentDateAuctionTz->getTimestamp() + $timePeriod;
        return $ts;
    }
}
