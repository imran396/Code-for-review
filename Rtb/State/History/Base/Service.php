<?php
/**
 * Service for storing current rtb state. It allows to save and restore state snapshots.
 * Assumed to use for Undo feature.
 * This is abstract class, so we extend it for Live and Hybrid auctions.
 *
 * SAM-3505: Clerk Console: Undo by snapshot
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           13 Okt, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\State\History\Base;

use RtbCurrent;
use RtbCurrentSnapshot;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Rtb\State\History\Base\Helper as HistoryHelper;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\BidTransaction\BidTransactionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrentSnapshot\RtbCurrentSnapshotWriteRepositoryAwareTrait;

/**
 * Class Service
 * @package Sam\Rtb\State\History\Base
 */
abstract class Service extends CustomizableClass implements ServiceInterface
{
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use BidTransactionWriteRepositoryAwareTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use RtbCurrentSnapshotWriteRepositoryAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;

    protected ?RtbCurrentSnapshot $lastRestoredSnapshot = null;

    /** @var string[] */
    protected array $commands = [
        Constants\Rtb::CMD_CHANGE_ASKING_BID_Q => 'Change Asking Bid',
        Constants\Rtb::CMD_ACCEPT_Q => 'Accept Bid',
        Constants\Rtb::CMD_PLACE_Q => 'Place Bid',
        Constants\Rtb::CMD_FLOOR_Q => 'Floor Bid',
        Constants\Rtb::CMD_FLOOR_PRIORITY_Q => 'Floor Priority Bid',
        Constants\Rtb::CMD_ABSENTEE_PRIORITY_Q => 'Absentee Priority Bid',
        // Constants\Rtb::CMD_CHANGE_DEFAULT_INCREMENT_Q => 'Change Default Increment',
        Constants\Rtb::CMD_CHANGE_INCREMENT_Q => 'Change Increment',
        // Constants\Rtb::CMD_ENABLE_DECREMENT_Q => 'Enable Decrement',
        Constants\Rtb::CMD_RESET_INCREMENT_Q => 'Reset Increment',
    ];

    /**
     * Properties of RtbCurrent that should be saved in snapshot
     */
    protected array $savedProperties = [
        'AuctionId',
        'LotItemId',
        'LotActive',
        'NewBidBy',
        'AskBid',
        'AbsenteeBid',
        'AutoStart',
        'NextLot',
        'Increment',
        'LotGroup',
        'GroupUser',
        'DefaultIncrement',
        'EnableDecrement',
        'BuyerUser',
        'BidCountdown',
    ];

    /**
     * Return command name of snapshot
     * @param RtbCurrentSnapshot $snapshot
     * @return string
     */
    public function getCommandName(RtbCurrentSnapshot $snapshot): string
    {
        return $this->commands[$snapshot->Command];
    }

    /**
     * Return supported commands with names
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @return HistoryHelper
     */
    public function getHistoryHelper(): HistoryHelper
    {
        return HistoryHelper::new();
    }

    /**
     * Return snapshot, that was previously restored by this service
     * @return RtbCurrentSnapshot|null
     */
    public function getLastRestoredSnapshot(): ?RtbCurrentSnapshot
    {
        return $this->lastRestoredSnapshot;
    }

    /**
     * Make snapshot of current rtb state
     * @param RtbCurrent $rtbCurrent
     * @param string $command
     * @param int $editorUserId
     */
    public function snapshot(RtbCurrent $rtbCurrent, string $command, int $editorUserId): void
    {
        if ($command === Constants\Rtb::CMD_CHANGE_ASKING_BID_Q) {
            $this->snapshotChangeAskingBid($rtbCurrent, $editorUserId);
        } else {
            $this->createPersistedSnapshot($rtbCurrent, $command, $editorUserId);
        }
        $this->getHistoryHelper()->clean($rtbCurrent);
    }

    /**
     * Make snapshot for Change Asking Bid command
     * @param RtbCurrent $rtbCurrent
     * @param int $editorUserId
     */
    protected function snapshotChangeAskingBid(RtbCurrent $rtbCurrent, int $editorUserId): void
    {
        $snapshotAllowed = true;
        $lastSnapshot = $this->getHistoryHelper()->loadLast($rtbCurrent);
        if (
            $lastSnapshot
            && $lastSnapshot->Command === Constants\Rtb::CMD_CHANGE_ASKING_BID_Q
            && Floating::eq($lastSnapshot->AskBid, $rtbCurrent->AskBid)
        ) {
            $snapshotAllowed = false;
        }
        if ($snapshotAllowed) {
            $this->createPersistedSnapshot($rtbCurrent, Constants\Rtb::CMD_CHANGE_ASKING_BID_Q, $editorUserId);
        }
    }

