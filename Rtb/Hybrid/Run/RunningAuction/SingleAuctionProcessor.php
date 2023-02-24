<?php
/**
 * Single iteration of running auctions processing
 * SAM-3775: Rtbd improvements
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 01, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Hybrid\Run\RunningAuction;

use Auction;
use AuctionLotItem;
use DateInterval;
use Sam\Auction\Hybrid\FairWarning\Load\HybridFairWarningLoaderCreateTrait;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\AuctionLot\Validate\AuctionLotExistenceCheckerAwareTrait;
use Sam\Bidding\AbsenteeBid\Validate\AbsenteeBidExistenceCheckerAwareTrait;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\JsonArray;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Command\Concrete\Hybrid\AcceptBid;
use Sam\Rtb\Command\Concrete\Hybrid\EnterBidderNumber;
use Sam\Rtb\Command\Concrete\Hybrid\PassLot;
use Sam\Rtb\Command\Concrete\Hybrid\ResetRunningLotCountdown;
use Sam\Rtb\Command\Concrete\Hybrid\SellBuyer;
use Sam\Rtb\Command\Concrete\Hybrid\SellLot;
use Sam\Rtb\Command\Concrete\Hybrid\SendFairWarning;
use Sam\Rtb\Command\Concrete\Hybrid\StopAuction;
use Sam\Rtb\Command\Concrete\Hybrid\Sync;
use Sam\Rtb\Command\Concrete\SellLots\Base\SellLotsCommand;
use Sam\Rtb\Command\Concrete\SellLots\Hybrid\SellLotsHybridClerkHandler;
use Sam\Rtb\Command\Controller\AdminHybridController;
use Sam\Rtb\Hybrid\Run\Base\HybridResponseSenderAwareTrait;
use Sam\Rtb\Hybrid\TimeoutHelperAwareTrait;
use Sam\Rtb\Load\RtbLoaderAwareTrait;
use Sam\Rtb\Log\Logger;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\Server\Daemon\RtbDaemonAwareTrait;
use Sam\Rtb\State\RtbStateUpdaterCreateTrait;
use Sam\Rtb\User\UserHashGeneratorCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class Processor
 * @package Sam\Rtb\Hybrid\Run\RunningAuction
 * @method Auction getAuction(bool $isReadOnlyDb = false) - availability is checked at process method
 */
class SingleAuctionProcessor extends CustomizableClass
{
    use AbsenteeBidExistenceCheckerAwareTrait;
    use AuctionAwareTrait;
    use AuctionCacheLoaderAwareTrait;
    use AuctionLotExistenceCheckerAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use HighBidDetectorCreateTrait;
    use HybridFairWarningLoaderCreateTrait;
    use HybridResponseSenderAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbDaemonAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbLoaderAwareTrait;
    use RtbStateUpdaterCreateTrait;
    use SettingsManagerAwareTrait;
    use TimeoutHelperAwareTrait;
    use UserHashGeneratorCreateTrait;

    protected int $activeCount = 0;
    protected ?int $viewLanguageId = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Process running auction
     * @param Auction $auction
     * @param int $activeCount
     */
    public function process(Auction $auction, int $activeCount): void
    {
        $this->setAuction($auction);
        $this->activeCount = $activeCount;
        $this->prepareRunningLot();
        if ($this->hasActiveLots()) {
            if ($this->isRunningLotActive()) {
                if ($this->isGapTimeJustEnded()) {
                    $this->switchGapToExtend();
                }
                if ($this->hasBid()) {
                    $this->acceptBid();
                }
                if ($this->isLotEnded()) {
                    $this->closeLot();
                }
                $this->processFairMessage();
            } else {
                /**
                 * Here we handle running lot with Paused or Idle status.
                 */
                if ($this->isPendingAction()) {
                    $this->processPendingAction();
                }
            }
        } else {
            $this->stopAuction();
        }
    }

