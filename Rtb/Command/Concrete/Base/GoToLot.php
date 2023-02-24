<?php

namespace Sam\Rtb\Command\Concrete\Base;

use AuctionLotItem;
use LotItem;
use Sam\AuctionLot\Load\PositionalAuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Validate\AuctionLotExistenceCheckerAwareTrait;
use Sam\Core\Constants;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\State\Reset\RtbStateResetterAwareTrait;
use Sam\Rtb\State\RtbStateUpdaterCreateTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class GoToLot
 * @package Sam\Rtb\Command\Concrete\Base
 * @method LotItem getLotItem() - existence checked in checkRunningLot()
 */
abstract class GoToLot extends CommandBase implements RtbCommandHelperAwareInterface
{
    use AuctionLotExistenceCheckerAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use PositionalAuctionLotLoaderAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbRendererCreateTrait;
    use RtbStateResetterAwareTrait;
    use RtbStateUpdaterCreateTrait;

    /** Next, previous lot markers */
    private const NEXT = 'next';
    private const PREV = 'prev';

    // Externally defined properties

    /**
     * Required by command call contract
     * Numeric string LotItemId or next, prev marker
     */
    protected ?string $goToLotItemId = null;

    // Internally defined properties

    /**
     * Is new switching lot the last
     */
    protected bool $isLastLot = false;
    protected ?LotItem $runningLotItem = null;
    protected ?LotItem $goToLotItem = null;
    protected ?AuctionLotItem $goToAuctionLot = null;
    protected string $gameStatus = '';

    /**
     * @param string $lotItemId numeric integer for id or 'next', 'prev' markers
     * @return static
     */
    public function setGoToLotId(string $lotItemId): static
    {
        $this->goToLotItemId = $lotItemId;
        return $this;
    }

    /**
     * the originating lot stays whatever it is (since we may have "skip to previous" over already sold or unsold lots)
     */
    public function execute(): void
    {
        if (
            !$this->checkConsoleSync()
            || !$this->checkRunningLot()
            || !$this->checkSwitchingLot()
        ) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        $rtbCurrent = $this->getRtbCurrent();
        $this->runningLotItem = $this->getLotItem();

        switch ($this->goToLotItemId) {
            case self::NEXT:
                $this->goToAuctionLot = $this->getPositionalAuctionLotLoader()
                    ->loadNextLot($this->getAuctionId(), $this->getLotItemId());
                $this->isLastLot = !$this->goToAuctionLot;
                break;
            case self::PREV:
                $this->goToAuctionLot = $this->getPositionalAuctionLotLoader()
                    ->loadPreviousLot($this->getAuctionId(), $this->getLotItemId());
                break;
            default:
                $this->goToAuctionLot = $this->getAuctionLotLoader()
                    ->load((int)$this->goToLotItemId, $this->getAuctionId(), true);
        }

        if ($this->goToAuctionLot) {
            $this->goToLotItem = $this->getLotItemLoader()->load($this->goToAuctionLot->LotItemId, true);
            $lotNo = $this->getLotRenderer()->renderLotNo($this->goToAuctionLot);
            $this->getLogger()->log("Admin clerk skips to {$this->goToLotItemId} lot {$lotNo} ({$this->goToLotItem->Id})");
        } else {
            $this->goToLotItem = $this->runningLotItem;
        }

        $rtbCurrent = $this->getRtbCommandHelper()->switchRunningLot($rtbCurrent, $this->goToLotItem);
        $rtbCurrent = $this->getRtbStateResetter()->cleanState($rtbCurrent, $this->detectModifierUserId());
        $rtbCurrent = $this->getRtbCommandHelper()->activateLot($rtbCurrent, Constants\Rtb::LA_BY_AUTO_START, $this->detectModifierUserId());
        $rtbCurrent = $this->createRtbStateUpdater()->update($rtbCurrent, $this->getAuction()->AccountId, $this->getViewLanguageId());
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        if (!$this->isLastLot) {
            $statusData = $this->getResponseDataProducer()->produceGameStatusData($rtbCurrent);
            $this->gameStatus = $statusData[Constants\Rtb::RES_STATUS];
        }
        $this->updateChatHistory($this->gameStatus);
    }