    /**
     * Make snapshot and save
     * @param RtbCurrent $rtbCurrent
     * @param string $command
     * @param int $editorUserId
     * @return RtbCurrentSnapshot
     */
    protected function createPersistedSnapshot(RtbCurrent $rtbCurrent, string $command, int $editorUserId): RtbCurrentSnapshot
    {
        $snapshot = $this->createEntityFactory()->rtbCurrentSnapshot();
        $snapshot->Command = $command;
        $snapshot->RtbCurrentId = $rtbCurrent->Id;
        foreach ($this->savedProperties as $property) {
            $snapshot->$property = $rtbCurrent->$property;
        }
        $this->getRtbCurrentSnapshotWriteRepository()->saveWithModifier($snapshot, $editorUserId);

        return $snapshot;
    }

    /**
     * Restore rtb state from the last snapshot, roll back current bid if needed
     * @param RtbCurrent $rtbCurrent
     * @param int $editorUserId
     * @return RtbCurrent|null
     */
    public function restore(RtbCurrent $rtbCurrent, int $editorUserId): ?RtbCurrent
    {
        $snapshot = $this->getHistoryHelper()->loadLast($rtbCurrent);
        if (!$snapshot) {
            log_error(
                "RtbCurrentSnapshot not found"
                . composeSuffix(['a' => $rtbCurrent->AuctionId, 'li' => $rtbCurrent->LotItemId])
            );
            return null;
        }

        if (
            in_array(
                $snapshot->Command,
                [
                    Constants\Rtb::CMD_ACCEPT_Q,
                    Constants\Rtb::CMD_FLOOR_Q,
                    Constants\Rtb::CMD_FLOOR_PRIORITY_Q,
                    Constants\Rtb::CMD_ABSENTEE_PRIORITY_Q,
                ],
                true
            )
        ) {
            $this->rollbackCurrentBid($rtbCurrent, $editorUserId);
        }

        $rtbCurrent = $this->applySnapshot($rtbCurrent, $snapshot);
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $editorUserId);

        $this->getRtbCurrentSnapshotWriteRepository()->deleteWithModifier($snapshot, $editorUserId);

        $this->lastRestoredSnapshot = $snapshot;
        $this->logRestore($rtbCurrent, $snapshot);

        return $rtbCurrent;
    }

    /**
     * Copy snapshot values to rtb current
     * @param RtbCurrent $rtbCurrent
     * @param RtbCurrentSnapshot $snapshot
     * @return RtbCurrent
     */
    protected function applySnapshot(RtbCurrent $rtbCurrent, RtbCurrentSnapshot $snapshot): RtbCurrent
    {
        foreach ($this->savedProperties as $property) {
            $rtbCurrent->$property = $snapshot->$property;
        }
        return $rtbCurrent;
    }

    /**
     * Current bid reverted to previous. Bid deleted
     * @param RtbCurrent $rtbCurrent
     * @param int $editorUserId
     */
    protected function rollbackCurrentBid(RtbCurrent $rtbCurrent, int $editorUserId): void
    {
        $currentBid = $this->createBidTransactionLoader()
            ->loadLastActiveBid($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        if ($currentBid) {
            $currentBid->Deleted = true;
            $this->getBidTransactionWriteRepository()->saveWithModifier($currentBid, $editorUserId);

            $auctionLot = $this->getAuctionLotLoader()->load($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
            if (!$auctionLot) {
                $logInfo = composeSuffix(['li' => $rtbCurrent->LotItemId, 'a' => $rtbCurrent->AuctionId]);
                log_error("Available auction lot not found for bid rollback" . $logInfo);
                return;
            }
            $previousBid = $this->createBidTransactionLoader()
                ->loadLastActiveBid($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
            if ($previousBid) {
                $auctionLot->linkCurrentBid($previousBid->Id);
                $newBid = $previousBid->Bid;
            } else {
                $auctionLot->unlinkCurrentBid();
                $newBid = '-';
            }
            $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);

            $logData = [
                'a' => $rtbCurrent->AuctionId,
                'li' => $rtbCurrent->LotItemId,
                'new bt' => $previousBid->Id ?? null,
                'new amount' => $newBid,
                'old bt' => $currentBid->Id,
                'old amount' => $currentBid->Bid,
            ];
            log_debug("Current Bid rolled back" . composeSuffix($logData));
        }
    }

    /**
     * Log restore operation
     * @param RtbCurrent $rtbCurrent
     * @param RtbCurrentSnapshot $snapshot
     */
    protected function logRestore(RtbCurrent $rtbCurrent, RtbCurrentSnapshot $snapshot): void
    {
        $currentSnapshot = $this->getHistoryHelper()->loadLast($rtbCurrent);
        if ($currentSnapshot) {
            $currentCommandName = $this->getCommandName($currentSnapshot);
        } else {
            $currentCommandName = '-';
        }

        log_info(
            "Restored from snapshot"
            . composeSuffix(
                [
                    'a' => $rtbCurrent->AuctionId,
                    'li' => $rtbCurrent->LotItemId,
                    'undone command' => $this->getCommandName($snapshot),
                    'current snapshot for' => $currentCommandName,
                ]
            )
        );
    }
}
