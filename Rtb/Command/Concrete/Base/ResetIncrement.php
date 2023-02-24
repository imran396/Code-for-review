<?php

namespace Sam\Rtb\Command\Concrete\Base;

use AuctionLotItem;
use LotItem;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidding\AbsenteeBid\Validate\AbsenteeBidExistenceCheckerAwareTrait;
use Sam\Bidding\AskingBid\AskingBidDetectorCreateTrait;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\AbsenteeBid\AutoplaceAbsenteeBidDetectorCreateTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\State\History\HistoryServiceFactoryCreateTrait;
use Sam\Rtb\User\UserHashGeneratorCreateTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class ResetIncrement
 * @package Sam\Rtb\Command\Concrete\Base
 * @method AuctionLotItem getAuctionLot() - existence checked in checkRunningLot()
 * @method LotItem getLotItem() - existence checked in checkRunningLot()
 */
abstract class ResetIncrement extends CommandBase implements RtbCommandHelperAwareInterface
{
    use AbsenteeBidExistenceCheckerAwareTrait;
    use AskingBidDetectorCreateTrait;
    use AuctionBidderHelperAwareTrait;
    use AutoplaceAbsenteeBidDetectorCreateTrait;
    use HighBidDetectorCreateTrait;
    use HistoryServiceFactoryCreateTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbRendererCreateTrait;
    use UserHashGeneratorCreateTrait;

    public function execute(): void
    {
        if (
            !$this->checkConsoleSync()
            || !$this->checkRunningLot()
        ) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        $rtbCurrent = $this->getRtbCurrent();

        $historyService = $this->createHistoryServiceFactory()->create($rtbCurrent);
        $historyService->snapshot($rtbCurrent, Constants\Rtb::CMD_RESET_INCREMENT_Q, $this->detectModifierUserId());

        $currentBidAmount = $this->createHighBidDetector()->detectAmount($this->getLotItemId(), $this->getAuctionId());
        $askingBid = $this->createAskingBidDetector()
            ->detectQuantizedBid($currentBidAmount, true, $this->getLotItemId(), $this->getAuctionId());

        $auctionLot = $this->getAuctionLot();
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $this->getLogger()->log("Admin clerk resets to default increment changed asking to {$askingBid} on lot {$lotNo} ({$this->getLotItemId()})");

        $bidderNo = '';
        $bidderName = '';

        $bidTransaction = $this->createBidTransactionLoader()->loadLastActiveBid($this->getLotItemId(), $this->getAuctionId());
        $highBidUserId = $bidTransaction?->UserId;

        if ($highBidUserId > 0) {
            $auctionBidder = $this->getAuctionBidderLoader()->load($highBidUserId, $this->getAuctionId(), true);
            if ($auctionBidder) {
                $bidderNo = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
            }
            $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($highBidUserId);
            $bidderName = UserPureRenderer::new()->renderFullName($userInfo);
        }

        $bidByUserId = $this->createAutoplaceAbsenteeBidDetector()
            ->setLotItemId($this->getLotItemId())
            ->setAuctionId($this->getAuctionId())
            ->setHighBidUserId($highBidUserId)
            ->setAskingBid($askingBid)
            ->detectUserId();

        $auctionBidder = $this->getAuctionBidderLoader()->load($bidByUserId, $this->getAuctionId(), true);
        if (
            $auctionBidder
            && $this->getAuctionBidderHelper()->isApproved($auctionBidder)
        ) {
            $bidderNum = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
        } else {
            $bidByUserId = null;
            $bidderNum = '';
        }

        $rtbCurrent = $this->getRtbCurrent();
        $rtbCurrent->AskBid = $askingBid;
        $rtbCurrent->NewBidBy = $bidByUserId;
        $rtbCurrent->Increment = null;
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        $status = $this->translate('BIDDERCLIENT_MSG_DEFAULTINCR_RESTORED');

        // Save in static file
        $message = $this->createRtbRenderer()->renderAuctioneerMessage($status, $this->getAuction());
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message, true);

        $userHashGenerator = $this->createUserHashGenerator();
        $pendingBidUserHash = $userHashGenerator->generate($bidByUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $data = [
            Constants\Rtb::RES_LOT_ITEM_ID => $this->getLotItemId(),
            Constants\Rtb::RES_PENDING_BID_USER_HASH => $pendingBidUserHash,
            Constants\Rtb::RES_PENDING_BID_BIDDER_NO => $bidderNum, // Auto
            Constants\Rtb::RES_STATUS => $status,
        ];

        $responseDataProducer = $this->getResponseDataProducer();
        $data = array_merge(
            $data,
            $responseDataProducer->produceBidData($rtbCurrent, ['askingBid' => $askingBid, 'currentBid' => $currentBidAmount]),
            $responseDataProducer->produceIncrementData($rtbCurrent, ['currentBid' => $currentBidAmount]),
            $responseDataProducer->produceUndoButtonData($rtbCurrent)
        );

        $currentBidderUserHash = $userHashGenerator->generate($highBidUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $data[Constants\Rtb::RES_CURRENT_BIDDER_USER_HASH] = $currentBidderUserHash;
        $data[Constants\Rtb::RES_CURRENT_BIDDER_NO] = $bidderNo;
        $data[Constants\Rtb::RES_CURRENT_BIDDER_NAME] = $bidderName;

        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_CHANGE_ASKING_BID_S, Constants\Rtb::RES_DATA => $data];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;

        unset(
            $data[Constants\Rtb::RES_INCREMENT_CURRENT],
            $data[Constants\Rtb::RES_INCREMENT_RESTORE],
            $data[Constants\Rtb::RES_INCREMENT_NEXT_1],
            $data[Constants\Rtb::RES_INCREMENT_NEXT_2],
            $data[Constants\Rtb::RES_INCREMENT_NEXT_3],
            $data[Constants\Rtb::RES_INCREMENT_NEXT_4]
        );

        $langNewAsking = $this->translate('BIDDERCLIENT_MSG_NEWASKING');
        $status = sprintf($langNewAsking, $this->getCurrency() . $askingBid);
        $message = $this->createRtbRenderer()->renderAuctioneerMessage($status, $this->getAuction());
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message);

        $data[Constants\Rtb::RES_STATUS] = $status;

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
