<?php

namespace Sam\Rtb\Command\Concrete\Base;

use AbsenteeBid;
use AuctionLotItem;
use BidTransaction;
use LotItem;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidder\BidderInfo\BidderInfoRendererAwareTrait;
use Sam\Bidding\AbsenteeBid\Detect\HighAbsenteeBidDetectorCreateTrait;
use Sam\Bidding\BidTransaction\Place\Live\LiveBidSaverCreateTrait;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Bid\BidUpdaterAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\State\History\HistoryServiceFactoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\BidTransaction\BidTransactionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Flag\UserFlaggingAwareTrait;

/**
 * Class PlaceAbsenteePriorityBid
 * @package Sam\Rtb\Command\Concrete\Base
 * @method AuctionLotItem getAuctionLot() - existence checked in checkRunningLot()
 * @method LotItem getLotItem() - existence checked in checkRunningLot()
 */
abstract class PlaceAbsenteePriorityBid extends CommandBase implements RtbCommandHelperAwareInterface
{
    use AuctionBidderHelperAwareTrait;
    use BidderInfoRendererAwareTrait;
    use BidUpdaterAwareTrait;
    use BidTransactionWriteRepositoryAwareTrait;
    use HighAbsenteeBidDetectorCreateTrait;
    use HighBidDetectorCreateTrait;
    use HistoryServiceFactoryCreateTrait;
    use LiveBidSaverCreateTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbRendererCreateTrait;
    use UserFlaggingAwareTrait;

    private const DENIED_BID_ACCEPTING_MESSAGE = 'Absentee priority bid has been denied';

    // Externally defined properties

    /**
     * @var bool
     */
    protected bool $isConfirmBuy = false;

    // Internally defined properties

    /**
     * This is absentee bid that describes target switching user.
     * This bid should be registered in `bid_transaction`
     */
    private ?AbsenteeBid $targetHighAbsentee = null;
    /**
     * Current running bid transaction, that should become stale after command.
     * It describes asking bid, that will be kept the same.
     */
    private ?BidTransaction $sourceBidTransaction = null;

    /**
     * Set and normalize to int
     * @param bool $confirmBuy
     * @return static
     */
    public function enableConfirmBuy(bool $confirmBuy): static
    {
        $this->isConfirmBuy = $confirmBuy;
        return $this;
    }

