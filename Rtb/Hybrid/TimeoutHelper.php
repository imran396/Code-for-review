<?php

namespace Sam\Rtb\Hybrid;

use DateInterval;
use DateTime;
use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class TimeoutHelper
 * @package Sam\Rtb\Hybrid
 */
class TimeoutHelper extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use CurrentDateTrait;
    use RtbCurrentWriteRepositoryAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calculate lot end date considering extend time and start gap time.
     * Method considers auction pause date, if auction was paused.
     * @param RtbCurrent $rtbCurrent
     * @return DateTime|null
     */
    public function calcLotEndDate(RtbCurrent $rtbCurrent): ?DateTime
    {
        $afterPauseInterval = $this->getDateIntervalBetweenPauseAndLotEnd($rtbCurrent);
        if ($afterPauseInterval) { // means, that was paused
            $lotEndDate = $this->getCurrentDateUtc();
            $lotEndDate->add($afterPauseInterval);
        } else {
            $lotEndDate = $this->calcLotEndDateWithExtendAndGapTime($rtbCurrent);
        }
        return $lotEndDate;
    }

    /**
     * Return time interval between rtb_current.pause_date and rtb_current.lot_end_date
     * Return false, if interval cannot be found.
     * @param RtbCurrent $rtbCurrent
     * @return DateInterval|null
     */
    public function getDateIntervalBetweenPauseAndLotEnd(RtbCurrent $rtbCurrent): ?DateInterval
    {
        $dateInterval = null;
        if (
            $rtbCurrent->PauseDate
            && $rtbCurrent->LotEndDate
        ) {
            $dateInterval = $rtbCurrent->PauseDate->diff($rtbCurrent->LotEndDate);
        }
        return $dateInterval;
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return int|null
     */
    public function calcSecondsBeforeLotEnd(RtbCurrent $rtbCurrent): ?int
    {
        $secondsLeft = null;
        $currentDate = $this->getCurrentDateUtc();

        if ($rtbCurrent->LotEndDate) {
            if ($rtbCurrent->PauseDate === null) {
                $secondsLeft = $rtbCurrent->LotEndDate->getTimestamp() - $currentDate->getTimestamp();
                log_trace(static fn() => composeLogData(
                    [
                        'LotEndDate' => $rtbCurrent->LotEndDate->format(Constants\Date::ISO),
                        'Now' => $currentDate->format(Constants\Date::ISO),
                        'SecondsLeft' => $secondsLeft,
                        'PauseDate' => null,
                        'RunningInterval' => $rtbCurrent->RunningInterval,
                    ]
                ));
            } else {
                $lotEndDate = $this->calcLotEndDate($rtbCurrent);
                $secondsLeft = $lotEndDate ? $lotEndDate->getTimestamp() - $currentDate->getTimestamp() : null;
                log_trace(static fn() => composeLogData(
                    [
                        'LotEndDate' => $rtbCurrent->LotEndDate->format(Constants\Date::ISO),
                        'Now' => $currentDate->format(Constants\Date::ISO),
                        'SecondsLeft' => $secondsLeft,
                        'PauseDate' => $rtbCurrent->PauseDate,
                        'RunningInterval' => $rtbCurrent->RunningInterval,
                    ]
                ));
            }
        } else {
            // When lot end date undefined
            $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId);
            if (!$auction) {
                log_error(
                    "Available auction not found, when updating lot end date"
                    . composeSuffix(['a' => $rtbCurrent->AuctionId])
                );
                return null;
            }
            if ($auction->isStartedOrPaused()) {
                $secondsLeft = $rtbCurrent->ExtendTime + $rtbCurrent->LotStartGapTime;
            } elseif ($auction->isClosed()) {
                $secondsLeft = 0;
            }
        }
        return $secondsLeft;
    }

    /**
     * Update rtb_current.lot_end_date according interval we are in (Extend Time or Lot Start Gap Time)
     * @param RtbCurrent $rtbCurrent
     * @param int $editorUserId
     * @return RtbCurrent
     */
    public function updateLotEndDate(RtbCurrent $rtbCurrent, int $editorUserId): RtbCurrent
    {
        $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId);
        if (!$auction) {
            log_error(
                "Available auction not found, when updating lot end date"
                . composeSuffix(['a' => $rtbCurrent->AuctionId])
            );
            return $rtbCurrent;
        }
        // Detect RunningInterval for ExtendTime and LotStartGapTime before their possible change
        $runningInterval = $this->detectRunningInterval($rtbCurrent);
        $rtbCurrent->ExtendTime = $auction->ExtendTime;
        $rtbCurrent->LotStartGapTime = $auction->LotStartGapTime;
        if ($runningInterval === Constants\Rtb::RI_EXTEND_TIME) {
            $rtbCurrent->LotEndDate = $this->calcLotEndDateWithExtendTime($rtbCurrent);
            log_debug(static fn() => 'Update rtb_current.lot_end_date with Extend Time (' . $rtbCurrent->ExtendTime . '): '
                . ($rtbCurrent->LotEndDate ? $rtbCurrent->LotEndDate->format(Constants\Date::ISO) : 'n/a')
                . composeSuffix(['a' => $rtbCurrent->AuctionId, 'li' => $rtbCurrent->LotItemId])
            );
        } elseif ($runningInterval === Constants\Rtb::RI_LOT_START_GAP_TIME) {
            $rtbCurrent->LotEndDate = $this->calcLotEndDateWithExtendAndGapTime($rtbCurrent);
            log_debug(static fn() => 'Update rtb_current.lot_end_date with Extend (' . $rtbCurrent->ExtendTime . ')'
                . ' and Gap (' . $rtbCurrent->LotStartGapTime . ') time: '
                . ($rtbCurrent->LotEndDate ? $rtbCurrent->LotEndDate->format(Constants\Date::ISO) : 'n/a')
                . composeSuffix(['a' => $rtbCurrent->AuctionId, 'li' => $rtbCurrent->LotItemId])
            );
        }
        // Update RunningInterval according actual ExtendTime and LotStartGapTime settings
        $rtbCurrent->RunningInterval = $this->detectRunningInterval($rtbCurrent);
        $this->commitChangesFromUpdateAction($rtbCurrent, $editorUserId);
        return $rtbCurrent;
    }

    /**
     * Check, if currently we are inside Extend Time interval
     * @param RtbCurrent $rtbCurrent
     * @return bool
     */
    public function isInExtendTime(RtbCurrent $rtbCurrent): bool
    {
        $secondsLeft = $this->calcSecondsBeforeLotEnd($rtbCurrent);
        $isInExtendTime = $secondsLeft <= $rtbCurrent->ExtendTime;
        return $isInExtendTime;
    }

    /**
     * Check, if currently we are inside Lot Start Gap Time interval
     * @param RtbCurrent $rtbCurrent
     * @return bool
     */
    public function isInGapTime(RtbCurrent $rtbCurrent): bool
    {
        $isInGapTime = false;
        if ($rtbCurrent->LotStartGapTime > 0) {
            $secondsLeft = $this->calcSecondsBeforeLotEnd($rtbCurrent);
            $isInGapTime = ($secondsLeft <= $rtbCurrent->ExtendTime + $rtbCurrent->LotStartGapTime)
                && ($secondsLeft > $rtbCurrent->ExtendTime);
        }
        return $isInGapTime;
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return string
     */
    public function detectRunningInterval(RtbCurrent $rtbCurrent): string
    {
        if ($this->isInExtendTime($rtbCurrent)) {
            $runningInterval = Constants\Rtb::RI_EXTEND_TIME;
        } else {
            // } elseif ($this->isInGapTime($rtbCurrent)) {
            // All other than Extend Time we consider as Lot Start Gap Time (LSGP). It fixes SAM-5099
            // When lot intervals are changed to low side, we want to consider existing overtime of rtbc.lot_end_date as LSGP
            $runningInterval = Constants\Rtb::RI_LOT_START_GAP_TIME;
        }
        return $runningInterval;
    }

    /**
     * Calculate Lot End Date by formula: Now + Extend Time
     * @param RtbCurrent $rtbCurrent
     * @return DateTime|null
     */
    protected function calcLotEndDateWithExtendTime(RtbCurrent $rtbCurrent): ?DateTime
    {
        $lotEndDate = null;
        if ($rtbCurrent->ExtendTime) {
            $lotEndDate = $this->getCurrentDateUtc();
            $extendTimeInterval = new DateInterval('PT' . $rtbCurrent->ExtendTime . 'S');
            $lotEndDate->add($extendTimeInterval);
        }
        return $lotEndDate;
    }

    /**
     * Calculate Lot End Date by formula: Now + Extend Time + Lot Start Gap Time
     * @param RtbCurrent $rtbCurrent
     * @return DateTime|null
     */
    protected function calcLotEndDateWithExtendAndGapTime(RtbCurrent $rtbCurrent): ?DateTime
    {
        $lotEndDate = $this->calcLotEndDateWithExtendTime($rtbCurrent);
        if (
            $lotEndDate
            && $rtbCurrent->LotStartGapTime
        ) {
            $gapTimeInterval = new DateInterval('PT' . $rtbCurrent->LotStartGapTime . 'S');
            $lotEndDate->add($gapTimeInterval);
        }
        return $lotEndDate;
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @param int $editorUserId
     */
    protected function commitChangesFromUpdateAction(RtbCurrent $rtbCurrent, int $editorUserId): void
    {
        if (
            $rtbCurrent->isIdleLot()
            || $rtbCurrent->isPausedLot()
        ) {
            $rtbCurrent->PauseDate = $this->getCurrentDateUtc();
        }
        $rtbCurrent->FairWarningSec = null;
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $editorUserId);
    }
}
