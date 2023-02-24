<?php

namespace Sam\Rtb\Command\Concrete\Base;

use AuctionLotItem;
use LotItem;
use Sam\Bidder\BidderInfo\BidderInfoRendererAwareTrait;
use Sam\Bidding\BidTransaction\Place\Live\LiveBidSaverCreateTrait;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Math\Floating;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Bid\BidUpdaterAwareTrait;
use Sam\Rtb\Command\Concrete\Base\Validate\RtbCommandStateCheckerCreateTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\State\History\HistoryServiceFactoryCreateTrait;
use Sam\Rtb\User\UserHashGeneratorCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class PlaceFloorBid
 * @package Sam\Rtb\Command\Concrete\Base
 * @method AuctionLotItem getAuctionLot() - existence checked in checkRunningLot()
 * @method LotItem getLotItem() - existence checked in checkRunningLot()
 */
abstract class PlaceFloorBid extends CommandBase implements RtbCommandHelperAwareInterface
{
    use BidderInfoRendererAwareTrait;
    use BidUpdaterAwareTrait;
    use HighBidDetectorCreateTrait;
    use HistoryServiceFactoryCreateTrait;
    use LiveBidSaverCreateTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbCommandStateCheckerCreateTrait;
    use RtbRendererCreateTrait;
    use UserHashGeneratorCreateTrait;

    protected ?float $askingBid = null;
    protected bool $isConfirmBuy = false;
    protected ?float $currentBid = null;

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
        $rtbCurrent = $this->getRtbCurrent();
        $stateChecker = $this->createRtbCommandStateChecker();
        if (
            !$this->checkAskingBid()
            || !$this->checkConsoleSync()
            || !$this->checkRunningLot()
            || !$stateChecker->checkCurrentBidAmountSync($rtbCurrent, $this->currentBid)
        ) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        $langInternetBidder2 = $this->translate('BIDDERCLIENT_INTERNETBIDDER2');
        $langFloorBidder2 = $this->translate('BIDDERCLIENT_FLOORBIDDER2');
        $rtbCurrent = $this->getRtbCurrent();
        $logData = ['li' => $rtbCurrent->LotItemId, 'a' => $this->getAuctionId(), 'bid' => $this->askingBid];

        // SAM-970: floor (FloorQ) or online (AcceptQ) bid shall be ignored if RtbCurrent.LotActive==false
        if ($rtbCurrent->isIdleLot()) {
            log_warning(
                "FloorQ: Attempt to post floor bid of {$this->askingBid} on in-active lot"
                . composeSuffix($logData)
            );
            return;
        }

        if (!$this->getAuction()->isStarted()) {
            // Only to floor bid if the auction has started
            return;
        }

