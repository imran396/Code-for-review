<?php
/**
 * Custom methods can be used there or in customized class (SAM-1570)
 *
 * Optional method called when rendering the custom lot item field value
 * param LotItemCustField $lotCustomField the custom lot item field object
 * param LotItemCustData $lotCustomData the custom lot item field data
 * param integer $auctionId auction.id to which lot is assigned
 * return string the rendered value
 * public function LotCustomField_{Field name}_Render(LotItemCustField $lotCustomField, LotItemCustData $lotCustomData, $auctionId)
 *
 * {Field name} - Camel cased name of custom field (see TextTransformer::toCamelCase())
 */

/**
 * Class AdminLiveController
 */

namespace Sam\Rtb\Command\Controller;

use Sam\Application\RequestParam\ParamFetcherForRtbd;
use Sam\Core\Constants;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Command\Concrete\Live\AcceptBid;
use Sam\Rtb\Command\Concrete\Live\CancelEnterBidderNumber;
use Sam\Rtb\Command\Concrete\Live\ChangeAskingBid;
use Sam\Rtb\Command\Concrete\Live\ChangeDefaultIncrement;
use Sam\Rtb\Command\Concrete\Live\ChangeIncrement;
use Sam\Rtb\Command\Concrete\Live\ClearLog;
use Sam\Rtb\Command\Concrete\Live\DisableBid;
use Sam\Rtb\Command\Concrete\Live\EnableAutoStart;
use Sam\Rtb\Command\Concrete\Live\EnableDecrement;
use Sam\Rtb\Command\Concrete\Live\EnableEnterFloorNo;
use Sam\Rtb\Command\Concrete\Live\EnterBidderNumber;
use Sam\Rtb\Command\Concrete\Live\GoToLot;
use Sam\Rtb\Command\Concrete\Live\GroupLots;
use Sam\Rtb\Command\Concrete\Live\InitBidCountdown;
use Sam\Rtb\Command\Concrete\Live\PassLot;
use Sam\Rtb\Command\Concrete\Live\PauseAuction;
use Sam\Rtb\Command\Concrete\Live\PauseLot;
use Sam\Rtb\Command\Concrete\Live\PlaceAbsenteePriorityBid;
use Sam\Rtb\Command\Concrete\Live\PlaceFloorBid;
use Sam\Rtb\Command\Concrete\Live\PlaceFloorPriorityBid;
use Sam\Rtb\Command\Concrete\Live\ResetIncrement;
use Sam\Rtb\Command\Concrete\Live\ResetLot;
use Sam\Rtb\Command\Concrete\Live\SellBuyer;
use Sam\Rtb\Command\Concrete\Live\SellLot;
use Sam\Rtb\Command\Concrete\Live\SendFairWarning;
use Sam\Rtb\Command\Concrete\Live\SendMessage;
use Sam\Rtb\Command\Concrete\Live\StartAuction;
use Sam\Rtb\Command\Concrete\Live\StartLot;
use Sam\Rtb\Command\Concrete\Live\StopAuction;
use Sam\Rtb\Command\Concrete\Live\Sync;
use Sam\Rtb\Command\Concrete\Live\SyncInterest;
use Sam\Rtb\Command\Concrete\Live\UndoSnapshot;
use Sam\Rtb\Command\Concrete\Live\UngroupLots;
use Sam\Rtb\Command\Concrete\Ping\PingCommand;
use Sam\Rtb\Command\Concrete\Ping\PingHandler;
use Sam\Rtb\Command\Concrete\ReversePing\ReversePingCommand;
use Sam\Rtb\Command\Concrete\ReversePing\ReversePingHandler;
use Sam\Rtb\Command\Concrete\SellLots\Base\SellLotsCommand;
use Sam\Rtb\Command\Concrete\SellLots\Live\SellLotsLiveClerkHandler;
use Sam\Rtb\Messenger;

/**
 * Class AdminLiveController
 */
