<?php

namespace Sam\Rtb\Command\Concrete\Base;

use AuctionLotItem;
use LotItem;
use Sam\Bidding\BidTransaction\Place\Live\LiveBidSaverCreateTrait;
use Sam\Core\Constants;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Bid\BidUpdaterAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\State\History\HistoryServiceFactoryCreateTrait;
use Sam\Rtb\State\RtbStateUpdaterCreateTrait;
use Sam\Rtb\User\UserHashGeneratorCreateTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class PlaceFloorPriorityBid
 * @package Sam\Rtb\Command\Concrete\Base
 * @method AuctionLotItem getAuctionLot() - existence checked in checkRunningLot()
 * @method LotItem getLotItem() - existence checked in checkRunningLot()
 */
abstract class PlaceFloorPriorityBid extends CommandBase implements RtbCommandHelperAwareInterface
{
    use BidUpdaterAwareTrait;
    use HistoryServiceFactoryCreateTrait;
    use LiveBidSaverCreateTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbRendererCreateTrait;
    use RtbStateUpdaterCreateTrait;
    use UserHashGeneratorCreateTrait;

    // Externally defined properties

    protected bool $isConfirmBuy = false;

    // Internally defined properties

    protected ?int $outbidUserId = null;
    protected bool $isBidPlaced = false;
    protected ?float $currentBidAmount = null;
    protected string $gameStatus = '';

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
        if (
            !$this->checkConsoleSync()
            || !$this->checkRunningLot()
        ) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        $rtbCurrent = $this->getRtbCurrent();
        $lotItem = $this->getLotItem();
        $auction = $this->getAuction();
        $auctionLot = $this->getAuctionLot();
        $logData = ['li' => $lotItem->Id, 'a' => $auction->Id];

        /**
         * Check auction status correctness
         */
        if (!$auction->isStarted()) {
            log_error(
                'Floor priority bid can be placed only in started auction'
                . composeSuffix($logData + ['as' => $auction->AuctionStatusId])
            );
            return;
        }

        /**
         * Check if current bid exists and it is for internet bidder
         */
        $currentBidTransaction = $this->createBidTransactionLoader()->loadLastActiveBid($lotItem->Id, $auction->Id);
        if (!$currentBidTransaction) {
            log_error('Floor priority bid can be placed only against existing bid' . composeSuffix($logData));
            return;
        }
        if (!$currentBidTransaction->UserId) {
            log_error(
                'Floor priority bid can be placed only against internet bidder'
                . composeSuffix($logData + ['current bt' => $currentBidTransaction->Id])
            );
            return;
        }

        $this->outbidUserId = $currentBidTransaction->UserId;
        $askingBid = $currentBidTransaction->Bid;

        if (
            $auctionLot->BuyNow
            && !$this->isConfirmBuy
            && $auctionLot->isAmongWonStatuses()
        ) {
            $responses = $this->getResponseHelper()->getResponseForBidConfirmationForSoldLot(
                $auction->Id,
                $lotItem,
                null,
                $askingBid,
                Constants\Rtb::RES_BID_TO_FLOOR
            );
            $this->setResponses($responses);
            return;
        }

        /**
         * Store state snapshot for Undo
         */
        $historyService = $this->createHistoryServiceFactory()->create($rtbCurrent);
        $historyService->snapshot($rtbCurrent, Constants\Rtb::CMD_FLOOR_PRIORITY_Q, $this->detectModifierUserId());

        /**
         * Place floor bid
         */
        $liveBidSaver = $this->createLiveBidSaver()
            ->setAmount($askingBid)
            ->setAuction($auction)
            ->setEditorUserId($this->detectModifierUserId())
            ->setLotItem($lotItem);
        $bidTransaction = $liveBidSaver->place();
        if (!$bidTransaction) {
            log_error($liveBidSaver->errorMessage() . composeSuffix($logData + ['bid' => $askingBid]));
            return;
        }

