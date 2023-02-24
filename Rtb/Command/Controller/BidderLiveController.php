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

namespace Sam\Rtb\Command\Controller;

use Sam\Application\RequestParam\ParamFetcherForRtbd;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Command\Concrete\Live\AcceptLotChanges;
use Sam\Rtb\Command\Concrete\Live\DropInterest;
use Sam\Rtb\Command\Concrete\Live\Interest;
use Sam\Rtb\Command\Concrete\Live\PlaceBid;
use Sam\Rtb\Command\Concrete\Live\SellBuyer;
use Sam\Rtb\Command\Concrete\Live\SendMessage;
use Sam\Rtb\Command\Concrete\Live\Sync;
use Sam\Rtb\Command\Concrete\Ping\PingCommand;
use Sam\Rtb\Command\Concrete\Ping\PingHandler;
use Sam\Rtb\Command\Concrete\ReversePing\ReversePingCommand;
use Sam\Rtb\Command\Concrete\ReversePing\ReversePingHandler;
use Sam\Rtb\Command\Concrete\SellLots\Base\SellLotsCommand;
use Sam\Rtb\Command\Concrete\SellLots\Live\SellLotsLiveBidderHandler;
use Sam\Rtb\Server\Daemon\RtbDaemonEvent;
use Sam\Rtb\Server\Daemon\RtbDaemonLegacy;

/**
 * Class BidderLiveController
 */
class BidderLiveController extends ControllerBase
{
    use AuctionBidderLoaderAwareTrait;
    use BidderNumPaddingAwareTrait;

    public string $bidderNum = '';

    /** @var string[] */
    protected array $commandHandlerMethodNames = [
        Constants\Rtb::CMD_SYNC_Q => 'sync',
        Constants\Rtb::CMD_PLACE_Q => 'placeBid',
        Constants\Rtb::CMD_ACCEPT_LOT_CHANGES_Q => 'acceptLotChanges',
        Constants\Rtb::CMD_SELL_LOTS_Q => 'sellLots',
        Constants\Rtb::CMD_SEND_MESSAGE_Q => 'sendMessage',
        Constants\Rtb::CMD_SELECT_BUYER_Q => 'sellBuyer',
        Constants\Rtb::CMD_INTEREST_Q => 'interest',
        Constants\Rtb::CMD_DROP_INTEREST_Q => 'dropInterest',
        Constants\Rtb::CMD_PING_Q => 'ping',
        Constants\Rtb::CMD_REVERSE_PING_Q => 'reversePing',
    ];

    /**
     * Get a BidderLiveController instance
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param int|null $editorUserId
     * @param int $userType
     * @param string $sessionId
     * @param string $remoteHost
     * @param int $remotePort
     * @param RtbDaemonLegacy|RtbDaemonEvent $daemon
     */
    public function init(
        int $auctionId,
        ?int $editorUserId,
        int $userType,
        string $sessionId,
        string $remoteHost,
        int $remotePort,
        RtbDaemonLegacy|RtbDaemonEvent $daemon
    ): void {
        parent::init($auctionId, $editorUserId, $userType, $sessionId, $remoteHost, $remotePort, $daemon);

        $auctionBidder = $this->getAuctionBidderLoader()->load($this->getEditorUserId(), $this->getAuctionId(), true);
        if ($auctionBidder) {
            $this->bidderNum = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
        }
    }

    /**
     * @param CommandBase $command
     * @param object $data
     * @return CommandBase
     */
    protected function initCommand(CommandBase $command, object $data): CommandBase
    {
        $command = parent::initCommand($command, $data);
        $command->setUserType(Constants\Rtb::UT_BIDDER);
        $command->setBidderNum($this->bidderNum);
        return $command;
    }

    /**
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
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function placeBid(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = PlaceBid::new();
        /** @var PlaceBid $command */
        $command = $this->initCommand($command, $data);
        $command->setAskingBid($paramFetcher->getFloatPositive(Constants\Rtb::REQ_ASKING_BID));
        $command->setReferrer($paramFetcher->getString(Constants\Rtb::REQ_REFERRER));
        $command->setLotChanges($paramFetcher->getString(Constants\Rtb::REQ_LOT_CHANGES));
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function acceptLotChanges(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = AcceptLotChanges::new();
        /** @var AcceptLotChanges $command */
        $command = $this->initCommand($command, $data);
        $command->setLotChanges($paramFetcher->getString(Constants\Rtb::REQ_LOT_CHANGES));
        $command->setLotChangesStatus($paramFetcher->getString(Constants\Rtb::REQ_IS_LOT_CHANGES));
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    protected function sellLots(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = SellLotsCommand::new()->construct(
            $paramFetcher->getIntPositiveOrZero(Constants\Rtb::REQ_GROUP_LOT_QUANTITY),
            $paramFetcher->getString(Constants\Rtb::REQ_LOT_ITEM_IDS)
        );
        $handler = SellLotsLiveBidderHandler::new()->construct($command);
        /** @var SellLotsLiveBidderHandler $handler */
        $handler = $this->initCommand($handler, $data);
        $handler->execute();
        $responses = $handler->getResponses();
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
     */
    protected function interest(object $data): array
    {
        $command = Interest::new();
        /** @var Interest $command */
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
    protected function dropInterest(object $data): array
    {
        $command = DropInterest::new();
        /** @var DropInterest $command */
        $command = $this->initCommand($command, $data);
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