        $lotItem = $this->getLotItem();
        $auctionLot = $this->getAuctionLot();
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);

        if (
            $auctionLot->BuyNow
            && !$this->isConfirmBuy
            && $auctionLot->isAmongWonStatuses()
        ) {
            $responses = $this->getResponseHelper()->getResponseForBidConfirmationForSoldLot(
                $this->getAuctionId(),
                $lotItem,
                null,
                $this->askingBid,
                Constants\Rtb::RES_BID_TO_FLOOR
            );
            $this->setResponses($responses);
            return;
        }

        $historyService = $this->createHistoryServiceFactory()->create($rtbCurrent);
        $historyService->snapshot($rtbCurrent, Constants\Rtb::CMD_FLOOR_Q, $this->detectModifierUserId());

        $pendingBidUserId = $rtbCurrent->NewBidBy;
        $highBidUserId = $this->createHighBidDetector()->detectUserId($lotItem->Id, $this->getAuctionId());

        $liveBidSaver = $this->createLiveBidSaver()
            ->setAmount($this->askingBid)
            ->setAuction($this->getAuction())
            ->setEditorUserId($this->detectModifierUserId())
            ->setLotItem($lotItem);
        $bidTransaction = $liveBidSaver->place();
        if (!$bidTransaction) {
            log_warning($liveBidSaver->errorMessage() . composeSuffix($logData));
            return;
        }

        $this->getLogger()->log("Admin clerk posts floor bid at {$this->askingBid} on lot {$lotNo} ({$lotItem->Id})");

        $outbidUserId = $highBidUserId;

        $rtbCurrent = $this->getBidUpdater()
            ->setRtbCurrent($rtbCurrent)
            ->setLotItem($this->getLotItem())
            ->setAuction($this->getAuction())
            ->update($this->detectModifierUserId(), $bidTransaction->UserId, $outbidUserId, $rtbCurrent->LotGroup);
        $data = $this->getBidUpdater()->getData();

        // reload RtbCurrent, since it is manipulated in UpdateBid
        $this->reloadRtbCurrent();

        $currentBid = Cast::toFloat($data[Constants\Rtb::RES_CURRENT_BID], Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        $currentBidFormatted = $this->getCurrency() . $this->getNumberFormatter()->formatMoney($currentBid);
        $adminMessage = $publicMessage = '';

        $currentBid = Cast::toFloat($data[Constants\Rtb::RES_CURRENT_BID], Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        if ($data[Constants\Rtb::RES_CURRENT_BIDDER_NO]) {
            $bidderInfoForAdmin = $this->getBidderInfoRenderer()->renderForAdminRtb($bidTransaction->UserId, $this->getAuctionId());
            $adminMessage = $currentBidFormatted . " " . $langInternetBidder2 . " " . $bidderInfoForAdmin;
            $publicMessage = $currentBidFormatted . " " . $langInternetBidder2;
            $isHideBidderNumber = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::HIDE_BIDDER_NUMBER, $this->getAuction()->AccountId);
            if (!$isHideBidderNumber) {
                $bidderInfoForPublic = $this->getBidderInfoRenderer()->renderForPublicRtb($bidTransaction->UserId, $this->getAuctionId());
                $publicMessage .= " " . $bidderInfoForPublic;
            }
        } elseif ($currentBid) {
            $adminMessage = $currentBidFormatted . " " . $langFloorBidder2;
            $publicMessage = $currentBidFormatted . " " . $langFloorBidder2;
        }

        // Save in static file
        if ($adminMessage) {
            $this->getMessenger()->createStaticHistoryMessage(
                $this->getAuctionId(),
                $this->createRtbRenderer()->renderAuctioneerMessage($adminMessage, $this->getAuction()),
                true
            );
        }
        if ($publicMessage) {
            $this->getMessenger()->createStaticHistoryMessage(
                $this->getAuctionId(),
                $this->createRtbRenderer()->renderAuctioneerMessage($publicMessage, $this->getAuction())
            );
        }
        $data[Constants\Rtb::RES_STATUS] = $adminMessage;
        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S, Constants\Rtb::RES_DATA => $data];
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

        $pendingBidUserHash = $this->createUserHashGenerator()->generate($pendingBidUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $data[Constants\Rtb::RES_OUTBID_PENDING_BID_USER_HASH] = $pendingBidUserHash;
        $data[Constants\Rtb::RES_STATUS] = $publicMessage;
        $data = $this->getResponseHelper()->removeSensitiveData($data);
        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S, Constants\Rtb::RES_DATA => $data];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $publicMessage
        );
        $this->setResponses($responses);
    }

    /**
     * Check positive amount of asking bid value.
     * Do not verify asking bid correspondence to value of server state,
     * because clerk can to modify its value in text-box and don't materialize it in state by ChangeAskingBid command.
     * @return bool
     */
    protected function checkAskingBid(): bool
    {
        $rtbCurrent = $this->getRtbCurrent();
        $success = Floating::gt($this->askingBid, 0.);
        if (!$success) {
            log_warning(
                "Asking bid value for the required floor bid must be positive"
                . composeSuffix(
                    [
                        'request ask' => $this->askingBid,
                        'actual ask' => $rtbCurrent->AskBid,
                        'li' => $rtbCurrent->LotItemId,
                        'a' => $rtbCurrent->AuctionId,
                    ]
                )
            );
        }
        return $success;
    }
}