        /**
         * Update rtb state
         */
        $newBidBy = $rtbCurrent->NewBidBy;
        $rtbCurrent = $this->getBidUpdater()
            ->setRtbCurrent($rtbCurrent)
            ->setLotItem($lotItem)
            ->setAuction($auction)
            ->update($this->detectModifierUserId(), $bidTransaction->UserId, $this->outbidUserId);
        $rtbCurrent->NewBidBy = $newBidBy;
        $rtbCurrent = $this->createRtbStateUpdater()->update($rtbCurrent, $this->getAuction()->AccountId, $this->getViewLanguageId());
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $this->getLogger()->log("Admin clerk set floor priority at {$askingBid} on lot {$lotNo} ({$lotItem->Id})");

        $this->currentBidAmount = $bidTransaction->Bid;
        $this->gameStatus = $this->updateChatHistory($this->currentBidAmount);

        $this->isBidPlaced = true;
    }

    /**
     * Persist status message to static file with chat history
     * @param float $currentBidAmount
     * @return string
     */
    protected function updateChatHistory(float $currentBidAmount): string
    {
        $currentBidFormatted = $this->getCurrency() . $this->getNumberFormatter()->formatMoney($currentBidAmount);
        $langFloorPriority = $this->translate('BIDDERCLIENT_FLOOR_PRIORITY');
        // Message: We're sorry, the auctioneer gave the floor priority. Bid $XX
        $gameStatus = sprintf($langFloorPriority, $currentBidFormatted);
        $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($gameStatus, $this->getAuction());
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageHtml);
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageHtml, true);
        return $gameStatus;
    }

    protected function createResponses(): void
    {
        if (!$this->isBidPlaced) {
            $this->setResponses([]);
            return;
        }

        $rtbCurrent = $this->getRtbCurrent();
        $pendingBidUserHash = $this->createUserHashGenerator()->generate($this->outbidUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $data = $this->getResponseHelper()->getLotData($rtbCurrent, ['currentBid' => $this->currentBidAmount]);
        $data[Constants\Rtb::RES_STATUS] = $this->gameStatus;
        $data[Constants\Rtb::RES_OUTBID_PENDING_BID_USER_HASH] = $pendingBidUserHash;

        $clerkData = $auctioneerData = array_merge(
            $data,
            $this->getResponseDataProducer()->produceAdminSideData($rtbCurrent)
        );

        $responses = $this->makePublicConsoleResponses($data)
            + $this->makeClerkConsoleResponse($clerkData)
            + $this->makeAuctioneerConsoleResponse($auctioneerData);

        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $this->gameStatus
        );

        $this->setResponses($responses);
    }

    /**
     * @param array $publicData
     * @return array
     */
    protected function makePublicConsoleResponses(array $publicData): array
    {
        $publicData = $this->getResponseHelper()->removeSensitiveData($publicData);
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S,
            Constants\Rtb::RES_DATA => $publicData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        return $responses;
    }

    /**
     * @param array $clerkData
     * @return array
     */
    protected function makeClerkConsoleResponse(array $clerkData): array
    {
        // Prepare data
        $responseDataProducer = $this->getResponseDataProducer();
        $clerkData = array_merge(
            $clerkData,
            $responseDataProducer->produceIncrementData($this->getRtbCurrent(), ['currentBid' => $this->currentBidAmount]),
            $responseDataProducer->produceBidderAddressData($this->getRtbCurrent(), Constants\Rtb::UT_CLERK, ['highBidUserId' => 0]) // 0 for floor
        );
        // Compose response
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S,
            Constants\Rtb::RES_DATA => $clerkData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        return $responses;
    }

    /**
     * @param array $auctioneerData
     * @return array
     */
    protected function makeAuctioneerConsoleResponse(array $auctioneerData): array
    {
        $responseDataProducer = $this->getResponseDataProducer();
        $auctioneerData = array_replace(
            $auctioneerData,
            $responseDataProducer->produceBidderAddressData($this->getRtbCurrent(), Constants\Rtb::UT_AUCTIONEER, ['highBidUserId' => 0]) // 0 for floor
        );
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S,
            Constants\Rtb::RES_DATA => $auctioneerData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;
        return $responses;
    }
}
