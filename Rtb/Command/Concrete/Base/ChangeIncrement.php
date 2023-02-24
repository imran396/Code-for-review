<?php

namespace Sam\Rtb\Command\Concrete\Base;

use LotItem;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidding\AbsenteeBid\Validate\AbsenteeBidExistenceCheckerAwareTrait;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\AbsenteeBid\AutoplaceAbsenteeBidDetectorCreateTrait;
use Sam\Rtb\Command\Concrete\Base\Validate\RtbCommandStateCheckerCreateTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\State\History\HistoryServiceFactoryCreateTrait;
use Sam\Rtb\User\UserHashGeneratorCreateTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class ChangeIncrement
 * @package Sam\Rtb\Command\Concrete\Base
 * @method LotItem getLotItem() - existence checked in checkRunningLot()
 */
abstract class ChangeIncrement extends CommandBase implements RtbCommandHelperAwareInterface
{
    use AbsenteeBidExistenceCheckerAwareTrait;
    use AuctionBidderHelperAwareTrait;
    use AutoplaceAbsenteeBidDetectorCreateTrait;
    use HighBidDetectorCreateTrait;
    use HistoryServiceFactoryCreateTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbCommandStateCheckerCreateTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbRendererCreateTrait;
    use UserHashGeneratorCreateTrait;

    protected ?float $newIncrement = null;
    protected ?float $currentBid = null;

    /**
     * @param float|null $newIncrement
     * @return static
     */
    public function setNewIncrement(?float $newIncrement): static
    {
        $this->newIncrement = $newIncrement;
        return $this;
    }

    /**
     * Current Bid value known by client at the moment of the "Change Increment" command request.
     * We want to check sync of console and server state by the "Current Bid" value.
     * @param float|null $currentBid
     * @return $this
     */
    public function setCurrentBid(?float $currentBid): static
    {
        $this->currentBid = $currentBid;
        return $this;
    }

    public function execute(): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        $stateChecker = $this->createRtbCommandStateChecker();
        if (
            !$this->checkConsoleSync()
            || !$this->checkRunningLot()
            || !$stateChecker->checkCurrentBidAmountSync($rtbCurrent, $this->currentBid)
        ) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        $historyService = $this->createHistoryServiceFactory()->create($rtbCurrent);
        $historyService->snapshot($rtbCurrent, Constants\Rtb::CMD_CHANGE_INCREMENT_Q, $this->detectModifierUserId());

        $currentBidAmount = $this->createHighBidDetector()->detectAmount($this->getLotItemId(), $this->getAuctionId());

        $askingBid = $currentBidAmount + $this->newIncrement;
        $currentBidderNo = '';
        $bidderName = '';

        $auctionLot = $this->getAuctionLot();
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $this->getLogger()->log("Admin clerk changes increment to {$this->newIncrement} on lot {$lotNo} ({$this->getLotItemId()})");

        $bidTransaction = $this->createBidTransactionLoader()->loadLastActiveBid($this->getLotItemId(), $this->getAuctionId());
        $highBidUserId = $bidTransaction?->UserId;

        if ($highBidUserId > 0) {
            $auctionBidder = $this->getAuctionBidderLoader()->load($highBidUserId, $this->getAuctionId(), true);
            if ($auctionBidder) {
                $currentBidderNo = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
            }
            $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($highBidUserId, true);
            $bidderName = UserPureRenderer::new()->renderFullName($userInfo);
        }

        $bidByUserId = $this->createAutoplaceAbsenteeBidDetector()
            ->setLotItemId($this->getLotItemId())
            ->setAuctionId($this->getAuctionId())
            ->setHighBidUserId($highBidUserId)
            ->setAskingBid($askingBid)
            ->detectUserId();
        $isAbsentee = $bidByUserId > 0;

        $auctionBidder = $this->getAuctionBidderLoader()->load($bidByUserId, $this->getAuctionId(), true);
        if (
            $auctionBidder
            && $this->getAuctionBidderHelper()->isApproved($auctionBidder)
        ) {
            $bidderNum = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
            $auctionBidderUser = $this->getUserLoader()->load($auctionBidder->UserId);
            $username = $auctionBidderUser->Username ?? '';
        } else {
            $bidderNum = '';
            $username = '';
            $bidByUserId = null;
        }

        //set the current bidder in RtbCurrent to null
        $rtbCurrent->AskBid = $askingBid;
        $rtbCurrent->NewBidBy = $bidByUserId;
        $rtbCurrent->Increment = $this->newIncrement;
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        $askingBidFormatted = $this->getNumberFormatter()->formatMoney($askingBid);

        $langAskingChange = $this->translate('BIDDERCLIENT_MSG_NEWASKING');
        $status = sprintf($langAskingChange, $this->getCurrency() . $askingBidFormatted);

        $message = $this->createRtbRenderer()->renderAuctioneerMessage($status, $this->getAuction());
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message, true);
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message);

        $userHashGenerator = $this->createUserHashGenerator();
        $currentBidderUserHash = $userHashGenerator->generate($highBidUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $pendingBidUserHash = $userHashGenerator->generate($bidByUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $data = [
            Constants\Rtb::RES_CURRENT_BIDDER_NAME => $bidderName,
            Constants\Rtb::RES_CURRENT_BIDDER_NO => $currentBidderNo,
            Constants\Rtb::RES_CURRENT_BIDDER_USER_HASH => $currentBidderUserHash,
            Constants\Rtb::RES_IS_ABSENTEE_BID => $isAbsentee, // Is an absentee bid
            Constants\Rtb::RES_LOT_ITEM_ID => $this->getLotItemId(),
            Constants\Rtb::RES_PENDING_BID_BIDDER_NO => $bidderNum, // Auto
            Constants\Rtb::RES_PENDING_BID_USER_HASH => $pendingBidUserHash,
            Constants\Rtb::RES_PLACE_BID_BUTTON_INFO => $username,
            Constants\Rtb::RES_STATUS => $status,
        ];

        $responseDataProducer = $this->getResponseDataProducer();
        $data = array_merge(
            $data,
            $responseDataProducer->produceBidData($rtbCurrent, ['askingBid' => $askingBid, 'currentBid' => $currentBidAmount]),
            $responseDataProducer->produceUndoButtonData($rtbCurrent),
            $responseDataProducer->produceIncrementData($rtbCurrent, ['currentBid' => $currentBidAmount])
        );

        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_CHANGE_ASKING_BID_S, Constants\Rtb::RES_DATA => $data];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        unset(
            $data[Constants\Rtb::RES_INCREMENT_CURRENT],
            $data[Constants\Rtb::RES_INCREMENT_NEXT_1],
            $data[Constants\Rtb::RES_INCREMENT_NEXT_2],
            $data[Constants\Rtb::RES_INCREMENT_NEXT_3],
            $data[Constants\Rtb::RES_INCREMENT_NEXT_4],
            $data[Constants\Rtb::RES_INCREMENT_RESTORE],
        );

        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_CHANGE_ASKING_BID_S, Constants\Rtb::RES_DATA => $data];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $status
        );

        $this->setResponses($responses);
    }
}