    /**
     * Persist status message to static file with chat history
     * @param string $gameStatus
     */
    protected function updateChatHistory(string $gameStatus): void
    {
        if (!$this->goToAuctionLot) {
            log_error("Cannot update chat history, because Go-to Auction Lot absent");
            return;
        }

        // Clean message center
        $shouldClearMessageCenterLog = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::CLEAR_MESSAGE_CENTER_LOG, $this->getAuction()->AccountId);
        if ($shouldClearMessageCenterLog) {
            $this->getMessenger()->clearStaticMessage($this->getAuctionId(), true);
            $this->getMessenger()->clearStaticMessage($this->getAuctionId());
        }
        // Make message and persist in file
        $messageHtml = $this->createRtbRenderer()->renderQuantityHtml(
            $this->getAuction(),
            $this->goToAuctionLot->LotItemId,
            $this->goToAuctionLot->Quantity ?? null,
            $this->goToAuctionLot->QuantityXMoney ?? false
        );
        $messageHtml .= $this->createRtbRenderer()->renderAuctioneerMessage($gameStatus, $this->getAuction());
        $this->getMessenger()->createStaticChatMessage($this->getAuctionId(), $messageHtml, true);
        $this->getMessenger()->createStaticChatMessage($this->getAuctionId(), $messageHtml);
    }

    /**
     * Generating responses
     */
    protected function createResponses(): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        $data = $this->getResponseHelper()->getLotData($rtbCurrent);
        $data[Constants\Rtb::RES_STATUS] = $this->gameStatus;

        $clerkData = $auctioneerData = array_merge(
            $data,
            $this->getResponseDataProducer()->produceAdminSideData($rtbCurrent)
        );

        $responses = $this->makePublicConsoleResponses($data)
            + $this->makeClerkConsoleResponse($clerkData)
            + $this->makeAuctioneerConsoleResponse($auctioneerData);

        $shouldClearMessageCenter = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::CLEAR_MESSAGE_CENTER, $this->getAuction()->AccountId);
        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $this->gameStatus,
            $shouldClearMessageCenter
        );

        if (!$this->isLastLot) {
            $isTextMsgEnabled = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::TEXT_MSG_ENABLED, $this->getAuction()->AccountId);
            $responses = $this->getResponseHelper()
                ->addSmsTextResponse($responses, $this->getAuction(), $this->goToLotItem->Id, $isTextMsgEnabled);
        }

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
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
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
        $currentBidAmount = (float)$clerkData[Constants\Rtb::RES_CURRENT_BID];
        $responseDataProducer = $this->getResponseDataProducer();
        $clerkData = array_merge(
            $clerkData,
            $responseDataProducer->produceBidderAddressData($this->getRtbCurrent(), Constants\Rtb::UT_CLERK),
            $responseDataProducer->produceIncrementData($this->getRtbCurrent(), ['currentBid' => $currentBidAmount])
        );
        $clerkData[Constants\Rtb::RES_INCREMENT_RESTORE] = 0.;
        // Compose response
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
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
        // Prepare data
        $responseDataProducer = $this->getResponseDataProducer();
        $auctioneerData = array_replace(
            $auctioneerData,
            $responseDataProducer->produceBidderAddressData($this->getRtbCurrent(), Constants\Rtb::UT_AUCTIONEER)
        );
        // Prepare previous lot info
        $runningAuctionLot = $this->getAuctionLot();
        if ($runningAuctionLot) {
            if ($runningAuctionLot->isSold()) {
                if ($this->runningLotItem->hasWinningBidder()) {
                    $winningBidderId = $this->runningLotItem->WinningBidderId;
                    $username = $this->getUserLoader()->load($winningBidderId)->Username;
                    $auctionBidder = $this->getAuctionBidderLoader()->load($winningBidderId, $this->getAuctionId(), true);
                    $bidderNum = $auctionBidder ? $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum) : '';
                    $auctioneerData[Constants\Rtb::RES_SOLD_LOT_WINNER_USERNAME] = $username;
                    $auctioneerData[Constants\Rtb::RES_SOLD_LOT_WINNER_BIDDER_NO] = [$winningBidderId => $bidderNum]; //user id, bidder num
                }
                $auctioneerData[Constants\Rtb::RES_SOLD_LOT_HAMMER_PRICES] = [$this->runningLotItem->Id => $this->runningLotItem->HammerPrice];
            } elseif ($runningAuctionLot->isUnsold()) {
                $auctioneerData[Constants\Rtb::RES_SOLD_LOT_HAMMER_PRICES] = [$this->runningLotItem->Id => Constants\Lot::$lotStatusNames[$runningAuctionLot->LotStatusId]];
            } elseif ($runningAuctionLot->isActive()) {
                $auctioneerData[Constants\Rtb::RES_SOLD_LOT_HAMMER_PRICES] = [$this->runningLotItem->Id => ''];
            }
            $auctioneerData[Constants\Rtb::RES_SOLD_LOT_NO] = [$this->runningLotItem->Id => $this->getLotRenderer()->renderLotNo($runningAuctionLot)];
        }
        // Compose response
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $auctioneerData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;
        return $responses;
    }

    /**
     * Check if new switched lot exists
     * It can be deleted
     * @return bool
     */
    protected function checkSwitchingLot(): bool
    {
        if (in_array($this->goToLotItemId, [self::NEXT, self::PREV], true)) {
            return true;
        }
        $isFound = $this->getAuctionLotExistenceChecker()->exist((int)$this->goToLotItemId, $this->getAuctionId());
        return $isFound;
    }
}