    public function execute(): void
    {
        /**
         * We perform some regular validations for rtb state and its correspondence to requested command.
         * Rtb state may become inconsistent, e.g. because high absentee or switching bid cannot be found
         * (their search conditions also checks their approved in auction bidders)
         * This failed invariants leads to Sync command response.
         */
        if (
            !$this->checkConsoleSync()
            || !$this->checkRunningLot()
            || !$this->checkAuction()
            || !$this->loadAndCheckSwitchingBids()
        ) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        $rtbCurrent = $this->getRtbCurrent();
        $lotItem = $this->getLotItem();
        $auctionLot = $this->getAuctionLot();
        $sourceAskingBid = $this->sourceBidTransaction->Bid;
        $targetUserId = $this->targetHighAbsentee->UserId;
        $sourceUserId = $this->sourceBidTransaction->UserId;

        if (
            $auctionLot->BuyNow
            && !$this->isConfirmBuy
            && $auctionLot->isAmongWonStatuses()
        ) {
            $responses = $this->getResponseHelper()->getResponseForBidConfirmationForSoldLot(
                $this->getAuctionId(),
                $lotItem,
                $targetUserId,
                $sourceAskingBid,
                Constants\Rtb::RES_BID_TO_ACCEPT
            );
            $this->setResponses($responses);
            return;
        }


        /**
         * Perform additional validations if running user isn't deleted, or removed from auction.
         * These seem to be rather redundant validations, because high absentee auction bidder is checked
         * in absentee bid detection query we did in $this->loadAndCheckSwitchingBids().
         */
        $success = true;
        $clarification = '';
        $targetAuctionBidder = $this->getAuctionBidderLoader()->load($targetUserId, $this->getAuctionId(), true);
        $isApproved = $this->getAuctionBidderHelper()->isApproved($targetAuctionBidder);
        $user = $this->getUserLoader()->load($targetUserId);
        $userFlag = $this->getUserFlagging()->detectFlagByUser($user, $this->getAuction()->AccountId);
        if (!$targetAuctionBidder) {
            $success = false;
            $clarification = "User has been removed from auction";
        } elseif (!$isApproved) {
            $success = false;
            $clarification = "User is not approved in auction";
        } elseif (!$user) {
            $success = false;
            $clarification = "User has been deleted";
        } elseif (in_array($userFlag, [Constants\User::FLAG_BLOCK, Constants\User::FLAG_NOAUCTIONAPPROVAL], true)) {
            $success = false;
            $clarification = sprintf('User is flagged by "%s"', Constants\User::FLAG_NAMES[$userFlag]);
        }

        if (!$success) {
            log_info(self::DENIED_BID_ACCEPTING_MESSAGE . ' - ' . $clarification . composeSuffix($this->getLogData()));
            $this->responseForDeniedBidAccepting($targetUserId, $clarification);
            return;
        }

        $historyService = $this->createHistoryServiceFactory()->create($rtbCurrent);
        $historyService->snapshot($rtbCurrent, Constants\Rtb::CMD_ABSENTEE_PRIORITY_Q, $this->detectModifierUserId());

        // Prepare values for absentee bid, instead of executing complete UndoQ action
        $rtbCurrent->AskBid = $sourceAskingBid;
        $rtbCurrent->NewBidBy = $targetUserId;
        $rtbCurrent->AbsenteeBid = true;
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
        // Register bid transaction for user of absentee bid
        $liveBidSaver = $this->createLiveBidSaver()
            ->setAmount($sourceAskingBid)
            ->setAuction($this->getAuction())
            ->setEditorUserId($this->detectModifierUserId())
            ->setLotItem($lotItem)
            ->setUser($user);
        $targetBidTransaction = $liveBidSaver->place();
        if (!$targetBidTransaction) {
            $clarification = $liveBidSaver->errorMessage();
            log_info(self::DENIED_BID_ACCEPTING_MESSAGE . ' - ' . $clarification . composeSuffix($this->getLogData()));
            $this->responseForDeniedBidAccepting($targetUserId, $clarification);
            return;
        }
        $targetBidTransaction->AbsenteeBid = true;
        $this->getBidTransactionWriteRepository()->saveWithModifier($targetBidTransaction, $this->detectModifierUserId());

        $rtbCurrent = $this->getBidUpdater()
            ->setRtbCurrent($rtbCurrent)
            ->setLotItem($this->getLotItem())
            ->setAuction($this->getAuction())
            ->update($this->detectModifierUserId(), $targetBidTransaction->UserId, $sourceUserId, $rtbCurrent->LotGroup);

        $bidderNum = $this->getBidderNumberPadding()->clear($targetAuctionBidder->BidderNum);
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $this->getLogger()->log(
            "Admin clerk set absentee priority bid by bidder {$bidderNum} ({$targetUserId}) "
            . "at {$sourceAskingBid} on lot {$lotNo} ({$lotItem->Id})"
        );

        $data = $this->getBidUpdater()->getData();

        // Message center
        $message = $this->translate('BIDDERCLIENT_ABSENTEE_PRIORITY');
        $price = ' ' . $this->getCurrency() . $this->getNumberFormatter()->formatMoney($sourceAskingBid);
        $langInternetBidder2 = $this->translate('BIDDERCLIENT_INTERNETBIDDER2');
        $bidderInfoForAdmin = $this->getBidderInfoRenderer()->renderForAdminRtb($targetBidTransaction->UserId, $this->getAuctionId());
        $adminMessage = $message . $price . ' ' . $langInternetBidder2 . ' ' . $bidderInfoForAdmin;
        $publicMessage = $message . $price . ' ' . $langInternetBidder2;
        $isHideBidderNumber = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::HIDE_BIDDER_NUMBER, $this->getAuction()->AccountId);
        if (!$isHideBidderNumber) {
            $bidderInfoForPublic = $this->getBidderInfoRenderer()->renderForPublicRtb($targetBidTransaction->UserId, $this->getAuctionId());
            $publicMessage .= ' ' . $bidderInfoForPublic;
        }

