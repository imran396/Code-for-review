<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use DateInterval;
use Sam\Core\Date\ComparableDateInterval;
use Sam\Rtb\Hybrid\HelpersAwareTrait;
use Sam\Core\Constants;
use Sam\Rtb\Command\Helper\Hybrid\HybridRtbCommandHelperAwareTrait;

/**
 * Class StartAuction
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class StartAuction extends \Sam\Rtb\Command\Concrete\Base\StartAuction
{
    use HelpersAwareTrait;
    use HybridRtbCommandHelperAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function execute(): void
    {
        parent::execute();

        $lotActivity = Constants\Rtb::LA_BY_AUTO_START;
        if ($this->isRunning()) {
            $rtbCurrent = $this->getRtbCurrent();
            if ($this->isResumed()) {
                if ($rtbCurrent->isPausedLot()) {
                    /**
                     * If lot was paused, keep it paused, when auction is resumed.
                     */
                    $lotActivity = Constants\Rtb::LA_PAUSED;
                } else {
                    if (!$rtbCurrent->AutoStart) {
                        /**
                         * If auction is resumed and AutoStart is Off,
                         * we compare current date interval with extend time to detect
                         * if system should activate lot (countdown != extend time => it was running before)
                         * or it should be activated manually (countdown == extend time => we don't know if it was running or just changed)
                         */
                        $lotActivity = Constants\Rtb::LA_STARTED;
                        $currentDateInterval = $this->getTimeoutHelper()->getDateIntervalBetweenPauseAndLotEnd($rtbCurrent);
                        if ($currentDateInterval) {
                            // Check for Extend Time
                            $currentDateIntervalComp = ComparableDateInterval::create($currentDateInterval);
                            $extendDateInterval = new DateInterval('PT' . $this->getAuction()->ExtendTime . 'S');
                            $extendDateIntervalComp = ComparableDateInterval::create($extendDateInterval);
                            if ($currentDateIntervalComp->compare($extendDateIntervalComp) === 0) {
                                $lotActivity = Constants\Rtb::LA_IDLE;
                            }
                            // Check for Lot Start Gap Time
                            if (
                                $this->getAuction()->LotStartGapTime
                                && $lotActivity
                            ) {
                                $extendAndGapDateInterval = new DateInterval('PT' . ($this->getAuction()->ExtendTime + $this->getAuction()->LotStartGapTime) . 'S');
                                $extendAndGapDateIntervalComp = ComparableDateInterval::create($extendAndGapDateInterval);
                                if ($currentDateIntervalComp->compare($extendAndGapDateIntervalComp) === 0) {
                                    $lotActivity = Constants\Rtb::LA_IDLE;
                                }
                            }
                        }
                    }
                }
            } else {
                $this->getTimeoutHelper()->updateLotEndDate($rtbCurrent, $this->detectModifierUserId());
            }
            $rtbCurrent = $this->getRtbCommandHelper()->activateLot($rtbCurrent, $lotActivity, $this->detectModifierUserId());
            $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
        }
        $this->createStaticMessages();
        $this->log();
    }

    /**
     * Check if running lot (rtb_current.lot_item_id) can be played
     * @return bool
     */
    protected function isPlayableRunningLot(): bool
    {
        $rtbCurrent = $this->getRtbCurrent();
        $isPlayable = true;
        if (!$rtbCurrent->LotItemId) {
            $isPlayable = false;
        }
        if ($rtbCurrent->LotItemId) {
            $auctionLot = $this->getAuctionLot();
            if (
                !$this->isResumed()
                && $auctionLot->isAmongWonStatuses()
            ) {
                // If we started new auction and current lot is sold or received, then find another lot
                $isPlayable = false;
            }
        }
        return $isPlayable;
    }

    protected function createResponses(): void
    {
        parent::createResponses();
        $this->getResponseHelper()->applyAdditionalInfo($this);
    }
}
