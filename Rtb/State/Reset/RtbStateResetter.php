<?php
/**
 * Rtb state reset service
 *
 * SAM-5012: Live/Hybrid auction state reset in rtbd process
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 27, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\State\Reset;

use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\Group\GroupingHelperAwareTrait;
use Sam\Rtb\Load\RtbLoaderAwareTrait;
use Sam\Rtb\Messenger;
use Sam\Rtb\State\History\HistoryServiceFactoryCreateTrait;
use Sam\Rtb\WebClient\AuctionStateResyncer;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class StateResetter
 */
class RtbStateResetter extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use GroupingHelperAwareTrait;
    use HistoryServiceFactoryCreateTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbLoaderAwareTrait;

    /**
     * Drop lot grouping
     */
    protected bool $shouldUngroup = true;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Ungroup lots
     * @param bool $enabled
     * @return static
     */
    public function enableUngroup(bool $enabled): static
    {
        $this->shouldUngroup = $enabled;
        return $this;
    }

    /**
     * Reset RtbCurrent record to initial state and save
     * @param int $auctionId
     * @param int $editorUserId
     * @return static
     */
    public function resetByAuction(int $auctionId, int $editorUserId): static
    {
        $rtbCurrent = $this->getRtbLoader()->loadByAuctionId($auctionId);
        if (!$rtbCurrent) {
            log_debug('Rtb state (RtbCurrent) is not found for auction, when trying to reset it' . composeSuffix(['a' => $auctionId]));
            return $this;
        }

        $rtbCurrent = $this->restartState($rtbCurrent);
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $editorUserId);

        $historyHelper = $this->createHistoryServiceFactory()->createHelper($rtbCurrent);
        $historyHelper->cleanForAuctionId($auctionId);

        $this->cleanMessageLog($auctionId);

        return $this;
    }

    /**
     * @param int $auctionId
     * @return static
     */
    public function notifyRtbd(int $auctionId): static
    {
        AuctionStateResyncer::new()
            ->setAuctionId($auctionId)
            ->notifyRtbd();
        return $this;
    }

    /**
     * Clean previous running lot state, but don't change general game options (e.g. AutoStart, LotItem and some other)
     * We should switch running lot before, eg. for group refreshing functionality
     * Do not persist changes in db.
     * @param RtbCurrent $rtbCurrent
     * @param int $editorUserId
     * @return RtbCurrent
     */
    public function cleanState(RtbCurrent $rtbCurrent, int $editorUserId): RtbCurrent
    {
        // Restore values, that shouldn't be touched
        $keepProperties = [
            'AutoStart' => null,
            'DefaultIncrement' => null,
            'EnableDecrement' => null,
            'EnterFloorNo' => null,
            'ExtendTime' => null,
            'LotItemId' => null,
            'LotStartGapTime' => null,
        ];
        // Store current values
        foreach ($keepProperties as $property => $value) {
            $keepProperties[$property] = $rtbCurrent->$property;
        }
        // Drop state
        $rtbCurrent = $this->dropState($rtbCurrent);
        // Restore previous values
        foreach ($keepProperties as $property => $value) {
            $rtbCurrent->$property = $value;
        }
        // LotGroup should be restored, only when $this->ungroup is disabled
        if (
            array_key_exists('LotGroup', $rtbCurrent->__Modified)
            && !$this->shouldUngroup
        ) {
            $rtbCurrent->LotGroup = $rtbCurrent->__Modified['LotGroup'];
        }

        if ($this->shouldUngroup) {
            $this->getGroupingHelper()->clearGroup($rtbCurrent->AuctionId);
        } else {
            $this->getGroupingHelper()->refreshGroupForRtbCurrent($rtbCurrent, $editorUserId);
        }

        $historyHelper = $this->createHistoryServiceFactory()->createHelper($rtbCurrent);
        $historyHelper->cleanForAuctionId($rtbCurrent->AuctionId);
        return $rtbCurrent;
    }

    /**
     * Assign initial values for rtb state.
     * Do not persist changes in db.
     * @param RtbCurrent $rtbCurrent
     * @return RtbCurrent
     */
    public function restartState(RtbCurrent $rtbCurrent): RtbCurrent
    {
        $rtbCurrent = $this->dropState($rtbCurrent);
        $this->getGroupingHelper()->clearGroup($rtbCurrent->AuctionId);
        $rtbCurrent->AutoStart = true;
        $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId, true);
        if (!$auction) {
            log_error(
                "Available auction not found, when restarting rtb state"
                . composeSuffix(['a' => $rtbCurrent->AuctionId])
            );
            return $rtbCurrent;
        }
        $rtbCurrent->ExtendTime = $auction->ExtendTime;
        $rtbCurrent->LotStartGapTime = $auction->LotStartGapTime;
        return $rtbCurrent;
    }

    /**
     * General rtb state dropping (rtb_current, rtb_current_group, rtb_current_snapshot)
     * @param RtbCurrent $rtbCurrent
     * @return RtbCurrent
     */
    public function dropState(RtbCurrent $rtbCurrent): RtbCurrent
    {
        $rtbCurrent = $this->dropRtbCurrentValues($rtbCurrent);
        $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId, true);
        if (!$auction) {
            log_error(
                "Available auction not found, when dropping rtb state"
                . composeSuffix(['a' => $rtbCurrent->AuctionId])
            );
            return $rtbCurrent;
        }
        return $rtbCurrent;
    }

    /**
     * Drop values of RtbCurrent except AuctionId
     * @param RtbCurrent $rtbCurrent
     * @return RtbCurrent
     */
    private function dropRtbCurrentValues(RtbCurrent $rtbCurrent): RtbCurrent
    {
        $rtbCurrent->AbsenteeBid = RtbCurrent::ABSENTEE_BID_DEFAULT;
        $rtbCurrent->AskBid = RtbCurrent::ASK_BID_DEFAULT;
        $rtbCurrent->AutoStart = RtbCurrent::AUTO_START_DEFAULT;
        $rtbCurrent->BidCountdown = RtbCurrent::BID_COUNTDOWN_DEFAULT;
        $rtbCurrent->BuyerUser = RtbCurrent::BUYER_USER_DEFAULT;
        $rtbCurrent->DefaultIncrement = RtbCurrent::DEFAULT_INCREMENT_DEFAULT;
        $rtbCurrent->EnableDecrement = RtbCurrent::ENABLE_DECREMENT_DEFAULT;
        $rtbCurrent->EnterFloorNo = RtbCurrent::ENTER_FLOOR_NO_DEFAULT;
        $rtbCurrent->ExtendTime = RtbCurrent::EXTEND_TIME_DEFAULT;
        $rtbCurrent->FairWarningSec = RtbCurrent::FAIR_WARNING_SEC_DEFAULT;
        $rtbCurrent->GroupUser = RtbCurrent::GROUP_USER_DEFAULT;
        $rtbCurrent->Increment = RtbCurrent::INCREMENT_DEFAULT;
        $rtbCurrent->LotActive = RtbCurrent::LOT_ACTIVE_DEFAULT;
        $rtbCurrent->LotEndDate = RtbCurrent::LOT_END_DATE_DEFAULT;
        $rtbCurrent->LotGroup = RtbCurrent::LOT_GROUP_DEFAULT;
        $rtbCurrent->LotItemId = RtbCurrent::LOT_ITEM_ID_DEFAULT;
        $rtbCurrent->LotStartGapTime = RtbCurrent::LOT_START_GAP_TIME_DEFAULT;
        $rtbCurrent->NewBidBy = RtbCurrent::NEW_BID_BY_DEFAULT;
        $rtbCurrent->NextLot = RtbCurrent::NEXT_LOT_DEFAULT;
        $rtbCurrent->PauseDate = RtbCurrent::PAUSE_DATE_DEFAULT;
        $rtbCurrent->PendingAction = RtbCurrent::PENDING_ACTION_DEFAULT;
        $rtbCurrent->PendingActionDate = RtbCurrent::PENDING_ACTION_DATE_DEFAULT;
        $rtbCurrent->Referrer = RtbCurrent::REFERRER_DEFAULT;
        $rtbCurrent->ReferrerHost = RtbCurrent::REFERRER_HOST_DEFAULT;
        $rtbCurrent->RunningInterval = RtbCurrent::RUNNING_INTERVAL_DEFAULT;
        return $rtbCurrent;
    }

    /**
     * @param int $auctionId
     */
    private function cleanMessageLog(int $auctionId): void
    {
        $types = [
            Messenger::MESSAGE_TYPE_ADMIN_HISTORY,
            Messenger::MESSAGE_TYPE_FRONT_HISTORY,
            Messenger::MESSAGE_TYPE_ADMIN_CHAT,
            Messenger::MESSAGE_TYPE_FRONT_CHAT,
        ];
        $logCleaner = \Sam\Rtb\Log\LogCleaner::new();
        foreach ($types as $type) {
            $logCleaner->clean($type, $auctionId);
        }
    }
}