    /**
     * @param CommandBase $command
     * @return CommandBase
     */
    protected function initCommand(CommandBase $command): CommandBase
    {
        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->getAuction());
        $command->setAuction($this->getAuction());
        $command->setLogger($this->getLogger());
        $command->setRtbCurrent($rtbCurrent);
        $command->setRtbDaemon($this->getRtbDaemon());
        $command->setUserType(Constants\Rtb::UT_SYSTEM);
        return $command;
    }

    /**
     * Check if running lot available in auction (could be deleted),
     * else run rtb synchronization, it switches running lot and prepare rtb state.
     */
    protected function prepareRunningLot(): void
    {
        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->getAuction());
        $isFound = $this->getAuctionLotExistenceChecker()->exist($rtbCurrent->LotItemId, $this->getAuctionId());
        if (!$isFound) {
            /** @var Sync $command */
            $command = $this->initCommand(Sync::new());
            $command->execute();
            $responses = $command->getResponses();
            $this->getHybridResponseSender()->send($responses, $this->getAuctionId());
        }
    }

    /**
     * Check if it is the first second of "Extend Time" interval
     * @return bool
     */
    protected function isGapTimeJustEnded(): bool
    {
        $justEnded = false;
        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->getAuction());
        if (
            $rtbCurrent->LotEndDate
            && $rtbCurrent->RunningInterval === Constants\Rtb::RI_LOT_START_GAP_TIME
        ) {
            $isInGapTime = $this->getTimeoutHelper()->isInGapTime($rtbCurrent);
            if (!$isInGapTime) {
                $justEnded = true;
            }
        }
        return $justEnded;
    }

    /**
     * Process switching from "Lot Start Gap Time" interval to "Extend Time" interval
     */
    protected function switchGapToExtend(): void
    {
        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->getAuction());
        $rtbCurrent->RunningInterval = Constants\Rtb::RI_EXTEND_TIME;
        $this->getRtbCurrentWriteRepository()->saveWithSystemModifier($rtbCurrent);
        if (!$this->getAuction()->AllowBiddingDuringStartGap) {
            $this->enableBiddingAfterGapTime();
        }
    }

    /**
     * Send responses, that enable bidding
     */
    protected function enableBiddingAfterGapTime(): void
    {
        // We could use Sync responses also, but it causes more complicated js update logic
        /** @var ResetRunningLotCountdown $command */
        $command = $this->initCommand(ResetRunningLotCountdown::new());
        log_debug(fn() => "Enable bidding, because of switching from Lot Start Gap time to Extend time"
            . composeSuffix(['a' => $this->getAuctionId(), 'li' => $command->getRtbCurrent()->LotItemId])
        );
        $responses = $command->getResponses();
        $this->getHybridResponseSender()->send($responses, $this->getAuctionId());
    }

    /**
     * Check, if running lot is playing
     * @return bool
     */
    protected function isRunningLotActive(): bool
    {
        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->getAuction());
        return $rtbCurrent->isStartedLot();
    }

    /**
     * Check if lot time ended
     * @return bool
     */
    protected function isLotEnded(): bool
    {
        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->getAuction());
        // Add 1 second to closing delay, because we steal 1 second in Extend Time period for rendering 0 seconds left output on button
        $closingDelay = $this->cfg()->get('core->auction->hybrid->closingDelay') + 1;
        $lotEndDate = null;
        if ($rtbCurrent->LotEndDate) {
            $lotEndDate = clone $rtbCurrent->LotEndDate;
            $lotEndDate->add(new DateInterval('PT' . $closingDelay . 'S'));
        }
        $isLotEnded = !$rtbCurrent->PauseDate
            && $lotEndDate
            && $lotEndDate < $this->getCurrentDateUtc();
        if ($isLotEnded) {
            log_trace(fn() => "Lot ended" . composeSuffix(['a' => $this->getAuctionId(), 'li' => $rtbCurrent->LotItemId]));
        }
        return $isLotEnded;
    }

    /**
     * Do lot closing action (sell, pass or initiate bidder# entering)
     */
    protected function closeLot(): void
    {
        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->getAuction());
        $auctionLot = $this->getAuctionLotLoader()->load($rtbCurrent->LotItemId, $this->getAuctionId());
        if (
            $auctionLot
            && $this->canSell($auctionLot)
        ) {
            if ($this->isFloorBidder()) {
                $enabledUserIds = $this->getUserIdsWithEnabledEnterFloorNo();
                if ($enabledUserIds) {
                    $this->showEnterBidderNum($enabledUserIds);
                } else {
                    $this->sell($auctionLot);
                }
            } else {
                $this->sell($auctionLot);
            }
        } else {
            $this->pass();
        }
    }

    /**
     * Iterate over connected Clerk Consoles and finds users with "Enter Floor#" enabled
     * It is enabled by default, so if we don't store value for user (in rtb_current.enter_floor_no),
     * then we consider user has enabled option
     * @return array
     */
    protected function getUserIdsWithEnabledEnterFloorNo(): array
    {
        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->getAuction());
        $userIds = $this->getConnectedAdminUserIds();
        $enterFloorNoOptions = new JsonArray($rtbCurrent->EnterFloorNo);
        $enabledUserIds = [];
        foreach ($userIds as $userId) {
            $isEnabled = $enterFloorNoOptions->get($userId);
            if ($isEnabled === null) {  // if not set,
                $isEnabled = true;      // then true by default
            }
            if ($isEnabled) {
                $enabledUserIds[] = $userId;
            }
        }
        return $enabledUserIds;
    }

    /**
     * Check if lot can be sold
     * @param AuctionLotItem|null $auctionLot
     * @return bool
     */
    protected function canSell(?AuctionLotItem $auctionLot = null): bool
    {
        $canSell = false;
        if (!$auctionLot) {
            $canSell = false;
        } elseif ($auctionLot->isActiveOrUnsold()) {
            $currentBid = $this->createBidTransactionLoader()->loadById($auctionLot->CurrentBidId);
            if ($currentBid) {
                $lotItem = $this->getLotItemLoader()->load($currentBid->LotItemId);
                if (!$lotItem) {
                    $logInfo = composeSuffix(['li' => $currentBid->LotItemId, 'a' => $auctionLot->AuctionId]);
                    log_error("Available lot item not found for selling in hybrid processor" . $logInfo);
                    return false;
                }
                $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($auctionLot->LotItemId, $auctionLot->AuctionId);
                $hammerPrice = $auctionLot->multiplyQuantityEffectively($currentBid->Bid, $quantityScale);
                $canSell = Floating::lteq($lotItem->ReservePrice, $hammerPrice);
            }
        }
        if ($canSell) {
            log_trace(static fn() => "Lot can be sold"
                . composeSuffix(['li' => $auctionLot->LotItemId, 'a' => $auctionLot->AuctionId])
            );
        }
        return $canSell;
    }

    /**
     * Check if lot is sold to floor bidder
     * @return bool
     */
    protected function isFloorBidder(): bool
    {
        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->getAuction());
        $highBidUserId = $this->createHighBidDetector()->detectUserId($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $isFloor = !$highBidUserId;
        log_trace(static fn() => "Current lot is"
            . ($isFloor ? " floor bidder" : composeSuffix(['u' => $highBidUserId, 'li' => $rtbCurrent->LotItemId, 'a' => $rtbCurrent->AuctionId]))
        );
        return $isFloor;
    }

    /**
     * Check if any Admin Clerk Console is connected to Rtbd and return user.id of Admin
     * @return int[]
     */
    protected function getConnectedAdminUserIds(): array
    {
        $editorUserIds = [];
        $clients = $this->getHybridResponseSender()->getClients();
        foreach ($clients as $client) {
            if (
                !$this->getRtbGeneralHelper()->checkSocketClient($client)
                || !$client->getRtbCommandController()
            ) {
                continue;
            }

            $rtbCommandController = $client->getRtbCommandController();
            if (
                $rtbCommandController instanceof AdminHybridController
                && $rtbCommandController->getAuctionId() === $this->getAuctionId()
            ) {
                $editorUserIds[] = $rtbCommandController->getEditorUserId();
            }
        }
        $editorUserIds = array_unique($editorUserIds);
        return $editorUserIds;
    }

    /**
     * Detect if pending action is available
     * @return bool
     */
    protected function isPendingAction(): bool
    {
        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->getAuction());
        $is = $rtbCurrent->PendingAction
            && $rtbCurrent->PendingActionDate;
        return $is;
    }

    /**
     * Run command for pending action
     */
    protected function processPendingAction(): void
    {
        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->getAuction());
        if (!$rtbCurrent->PendingActionDate) {
            log_error(
                "PendingActionDate not set in rtb current state"
                . composeSuffix(['a' => $this->getAuctionId(), 'li' => $rtbCurrent->LotItemId])
            );
            return;
        }
        $timeout = $this->getSettingsManager()->get(Constants\Setting::PENDING_ACTION_TIMEOUT_HYBRID, $this->getAuction()->AccountId);
        $pendingActionEndDate = clone $rtbCurrent->PendingActionDate;
        $pendingActionEndDate->add(new DateInterval('PT' . $timeout . 'S'));
        if ($pendingActionEndDate < $this->getCurrentDateUtc()) {
            $logInfo = ['a' => $this->getAuctionId(), 'li' => $rtbCurrent->LotItemId];
            if ($rtbCurrent->PendingAction === Constants\Rtb::PA_ENTER_BIDDER_NUM) {
                log_info(
                    "Timeout exceeded for pending action (Enter Bidder #). "
                    . "Continue to sell running lot to floor bidder" . composeSuffix($logInfo)
                );
                $auctionLot = $this->getAuctionLotLoader()->load($rtbCurrent->LotItemId, $this->getAuctionId());
                if (
                    $auctionLot
                    && $this->canSell($auctionLot)
                ) {
                    $this->sell($auctionLot);
                }
            } elseif ($rtbCurrent->PendingAction === Constants\Rtb::PA_SELECT_BUYER_BY_AGENT) {
                $logInfo['bu'] = $rtbCurrent->BuyerUser;
                log_info(
                    "Timeout exceeded for pending action (Buyer Selection by Agent). "
                    . "Continue selling running lot to Agent" . composeSuffix($logInfo)
                );
                /** @var SellBuyer $command */
                $command = $this->initCommand(SellBuyer::new());
                $command->setBuyerUserId(0);
                $command->execute();
                $responses = $command->getResponses();
                $this->getHybridResponseSender()->send($responses, $this->getAuctionId());
            } elseif ($rtbCurrent->PendingAction === Constants\Rtb::PA_SELECT_GROUPED_LOTS) {
                $logInfo['gu'] = $rtbCurrent->GroupUser;
                log_info(
                    "Timeout exceeded for pending action (Grouped Lots Selection). "
                    . "Continue to sell running lot" . composeSuffix($logInfo)
                );
                $handler = SellLotsHybridClerkHandler::new()->construct(
                    SellLotsCommand::new()->constructToOnlySellRunningLot()
                );
                /** @var SellLotsHybridClerkHandler $handler */
                $handler = $this->initCommand($handler);
                $handler->execute();
                $responses = $handler->getResponses();
                $this->getHybridResponseSender()->send($responses, $this->getAuctionId());
            }
        }
    }

    /**
     * Send command for showing Enter Bidder# dialog
     * @param array $userIds
     */
    protected function showEnterBidderNum(array $userIds): void
    {
        if (!$userIds) {
            return;
        }

        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->getAuction());
        /** @var EnterBidderNumber $command */
        $command = $this->initCommand(EnterBidderNumber::new());
        $command->execute();

        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_ENTER_BIDDER_NUM_S,
            Constants\Rtb::RES_DATA => [],
        ];
        $responseJson = json_encode($response);
        foreach ($userIds as $userId) {
            $individualResponse = [
                Constants\Rtb::RES_SA_USER_ID => $userId,
                Constants\Rtb::RES_SA_AUCTION_ID => $this->getAuctionId(),
                Constants\Rtb::RES_SA_MESSAGE => $responseJson,
            ];
            $responses[Constants\Rtb::RT_SIMULT_INDIVIDUAL] = $individualResponse;
            $this->getHybridResponseSender()->send($responses, $this->getAuctionId());
        }
        log_debug(fn() => "Requested Enter Bidder# by Admin Clerk"
            . composeSuffix(['a' => $this->getAuctionId(), 'li' => $rtbCurrent->LotItemId, 'u' => implode(', ', $userIds)])
        );
    }

    /**
     * Sell lot
     * @param AuctionLotItem $auctionLot
     */
    protected function sell(AuctionLotItem $auctionLot): void
    {
        $currentBid = $this->createBidTransactionLoader()->loadById($auctionLot->CurrentBidId);
        if (!$currentBid) {
            log_error(
                "Available current bid not found, when selling lot via hybrid rtbd processor"
                . composeSuffix(['bt' => $auctionLot->CurrentBidId, 'ali' => $auctionLot->Id])
            );
            return;
        }
        /** @var SellLot $command */
        $command = $this->initCommand(SellLot::new());
        $command->setWinningBidderIdentifier((string)$currentBid->UserId);
        $command->execute();
        $responses = $command->getResponses();
        $this->getHybridResponseSender()->send($responses, $this->getAuctionId());
    }

    /**
     * Pass lot
     */
    protected function pass(): void
    {
        /** @var PassLot $command */
        $command = $this->initCommand(PassLot::new());
        $command->execute();
        $responses = $command->getResponses();
        $this->getHybridResponseSender()->send($responses, $this->getAuctionId());
    }

    /**
     * Check if lot has placed bid, that should be accepted automatically
     * @return bool
     */
    protected function hasBid(): bool
    {
        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->getAuction());
        $hasBid = $rtbCurrent->NewBidBy > 0;
        if (!$hasBid) {
            $hasAbsenteeBids = $this->getAbsenteeBidExistenceChecker()
                ->existForLot($rtbCurrent->LotItemId, $this->getAuctionId());
            if (
                $hasAbsenteeBids
                && $this->placeAbsenteeBid()
            ) {
                $hasBid = true;
            }
        }

        if ($hasBid) {
            log_trace(fn() => "We have bid!" . composeSuffix(
                    [
                        'a' => $this->getAuctionId(),
                        'li' => $rtbCurrent->LotItemId,
                        'is absentee' => (int)$rtbCurrent->AbsenteeBid,
                        'ask' => $rtbCurrent->AskBid
                    ]
                ));
        }
        return $hasBid;
    }

    /**
     * Place absentee bid
     * @return bool
     */
    protected function placeAbsenteeBid(): bool
    {
        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->getAuction());
        $newBidBy = $rtbCurrent->NewBidBy;
        $rtbCurrent = $this->createRtbStateUpdater()->update($rtbCurrent, $this->getAuction()->AccountId, $this->getViewLanguageId());
        $this->getRtbCurrentWriteRepository()->saveWithSystemModifier($rtbCurrent);
        $bidPlaced = $newBidBy !== $rtbCurrent->NewBidBy;
        return $bidPlaced;
    }

    /**
     * Run command for placed bid accepting
     */
    protected function acceptBid(): void
    {
        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->getAuction());
        /** @var AcceptBid $command */
        $command = $this->initCommand(AcceptBid::new());
        $bidByHash = $this->createUserHashGenerator()->generate($rtbCurrent->NewBidBy, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $command
            ->setAskingBid($rtbCurrent->AskBid)
            ->setBidByHash($bidByHash)
            ->enableConfirmBuy(false);
        $command->execute();
        $responses = $command->getResponses();
        $this->getHybridResponseSender()->send($responses, $this->getAuctionId());
    }

    /**
     * Detect if Fair Warning message should be send and send it
     */
    protected function processFairMessage(): void
    {
        $rtbCurrent = $this->getRtbGeneralHelper()->loadRtbCurrentOrCreate($this->getAuction());
        $secondsLeft = null;
        if ($rtbCurrent->LotEndDate) {
            $secondsLeft = $rtbCurrent->LotEndDate->getTimestamp() - $this->getCurrentDateUtc()->getTimestamp();
        }
        if (
            $rtbCurrent->isStartedOrPausedLot()
            && $this->getAuction()->isStarted()
            && $secondsLeft !== null
            && $secondsLeft !== $rtbCurrent->FairWarningSec
        ) {
            $messages = $this->createHybridFairWarningLoader()->getWarnings($this->getAuction()->AccountId);
            $secFound = null;
            foreach ($messages as $sec => $msg) {
                $sec = (int)$sec;
                if (
                    $sec >= $secondsLeft
                    && $sec <= $secondsLeft + 1
                ) // fair message time shouldn't be more than 1 second than current seconds left
                {
                    if ($rtbCurrent->FairWarningSec !== $sec) {
                        $secFound = $sec;
                    }
                    break;
                }
            }
            if ($secFound !== null) {
                $message = $messages[$secFound];
                $rtbCurrent->FairWarningSec = $secFound;
                $this->getRtbCurrentWriteRepository()->saveWithSystemModifier($rtbCurrent);
                /** @var SendFairWarning $command */
                $command = $this->initCommand(SendFairWarning::new());
                $command->setMessage($message);
                $command->execute();
                $responses = $command->getResponses();
                $this->getHybridResponseSender()->send($responses, $this->getAuctionId());
            }
        }
    }

    /**
     * Check if auction still has active lots (not sold/unsold)
     * @return bool
     */
    protected function hasActiveLots(): bool
    {
        return $this->activeCount > 0;
    }

    /**
     * Stop auction
     */
    protected function stopAuction(): void
    {
        /** @var StopAuction $command */
        $command = $this->initCommand(StopAuction::new());
        $command->execute();
        $responses = $command->getResponses();
        $this->getHybridResponseSender()->send($responses, $this->getAuctionId());
    }

    /**
     * @return Logger
     */
    protected function getLogger(): Logger
    {
        $logger = Logger::new()->setAuctionId($this->getAuctionId());
        return $logger;
    }

    protected function getViewLanguageId(): int
    {
        if ($this->viewLanguageId === null) {
            $this->viewLanguageId = (int)$this->getSettingsManager()
                ->get(Constants\Setting::VIEW_LANGUAGE, $this->getAuction()->AccountId);
        }
        return $this->viewLanguageId;
    }

    public function setViewLanguageId(int $viewLanguageId): static
    {
        $this->viewLanguageId = $viewLanguageId;
        return $this;
    }
}