class AdminLiveController extends ControllerBase
{
    /** @var string[] */
    protected array $commandHandlerMethodNames = [
        Constants\Rtb::CMD_SYNC_Q => 'sync',
        Constants\Rtb::CMD_START_Q => 'startAuction',
        Constants\Rtb::CMD_PAUSE_Q => 'pauseAuction',
        Constants\Rtb::CMD_CHANGE_ASKING_BID_Q => 'changeAskingBid',
        Constants\Rtb::CMD_CHANGE_INCREMENT_Q => 'changeIncrement',
        Constants\Rtb::CMD_CHANGE_DEFAULT_INCREMENT_Q => 'changeDefaultIncrement',
        Constants\Rtb::CMD_ENABLE_DECREMENT_Q => 'enableDecrement',
        Constants\Rtb::CMD_RESET_INCREMENT_Q => 'resetIncrement',
        Constants\Rtb::CMD_FAIR_Q => 'sendFairWarning',
        Constants\Rtb::CMD_ACCEPT_Q => 'acceptBid',
        Constants\Rtb::CMD_FLOOR_Q => 'placeFloorBid',
        Constants\Rtb::CMD_FLOOR_PRIORITY_Q => 'placeFloorPriorityBid',
        Constants\Rtb::CMD_ABSENTEE_PRIORITY_Q => 'placeAbsenteePriorityBid',
        Constants\Rtb::CMD_GO_TO_LOT_Q => 'goToLot',
        Constants\Rtb::CMD_SEND_MESSAGE_Q => 'sendMessage',
        Constants\Rtb::CMD_SOLD_Q => 'sell',
        Constants\Rtb::CMD_UNDO_SNAPSHOT_Q => 'undoSnapshot',
        Constants\Rtb::CMD_PASS_Q => 'pass',
        Constants\Rtb::CMD_START_LOT_Q => 'startLot',
        Constants\Rtb::CMD_PAUSE_LOT_Q => 'pauseLot',
        Constants\Rtb::CMD_STOP_Q => 'stopAuction',
        Constants\Rtb::CMD_SELL_LOTS_Q => 'sellLots',
        Constants\Rtb::CMD_CLEAR_AUCTION_GAME_LOG_Q => 'clearAuctionGameLog',
        Constants\Rtb::CMD_CLEAR_CHAT_LOG_Q => 'clearChatLog',
        Constants\Rtb::CMD_RESET_LOT_Q => 'resetLot',
        Constants\Rtb::CMD_GROUP_LOT_Q => 'groupLots',
        Constants\Rtb::CMD_UNGROUP_LOT_Q => 'ungroupLots',
        Constants\Rtb::CMD_SELECT_BUYER_Q => 'sellBuyer',
        Constants\Rtb::CMD_ENTER_BIDDER_NUM_Q => 'enterBidderNumber',
        Constants\Rtb::CMD_CANCEL_ENTER_BIDDER_NUM_Q => 'cancelEnterBidderNumber',
        Constants\Rtb::CMD_DISABLE_BID_Q => 'disableBid',
        Constants\Rtb::CMD_SYNC_INTEREST_Q => 'syncInterest',
        Constants\Rtb::CMD_INIT_BID_COUNTDOWN_Q => 'initBidCountdown',
        Constants\Rtb::CMD_ENABLE_AUTO_START_Q => 'enableAutoStart',
        Constants\Rtb::CMD_ENABLE_ENTER_FLOOR_NO_Q => 'enableEnterFloorNo',
        Constants\Rtb::CMD_PING_Q => 'ping',
        Constants\Rtb::CMD_REVERSE_PING_Q => 'reversePing',
    ];

    /**
     * Get a AdminLiveController instance
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param CommandBase $command
     * @param object $data
     * @return CommandBase
     */
    protected function initCommand(CommandBase $command, object $data): CommandBase
    {
        $command = parent::initCommand($command, $data);
        $command->setUserType(Constants\Rtb::UT_CLERK);
        return $command;
    }

