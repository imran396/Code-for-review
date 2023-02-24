<?php

namespace Sam\Rtb\Command\Concrete\Base;

use AuctionLotItem;
use LotItem;
use Sam\AuctionLot\Date\AuctionLotDateAssignorCreateTrait;
use Sam\AuctionLot\Date\Dto\LiveHybridAuctionLotDates;
use Sam\Core\Constants;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Catalog\Bidder\Manage\BidderCatalogManagerFactoryCreateTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\Group\GroupingHelperAwareTrait;
use Sam\Rtb\Lot\Note\Save\LotNoteSaverCreateTrait;
use Sam\Rtb\LotInfo\LotInfoServiceAwareTrait;
use Sam\Rtb\State\Reset\RtbStateResetterAwareTrait;
use Sam\Rtb\State\RtbStateUpdaterCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class PassLot
 * @package Sam\Rtb\Command\Concrete\Base
 * @method LotItem getLotItem() - existence checked in checkRunningLot()
 * @method AuctionLotItem getAuctionLot() - existence checked in checkRunningLot()
 */
abstract class PassLot extends CommandBase implements RtbCommandHelperAwareInterface
{
    use AuctionLotDateAssignorCreateTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use BidderCatalogManagerFactoryCreateTrait;
    use GroupingHelperAwareTrait;
    use LotInfoServiceAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotItemWriteRepositoryAwareTrait;
    use LotNoteSaverCreateTrait;
    use LotRendererAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbRendererCreateTrait;
    use RtbStateResetterAwareTrait;
    use RtbStateUpdaterCreateTrait;

    // Externally defined properties

    protected string $generalNote = '';

    // Internally defined properties

    protected string $langLotPassed = '';
    protected array $soldStatuses = [];
    protected string $lotStatus = '';
    protected string $lotNo = '';
    /** @var string[] */
    protected array $unsoldLotNos = [];
    protected bool $isLastLot = false;
    protected string $gameStatus = '';

    /**
     * @param string $generalNote
     * @return static
     */
    public function setGeneralNote(string $generalNote): static
    {
        $this->generalNote = $generalNote;
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

        $auctionLot = $this->createLotNoteSaver()->save(
            $this->generalNote,
            $this->getLotItemId(),
            $this->getAuctionId(),
            $this->detectModifierUserId()
        );  // SAM-4260
        $this->setAuctionLot($auctionLot);

        $this->initTranslations($this->getAuction()->AccountId);
        $rtbCurrent = $this->getRtbCurrent();

        log_debug(
            'Trying to pass lot'
            . composeSuffix(['a' => $rtbCurrent->AuctionId, 'li' => $rtbCurrent->LotItemId,])
        );

        $rtbCurrent->BidCountdown = ''; // Reset bid countdown
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        $lotItem = $this->getLotItem();

        $auctionLot = $this->getAuctionLot();
        $auctionLot->Reload();
        $this->lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);

        $auctionLot->toUnsold();
        $auctionLotDates = LiveHybridAuctionLotDates::new()->setEndDate($this->getCurrentDateUtc());
        $auctionLot = $this->createAuctionLotDateAssignor()->assignForLiveOrHybrid($auctionLot, $auctionLotDates, $this->detectModifierUserId());