        $this->getMessenger()->createStaticHistoryMessage(
            $this->getAuctionId(),
            $this->createRtbRenderer()->renderAuctioneerMessage($adminMessage, $this->getAuction()),
            true
        );
        $this->getMessenger()->createStaticHistoryMessage(
            $this->getAuctionId(),
            $this->createRtbRenderer()->renderAuctioneerMessage($publicMessage, $this->getAuction())
        );

        $data[Constants\Rtb::RES_STATUS] = $adminMessage;
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;

        // Make auctioneer console response

        $data = array_replace(
            $data,
            $this->getResponseDataProducer()->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_AUCTIONEER)
        );
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

        // Make public consoles response

        $data[Constants\Rtb::RES_STATUS] = $publicMessage;
        if ($this->getDelayAfterBidAccepted() > 0) {
            $data[Constants\Rtb::RES_DELAY_AFTER_BID_ACCEPTED] = $this->getDelayAfterBidAccepted();
        }
        $data = $this->getResponseHelper()->removeSensitiveData($data);
        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S, Constants\Rtb::RES_DATA => $data];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;

        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $message
        );
        $this->setResponses($responses);
    }

    /**
     * @return bool
     */
    protected function loadAndCheckSwitchingBids(): bool
    {
        $lotItemId = $this->getLotItemId();
        $currentBidAmount = $this->createHighBidDetector()->detectAmount($lotItemId, $this->getAuctionId());
        $this->targetHighAbsentee = $this->createHighAbsenteeBidDetector()->detectFirstHighestByCurrentBid(
            $lotItemId,
            $this->getAuctionId(),
            $currentBidAmount,
            true
        );
        // Check if it has current bid
        $this->sourceBidTransaction = $this->createBidTransactionLoader()->loadLastActiveBid(
            $lotItemId,
            $this->getAuctionId()
        );

        $success = true;
        $clarification = '';
        if (!$this->sourceBidTransaction) {
            $success = false;
            $clarification = 'Last active bid transaction not found';
        } elseif (!$this->targetHighAbsentee) {
            $success = false;
            $clarification = 'First highest absentee bid not found';
        } elseif ($this->sourceBidTransaction->UserId === $this->targetHighAbsentee->UserId) {
            $success = false;
            $clarification = 'Current bid owner already is a high absentee bidder';
        }

        if (!$success) {
            log_warning(self::DENIED_BID_ACCEPTING_MESSAGE . ' - ' . $clarification . composeSuffix($this->getLogData()));
        }

        return $success;
    }

    /**
     * Only to force absentee bid if the auction has started
     * @return bool
     */
    protected function checkAuction(): bool
    {
        return $this->getAuction()->isStarted();
    }

    /**
     * @param int $bidByUserId
     * @param string $clarification
     */
    protected function responseForDeniedBidAccepting(int $bidByUserId, string $clarification): void
    {
        $message = $this->createRtbRenderer()->renderMessageForDeniedBidAccepting($bidByUserId, $clarification);
        $messageToFile = $message . "<br />\n"; // TODO: this line delimiting should be appended to message inside Messenger
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageToFile, true);
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageToFile);
        $responses = $this->getResponseHelper()->getResponseForDeniedBidAccepting($this->getRtbCurrent(), $message);
        $this->setResponses($responses);
    }

    /**
     * @return array
     */
    protected function getLogData(): array
    {
        $rtbCurrent = $this->getRtbCurrent();
        return [
            'li' => $rtbCurrent->LotItemId,
            'a' => $rtbCurrent->AuctionId,
            'u' => $rtbCurrent->NewBidBy,
            'ask' => $rtbCurrent->AskBid,
        ];
    }
}