    /**
     * Initialize or refresh current rtb state.
     * Return response with data for synchronization of rtb console view.
     * @param object $data
     * @return array
     */
    protected function sync(object $data): array
    {
        $command = Sync::new();
        /** @var Sync $command */
        $command = $this->initCommand($command, $data);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * Handle click on "Start auction" button
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function startAuction(object $data): array
    {
        $command = StartAuction::new();
        /** @var StartAuction $command */
        $command = $this->initCommand($command, $data);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function pauseAuction(object $data): array
    {
        $command = PauseAuction::new();
        /** @var PauseAuction $command */
        $command = $this->initCommand($command, $data);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function stopAuction(object $data): array
    {
        $command = StopAuction::new();
        /** @var StopAuction $command */
        $command = $this->initCommand($command, $data);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function startLot(object $data): array
    {
        $command = StartLot::new();
        /** @var StartLot $command */
        $command = $this->initCommand($command, $data);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function pauseLot(object $data): array
    {
        $command = PauseLot::new();
        /** @var PauseLot $command */
        $command = $this->initCommand($command, $data);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function acceptBid(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = AcceptBid::new();
        /** @var AcceptBid $command */
        $command = $this->initCommand($command, $data);
        $command->setReferrer($paramFetcher->getString(Constants\Rtb::REQ_REFERRER));
        $command->setAskingBid($paramFetcher->getFloatPositive(Constants\Rtb::REQ_ASKING_BID));
        $command->setBidByHash($paramFetcher->getString(Constants\Rtb::REQ_PENDING_BID_USER_HASH));
        $command->enableConfirmBuy($paramFetcher->getBool(Constants\Rtb::REQ_IS_CONFIRM_BUY) ?? false);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function placeFloorBid(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = PlaceFloorBid::new();
        /** @var PlaceFloorBid $command */
        $command = $this->initCommand($command, $data);
        $command->setAskingBid($paramFetcher->getFloatPositive(Constants\Rtb::REQ_ASKING_BID));
        $command->setCurrentBid($paramFetcher->getFloatPositiveOrZero(Constants\Rtb::REQ_CURRENT_BID));
        $command->enableConfirmBuy($paramFetcher->getBool(Constants\Rtb::REQ_IS_CONFIRM_BUY) ?? false);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    // SAM-1371 - It acts similar to, if we would "UndoQ" and then "FloorQ"

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function placeFloorPriorityBid(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = PlaceFloorPriorityBid::new();
        /** @var PlaceFloorPriorityBid $command */
        $command = $this->initCommand($command, $data);
        $command->enableConfirmBuy($paramFetcher->getBool(Constants\Rtb::REQ_IS_CONFIRM_BUY) ?? false);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * Place the absentee bid for the high absentee bidder at the current bid (SAM-1742)
     * It handles click on "Absentee Priority" button. It is enabled, if the max absentee bid is equal or higher
     * than the current bid and the current high bidder is not the absentee bidder.
     *
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function placeAbsenteePriorityBid(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = PlaceAbsenteePriorityBid::new();
        /** @var PlaceAbsenteePriorityBid $command */
        $command = $this->initCommand($command, $data);
        $command->enableConfirmBuy($paramFetcher->getBool(Constants\Rtb::REQ_IS_CONFIRM_BUY) ?? false);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function changeAskingBid(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = ChangeAskingBid::new();
        /** @var ChangeAskingBid $command */
        $command = $this->initCommand($command, $data);
        $command->setAskingBid($paramFetcher->getFloatPositive(Constants\Rtb::REQ_ASKING_BID));
        $command->setNewIncrement($paramFetcher->getFloat(Constants\Rtb::REQ_INCREMENT_NEW));
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function changeIncrement(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = ChangeIncrement::new();
        /** @var ChangeIncrement $command */
        $command = $this->initCommand($command, $data);
        $command->setNewIncrement($paramFetcher->getFloatPositive(Constants\Rtb::REQ_INCREMENT_NEW));
        $command->setCurrentBid($paramFetcher->getFloatPositiveOrZero(Constants\Rtb::REQ_CURRENT_BID));
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function changeDefaultIncrement(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = ChangeDefaultIncrement::new();
        /** @var ChangeDefaultIncrement $command */
        $command = $this->initCommand($command, $data);
        $command->setDefaultIncrement($paramFetcher->getFloatPositive(Constants\Rtb::REQ_INCREMENT_DEFAULT));
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function enableDecrement(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = EnableDecrement::new();
        /** @var EnableDecrement $command */
        $command = $this->initCommand($command, $data);
        $command->enableEnabled($paramFetcher->getBool(Constants\Rtb::REQ_IS_DECREMENT) ?? false);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function resetIncrement(object $data): array
    {
        $command = ResetIncrement::new();
        /** @var ResetIncrement $command */
        $command = $this->initCommand($command, $data);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function goToLot(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = GoToLot::new();
        /** @var GoToLot $command */
        $command = $this->initCommand($command, $data);
        $command->setGoToLotId($paramFetcher->getString(Constants\Rtb::REQ_GO_TO_LOT_ITEM_ID));
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     */
    protected function pass(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = PassLot::new();
        /** @var PassLot $command */
        $command = $this->initCommand($command, $data);
        $command->setGeneralNote($paramFetcher->getString(Constants\Rtb::REQ_GENERAL_NOTE));
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     */
    protected function sell(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = SellLot::new();
        /** @var SellLot $command */
        $command = $this->initCommand($command, $data);
        $command->setWinningBidderIdentifier($paramFetcher->getString(Constants\Rtb::REQ_WINNING_BIDDER_USER_ID));
        $command->setGeneralNote($paramFetcher->getString(Constants\Rtb::REQ_GENERAL_NOTE));
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * Handle confirming additional lots sold in group.
     * When we sold grouped of lots (Choice, Qty), system shows "Confirm Additional Lots" dialog.
     * Method marks checked lots as sold and assign winning info to them. It updates rtb group info and current state.
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function sellLots(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $isOnlySellRunningLot = $paramFetcher->getBool(Constants\Rtb::REQ_ONLY_SELL_RUNNING_LOT) ?? false;
        if ($isOnlySellRunningLot) {
            $command = SellLotsCommand::new()->constructToOnlySellRunningLot();
        } else {
            $command = SellLotsCommand::new()->construct(
                $paramFetcher->getIntPositiveOrZero(Constants\Rtb::REQ_GROUP_LOT_QUANTITY),
                $paramFetcher->getString(Constants\Rtb::REQ_LOT_ITEM_IDS)
            );
        }
        $handler = SellLotsLiveClerkHandler::new()->construct($command);
        /** @var SellLotsLiveClerkHandler $handler */
        $handler = $this->initCommand($handler, $data);
        $handler->execute();
        $responses = $handler->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function resetLot(object $data): array
    {
        $command = ResetLot::new();
        /** @var ResetLot $command */
        $command = $this->initCommand($command, $data);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function clearAuctionGameLog(object $data): array
    {
        $command = ClearLog::new();
        /** @var ClearLog $command */
        $command = $this->initCommand($command, $data);
        $command->setTypes(
            [
                Messenger::MESSAGE_TYPE_ADMIN_HISTORY,
                Messenger::MESSAGE_TYPE_FRONT_HISTORY,
            ]
        );
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function clearChatLog(object $data): array
    {
        $command = ClearLog::new();
        /** @var ClearLog $command */
        $command = $this->initCommand($command, $data);
        $command->setTypes(
            [
                Messenger::MESSAGE_TYPE_ADMIN_CHAT,
                Messenger::MESSAGE_TYPE_FRONT_CHAT,
            ]
        );
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function groupLots(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = GroupLots::new();
        /** @var GroupLots $command */
        $command = $this->initCommand($command, $data);
        $command->setLotItemIds($paramFetcher->getArrayOfIntPositive(Constants\Rtb::REQ_LOT_ITEM_IDS));
        $command->setGroupType($paramFetcher->getString(Constants\Rtb::REQ_GROUP));
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function ungroupLots(object $data): array
    {
        $command = UngroupLots::new();
        /** @var UngroupLots $command */
        $command = $this->initCommand($command, $data);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function sellBuyer(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = SellBuyer::new();
        /** @var SellBuyer $command */
        $command = $this->initCommand($command, $data);
        $command->setBuyerUserId($paramFetcher->getIntPositiveOrZero(Constants\Rtb::REQ_WINNING_BUYER_USER_ID));
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function enterBidderNumber(object $data): array
    {
        $command = EnterBidderNumber::new();
        /** @var EnterBidderNumber $command */
        $command = $this->initCommand($command, $data);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function cancelEnterBidderNumber(object $data): array
    {
        $command = CancelEnterBidderNumber::new();
        /** @var CancelEnterBidderNumber $command */
        $command = $this->initCommand($command, $data);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * SAM-970
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function disableBid(object $data): array
    {
        $command = DisableBid::new();
        /** @var DisableBid $command */
        $command = $this->initCommand($command, $data);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnusedParameterInspection
     * @noinspection PhpUnused
     */
    protected function syncInterest(object $data): array
    {
        $command = SyncInterest::new();
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function undoSnapshot(object $data): array
    {
        $command = UndoSnapshot::new();
        /** @var UndoSnapshot $command */
        $command = $this->initCommand($command, $data);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function initBidCountdown(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = InitBidCountdown::new();
        /** @var InitBidCountdown $command */
        $command = $this->initCommand($command, $data);
        $command->setCountdownLabel($paramFetcher->getString(Constants\Rtb::REQ_BID_COUNTDOWN));
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     */
    protected function sendMessage(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = SendMessage::new();
        /** @var SendMessage $command */
        $command = $this->initCommand($command, $data);
        $command->setMessage($paramFetcher->getString(Constants\Rtb::REQ_MESSAGE));
        $command->setMessageId($paramFetcher->getIntPositive(Constants\Rtb::REQ_MESSAGE_ID));
        $command->setReceiverUserId($paramFetcher->getIntPositive(Constants\Rtb::REQ_RECEIVER_USER_ID));
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function sendFairWarning(object $data): array
    {
        $command = SendFairWarning::new();
        /** @var SendFairWarning $command */
        $command = $this->initCommand($command, $data);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function enableAutoStart(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = EnableAutoStart::new();
        /** @var EnableAutoStart $command */
        $command = $this->initCommand($command, $data);
        $command->enableAutoStart($paramFetcher->getBool(Constants\Rtb::REQ_LOT_AUTO_START) ?? false);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function enableEnterFloorNo(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = EnableEnterFloorNo::new();
        /** @var EnableEnterFloorNo $command */
        $command = $this->initCommand($command, $data);
        $command->enableEnterFloorNo($paramFetcher->getBool(Constants\Rtb::REQ_IS_ENTER_FLOOR_NO) ?? false);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * SAM-5739
     * @param object $data
     * @return array
     */
    public function ping(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = PingCommand::new()->construct(
            $paramFetcher->getString(Constants\Rtb::REQ_PING_TS)
        );
        $handler = PingHandler::new()->construct($command);
        /** @var PingHandler $handler */
        $handler = $this->initCommand($handler, $data);
        $handler->execute();
        $responses = $handler->getResponses();
        return $responses;
    }

    /**
     * SAM-5739
     * @param object $data
     * @return array
     */
    public function reversePing(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = ReversePingCommand::new()->construct(
            $paramFetcher->getFloat(Constants\Rtb::REQ_REVERSE_PING_TS)
        );
        $handler = ReversePingHandler::new()->construct($command);
        /** @var ReversePingHandler $handler */
        $handler = $this->initCommand($handler, $data);
        $handler->execute();
        $responses = $handler->getResponses();
        return $responses;
    }
}
