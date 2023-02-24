<?php
/**
 * Undo command
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

namespace Sam\Rtb\Command\Concrete\Base;

use RtbCurrent;
use RtbCurrentSnapshot;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidding\AbsenteeBid\Detect\HighAbsenteeBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\State\History\HistoryServiceFactoryCreateTrait;
use Sam\Rtb\User\UserHashGeneratorCreateTrait;

/**
 * Class UndoSnapshot
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class UndoSnapshot extends CommandBase implements RtbCommandHelperAwareInterface
{
    use AuctionBidderHelperAwareTrait;
    use HighAbsenteeBidDetectorCreateTrait;
    use HistoryServiceFactoryCreateTrait;
    use LotRendererAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbRendererCreateTrait;
    use UserHashGeneratorCreateTrait;

    protected ?RtbCurrentSnapshot $snapshot = null;
    protected bool $isRestored = false;

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
        $rtbCurrent = $historyService->restore($rtbCurrent, $this->detectModifierUserId());
        $this->isRestored = $rtbCurrent instanceof RtbCurrent;
        if (!$this->isRestored) {
            log_error("No RTB State Snapshots for undo" . composeSuffix(['a' => $this->getAuctionId()]));
            return;
        }

        $this->snapshot = $historyService->getLastRestoredSnapshot();
        $auctionLot = $this->getAuctionLot();
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $this->getLogger()->log(
            'Admin clerk undo on lot ' . $lotNo . ' (' . $rtbCurrent->LotItemId . ') '
            . 'for command ' . $historyService->getCommandName($this->snapshot)
        );

        $this->createStaticMessages();
    }

    protected function createResponses(): void
    {
        if (!$this->isRestored) {
            $this->setResponses([]);
            return;
        }

        $rtbCurrent = $this->getRtbCurrent();

        $newBidByUserId = null;
        $newBidBidderNo = '';
        $userButtonInfo = '';
        $currentBidUserId = null;
        $highBidderNo = '';
        $highBidderName = '';
        $currentBidAmount = 0.;
        $user = null;
        $highAbsenteeUserId = null;
        $auctionBidderLoader = $this->getAuctionBidderLoader();

        $highBidTransaction = $this->createBidTransactionLoader()
            ->loadLastActiveBid($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        if ($highBidTransaction) {
            $currentBidAmount = $highBidTransaction->Bid;
            $currentBidUserId = $highBidTransaction->UserId;

            if ($highBidTransaction->UserId) {
                $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($highBidTransaction->UserId, true);
                $highBidderName = UserPureRenderer::new()->renderFullName($userInfo);
                $auctionBidder = $auctionBidderLoader
                    ->load($highBidTransaction->UserId, $rtbCurrent->AuctionId, true);
                if ($auctionBidder) {
                    $highBidderNo = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
                }
            }
        }

        $newBidAuctionBidder = $auctionBidderLoader->load($rtbCurrent->NewBidBy, $rtbCurrent->AuctionId, true);
        if (
            $newBidAuctionBidder
            && $this->getAuctionBidderHelper()->isApproved($newBidAuctionBidder)
        ) {
            $newBidByUserId = $rtbCurrent->NewBidBy;
            $newBidBidderNo = $this->getBidderNumberPadding()->clear($newBidAuctionBidder->BidderNum);
            $user = $this->getUserLoader()->load($newBidAuctionBidder->UserId, true);
            $userButtonInfo = $user->Username;
        }

        if ($rtbCurrent->AbsenteeBid) {
            $highAbsentee = $this->createHighAbsenteeBidDetector()->detectFirstHighestByCurrentBid(
                $rtbCurrent->LotItemId,
                $rtbCurrent->AuctionId,
                $currentBidAmount,
                true
            );
            $highAbsenteeUserId = $highAbsentee->UserId ?? null;
        }

        $onlinebidButtonInfo = (int)$this->getSettingsManager()
            ->get(Constants\Setting::ONLINEBID_BUTTON_INFO, $this->getAuction()->AccountId);
        if ($onlinebidButtonInfo && $user) {
            $userButtonInfo = $this->getRtbCommandHelper()->getButtonInfo($user, $onlinebidButtonInfo);
        }
        $userButtonInfo = $this->getRtbGeneralHelper()->clean($userButtonInfo);

        $message = $this->detectUndoMessage();
        $message = $this->getRtbGeneralHelper()->clean($message);

        $userHashGenerator = $this->createUserHashGenerator();
        $currentBidderUserHash = $userHashGenerator->generate($currentBidUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $highAbsenteeUserHash = $userHashGenerator->generate($highAbsenteeUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $pendingBidUserHash = $userHashGenerator->generate($newBidByUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $data = [
            Constants\Rtb::RES_CURRENT_BIDDER_NAME => $highBidderName,
            Constants\Rtb::RES_CURRENT_BIDDER_NO => $highBidderNo,
            Constants\Rtb::RES_CURRENT_BIDDER_USER_HASH => $currentBidderUserHash,
            Constants\Rtb::RES_HIGH_ABSENTEE_USER_HASH => $highAbsenteeUserHash,
            Constants\Rtb::RES_IS_ABSENTEE_BID => false,
            Constants\Rtb::RES_IS_UNDO => 1,
            Constants\Rtb::RES_LOT_ACTIVITY => $rtbCurrent->LotActive,
            Constants\Rtb::RES_LOT_ITEM_ID => $rtbCurrent->LotItemId,
            Constants\Rtb::RES_MESSAGE => $message,
            Constants\Rtb::RES_PENDING_BID_BIDDER_NO => $newBidBidderNo,
            Constants\Rtb::RES_PENDING_BID_USER_HASH => $pendingBidUserHash,
            Constants\Rtb::RES_PLACE_BID_BUTTON_INFO => $userButtonInfo,
        ];

        $responseDataProducer = $this->getResponseDataProducer();
        $clerkData = array_merge(
            $data,
            $responseDataProducer->produceBidData($rtbCurrent, ['currentBid' => $currentBidAmount]),
            $responseDataProducer->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_CLERK, ['highBidUserId' => $currentBidUserId]),
            $responseDataProducer->produceIncrementData($rtbCurrent, ['currentBid' => $currentBidAmount]),
            $responseDataProducer->produceUndoButtonData($rtbCurrent)
        );

        $auctioneerData = array_merge(
            $data,
            $responseDataProducer->produceBidData($rtbCurrent, ['currentBid' => $currentBidAmount]),
            $responseDataProducer->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_AUCTIONEER, ['highBidUserId' => $currentBidUserId])
        );

        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S, Constants\Rtb::RES_DATA => $clerkData];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;

        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S, Constants\Rtb::RES_DATA => $auctioneerData];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

        $data = $clerkData;
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

    protected function createStaticMessages(): void
    {
        $auction = $this->getAuction();
        $message = $this->detectUndoMessage();
        $completeMessage = $this->createRtbRenderer()->renderAuctioneerMessage($message, $this->getAuction());
        $this->getMessenger()->createStaticHistoryMessage($auction->Id, $completeMessage);
        $this->getMessenger()->createStaticHistoryMessage($auction->Id, $completeMessage, true);
    }

    /**
     * @return string
     */
    protected function detectUndoMessage(): string
    {
        $message = '';
        $translationKeys = [
            Constants\Rtb::CMD_CHANGE_ASKING_BID_Q => 'BIDDERCLIENT_UNDO_CHANGE_ASKING_BID',
            Constants\Rtb::CMD_FLOOR_Q => 'BIDDERCLIENT_UNDO_FLOOR',
            Constants\Rtb::CMD_ACCEPT_Q => 'BIDDERCLIENT_UNDO_ACCEPT',
            Constants\Rtb::CMD_PLACE_Q => 'BIDDERCLIENT_UNDO_PLACE',
            Constants\Rtb::CMD_FLOOR_PRIORITY_Q => 'BIDDERCLIENT_UNDO_FLOOR_PRIORITY',
            Constants\Rtb::CMD_ABSENTEE_PRIORITY_Q => 'BIDDERCLIENT_UNDO_ABSENTEE_PRIORITY',
            Constants\Rtb::CMD_CHANGE_INCREMENT_Q => 'BIDDERCLIENT_UNDO_CHANGE_INCREMENT',
            Constants\Rtb::CMD_RESET_INCREMENT_Q => 'BIDDERCLIENT_UNDO_RESET_INCREMENT',
        ];
        if (isset($translationKeys[$this->snapshot->Command])) {
            $langCommand = $translationKeys[$this->snapshot->Command];
            $message = $this->translate($langCommand);
        }
        return $message;
    }
}