        $lotItem->wipeOutSoldInfo();
        $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $this->detectModifierUserId());

        $this->lotStatus = sprintf($this->langLotPassed, $this->lotNo);

        $this->soldStatuses = [];
        $this->soldStatuses[$lotItem->Id] = 'Unsold';
        $this->unsoldLotNos[$lotItem->Id] = $this->lotNo;

        if ($rtbCurrent->LotGroup) { // Has group
            $rtbCurrentGroupRecords = $this->getGroupingHelper()
                ->loadGroups($this->getAuctionId(), null, [$rtbCurrent->LotItemId]);
            foreach ($rtbCurrentGroupRecords as $rtbCurrentGroup) {
                $groupAuctionLot = $this->getAuctionLotLoader()
                    ->load($rtbCurrentGroup->LotItemId, $this->getAuctionId(), true);
                if ($groupAuctionLot) {
                    $lotNoFromGroup = $this->getLotRenderer()->renderLotNo($groupAuctionLot);
                    $groupAuctionLot->toUnsold();
                    $this->getAuctionLotItemWriteRepository()->saveWithModifier($groupAuctionLot, $this->detectModifierUserId());  // ->Save(false, true);

                    $lotItemFromGroup = $this->getLotItemLoader()->load($groupAuctionLot->LotItemId, true);
                    if (!$lotItemFromGroup) {
                        log_error(
                            'Available lot item from group not found'
                            . composeSuffix(
                                [
                                    'li' => $groupAuctionLot->LotItemId,
                                    'ali' => $groupAuctionLot->Id,
                                ]
                            )
                        );
                        continue;
                    }
                    $lotItemFromGroup->wipeOutSoldInfo();
                    $this->getLotItemWriteRepository()->saveWithModifier($lotItemFromGroup, $this->detectModifierUserId());

                    $this->soldStatuses[$lotItemFromGroup->Id] = 'Unsold';
                    $this->unsoldLotNos[$lotItemFromGroup->Id] = $lotNoFromGroup;
                    $this->lotStatus .= ',' . sprintf($this->langLotPassed, $lotNoFromGroup);
                }
            }
        }

        $catalogManager = $this->createCatalogManagerFactory()
            ->createByRtbCurrent($rtbCurrent, $this->getAuction()->AccountId, $this->getViewLanguageId());
        $catalogManager->updateRow($auctionLot);

        $this->getLotInfoService()->drop($this->getAuctionId());

        $lotItem = $this->findNextLot();
        $lotActivity = $this->isLastLot ? Constants\Rtb::LA_IDLE : Constants\Rtb::LA_BY_AUTO_START;

        $rtbCurrent = $this->getRtbCommandHelper()->switchRunningLot($rtbCurrent, $lotItem);
        $rtbCurrent = $this->getRtbStateResetter()->cleanState($rtbCurrent, $this->detectModifierUserId());
        $rtbCurrent = $this->getRtbCommandHelper()->activateLot($rtbCurrent, $lotActivity, $this->detectModifierUserId());
        $rtbCurrent = $this->createRtbStateUpdater()->update($rtbCurrent, $this->getAuction()->AccountId, $this->getViewLanguageId());
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        $this->log();

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
        $shouldClearMessageCenterLog = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::CLEAR_MESSAGE_CENTER_LOG, $this->getAuction()->AccountId);
        if ($shouldClearMessageCenterLog) {
            $this->getMessenger()->clearStaticMessage($this->getAuctionId(), true);
            $this->getMessenger()->clearStaticMessage($this->getAuctionId());
        }

        $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($this->lotStatus, $this->getAuction());
        if ($gameStatus) {
            $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($gameStatus, $this->getAuction())
                . $messageHtml;
        }

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
        $this->lotStatus .= '| ' . $this->gameStatus;
        $data[Constants\Rtb::RES_STATUS] = $this->lotStatus;
        $data[Constants\Rtb::RES_SOLD_LOT_HAMMER_PRICES] = $this->soldStatuses;

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
            $this->lotStatus,
            $shouldClearMessageCenter
        );

        if (!$this->isLastLot) {
            $isTextMsgEnabled = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::TEXT_MSG_ENABLED, $this->getAuction()->AccountId);
            $responses = $this->getResponseHelper()
                ->addSmsTextResponse($responses, $this->getAuction(), $rtbCurrent->LotItemId, $isTextMsgEnabled);
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
        $rtbCurrent = $this->getRtbCurrent();
        $currentBidAmount = (float)$clerkData[Constants\Rtb::RES_CURRENT_BID];
        $responseDataProducer = $this->getResponseDataProducer();
        $clerkData = array_merge(
            $clerkData,
            $responseDataProducer->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_CLERK),
            $responseDataProducer->produceIncrementData($rtbCurrent, ['currentBid' => $currentBidAmount])
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
        $auctioneerData[Constants\Rtb::RES_SOLD_LOT_NO] = $this->unsoldLotNos;
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
     * @param int $accountId
     */
    protected function initTranslations(int $accountId): void
    {
        $this->getTranslator()->setAccountId($accountId);
        $this->langLotPassed = $this->translate('BIDDERCLIENT_MSG_LOTPASSED');
    }

    /**
     * Log to auction trail
     */
    protected function log(): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        $message = $this->getLogger()->getUserRoleName($this->getUserType());
        $message .= ' passes on lot ' . $this->lotNo . composeSuffix(['li' => $rtbCurrent->LotItemId]);
        $this->getLogger()->log($message);
    }

    /**
     * Finds next lot. Return current lot, if there is no next lots
     * @return LotItem|null
     */
    protected function findNextLot(): ?LotItem
    {
        $rtbCurrent = $this->getRtbCurrent();
        $isActiveOnly = (bool)$rtbCurrent->LotGroup;
        $lotItem = $this->getRtbCommandHelper()->findNextLotItem($rtbCurrent, $isActiveOnly);
        $this->isLastLot = !$lotItem;
        if ($this->isLastLot) {
            // if no next lot, then use running lot
            $lotItem = $this->getLotItem();
        }
        return $lotItem;
    }
}
