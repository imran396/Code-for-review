<?php

namespace Sam\Rtb\Command\Concrete\Base;

use LotItem;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidding\AbsenteeBid\Validate\AbsenteeBidExistenceCheckerAwareTrait;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\AbsenteeBid\AutoplaceAbsenteeBidDetectorCreateTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\State\History\HistoryServiceFactoryCreateTrait;
use Sam\Rtb\User\UserHashGeneratorCreateTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class ChangeAskingBid
 * @package Sam\Rtb\Command\Concrete\Base
 * @method LotItem getLotItem() - existence checked in checkRunningLot()
 */
abstract class ChangeAskingBid extends CommandBase implements RtbCommandHelperAwareInterface
{
    use AbsenteeBidExistenceCheckerAwareTrait;
    use AuctionBidderHelperAwareTrait;
    use AutoplaceAbsenteeBidDetectorCreateTrait;
    use HighBidDetectorCreateTrait;
    use HistoryServiceFactoryCreateTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbRendererCreateTrait;
    use UserHashGeneratorCreateTrait;

    protected ?float $askingBid = null;
    protected ?float $newIncrement = null;

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
        $historyService->snapshot($rtbCurrent, Constants\Rtb::CMD_CHANGE_ASKING_BID_Q, $this->detectModifierUserId());

        $currentBidAmount = $this->createHighBidDetector()->detectAmount($this->getLotItemId(), $this->getAuctionId());
        $currentBidBidderNo = '';
        $bidderName = '';

        $auctionLot = $this->getAuctionLot();
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);

        $this->getLogger()->log("Admin clerk changes asking to {$this->askingBid} on lot {$lotNo} ({$this->getLotItemId()})");

        $bidTransaction = $this->createBidTransactionLoader()->loadLastActiveBid($this->getLotItemId(), $this->getAuctionId());
        $highBidUserId = $bidTransaction?->UserId;

        if ($highBidUserId > 0) {
            $bidByAuctionBidder = $this->getAuctionBidderLoader()->load($highBidUserId, $this->getAuctionId(), true);
            if ($bidByAuctionBidder) {
                $currentBidBidderNo = $this->getBidderNumberPadding()->clear($bidByAuctionBidder->BidderNum);
            }
            $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($highBidUserId, true);
            $bidderName = UserPureRenderer::new()->renderFullName($userInfo);
        }

        $bidByUserId = $this->createAutoplaceAbsenteeBidDetector()
            ->setLotItemId($this->getLotItemId())
            ->setAuctionId($this->getAuctionId())
            ->setHighBidUserId($highBidUserId)
            ->setAskingBid($this->askingBid)
            ->detectUserId();
        $isAbsentee = $bidByUserId > 0;

        $bidByUser = null;
        $bidByButtonInfo = '';
        $bidByAuctionBidder = $this->getAuctionBidderLoader()->load($bidByUserId, $this->getAuctionId(), true);
        if (
            $bidByAuctionBidder
            && $this->getAuctionBidderHelper()->isApproved($bidByAuctionBidder)
        ) {
            $bidByBidderNo = $this->getBidderNumberPadding()->clear($bidByAuctionBidder->BidderNum);

            $bidByUser = $this->getUserLoader()->load($bidByAuctionBidder->UserId);
            $bidByButtonInfo = $bidByUser->Username;
            $bidByUserId = $bidByAuctionBidder->UserId;
        } else {
            $bidByUserId = null;
            $bidByBidderNo = '';
        }

        $onlinebidButtonInfo = (int)$this->getSettingsManager()
            ->get(Constants\Setting::ONLINEBID_BUTTON_INFO, $this->getAuction()->AccountId);
        if ($onlinebidButtonInfo && $bidByUser) {
            $bidByButtonInfo = $this->getRtbCommandHelper()->getButtonInfo($bidByUser, $onlinebidButtonInfo);
        }
        $bidByButtonInfo = $this->getRtbGeneralHelper()->clean($bidByButtonInfo);

        unset($bidByAuctionBidder);

        //set the current bidder in RtbCurrent to null
        $rtbCurrent->AskBid = $this->askingBid;
        $rtbCurrent->NewBidBy = $bidByUserId;
        $rtbCurrent->AbsenteeBid = $bidByUserId > 0;

        if (
            Floating::gt($this->newIncrement, 0.)
            || Floating::lt($this->newIncrement, 0.)
        ) {    //if increment quick changed
            $rtbCurrent->Increment = $this->newIncrement;
        } elseif ($this->getAuction()->isSimpleClerking()) {
            $rtbCurrent->Increment = $this->newIncrement;
        }

        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        $askingBidFormatted = $this->getNumberFormatter()->formatMoney($this->askingBid);

        $langAskingChange = $this->translate('BIDDERCLIENT_MSG_NEWASKING');
        $status = sprintf($langAskingChange, $this->getCurrency() . $askingBidFormatted);

        $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($status, $this->getAuction());

        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageHtml, true);
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageHtml);

        $userHashGenerator = $this->createUserHashGenerator();
        $pendingBidUserHash = $userHashGenerator->generate($bidByUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $currentBidderUserHash = $userHashGenerator->generate($highBidUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $data = [
            Constants\Rtb::RES_IS_ABSENTEE_BID => $isAbsentee,
            Constants\Rtb::RES_LOT_ITEM_ID => $rtbCurrent->LotItemId,
            Constants\Rtb::RES_PENDING_BID_BIDDER_NO => $bidByBidderNo, // Auto
            Constants\Rtb::RES_PENDING_BID_USER_HASH => $pendingBidUserHash,
            Constants\Rtb::RES_PLACE_BID_BUTTON_INFO => $bidByButtonInfo,
            Constants\Rtb::RES_STATUS => $status,
        ];

        $data[Constants\Rtb::RES_CURRENT_BIDDER_USER_HASH] = $currentBidderUserHash;
        $data[Constants\Rtb::RES_CURRENT_BIDDER_NO] = $currentBidBidderNo;
        $data[Constants\Rtb::RES_CURRENT_BIDDER_NAME] = $bidderName;

        $responseDataProducer = $this->getResponseDataProducer();
        $data = array_merge(
            $data,
            $responseDataProducer->produceBidData($rtbCurrent, ['askingBid' => $this->askingBid, 'currentBid' => $currentBidAmount]),
            $responseDataProducer->produceIncrementData($rtbCurrent, ['currentBid' => $currentBidAmount]),
            $responseDataProducer->produceLotChangesData($rtbCurrent),
            $responseDataProducer->produceUndoButtonData($rtbCurrent)
        );

        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_CHANGE_ASKING_BID_S,
            Constants\Rtb::RES_DATA => $data
        ];
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

        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_CHANGE_ASKING_BID_S,
            Constants\Rtb::RES_DATA => $data
        ];
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

    /**
     * @param float|null $askingBid
     * @return static
     */
    public function setAskingBid(?float $askingBid): static
    {
        $this->askingBid = $askingBid;
        return $this;
    }

    /**
     * We pass negative increment, when "Decrement" checkbox enabled at Advanced Clerking
     * @param float|null $newIncrement
     * @return static
     */
    public function setNewIncrement(?float $newIncrement): static
    {
        $this->newIncrement = $newIncrement;
        return $this;
    }
}
