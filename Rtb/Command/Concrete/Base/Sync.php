<?php

namespace Sam\Rtb\Command\Concrete\Base;

use RtbCurrent;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\AuctionLot\Load\PositionalAuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\Bidder\Outstanding\BidderOutstandingHelper;
use Sam\Core\Constants;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\Group\GroupingHelperAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\State\Reset\RtbStateResetterAwareTrait;
use Sam\Rtb\State\RtbStateUpdaterCreateTrait;
use Sam\Rtb\User\UserHashGeneratorCreateTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class Sync
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class Sync extends CommandBase implements RtbCommandHelperAwareInterface
{
    use GroupingHelperAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use LotRendererAwareTrait;
    use PositionalAuctionLotLoaderAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbRendererCreateTrait;
    use RtbStateResetterAwareTrait;
    use RtbStateUpdaterCreateTrait;
    use UrlBuilderAwareTrait;
    use UserHashGeneratorCreateTrait;

    protected bool $alreadySynchronized = true;
    protected ?array $lotData = null;
    protected bool $isOutOfSyncMessage = false;     // show "Out of sync" message in Bidder's console

    /**
     * Specific to viewer-repeater case. We identify SyncQ sender by resource id. (SAM-10677)
     * @var int|null
     */
    protected ?int $viewerResourceId = null;

    /**
     * Show "Out of sync" message in Bidder's console
     * @param bool $is
     * @return static
     */
    public function enableOutOfSyncMessage(bool $is): static
    {
        $this->isOutOfSyncMessage = $is;
        return $this;
    }

    /**
     * Specific to viewer-repeater case. We identify SyncQ sender by resource id. (SAM-10677)
     * @param int|null $viewerResourceId
     * @return $this
     */
    public function setViewerResourceId(?int $viewerResourceId): static
    {
        $this->viewerResourceId = $viewerResourceId;
        return $this;
    }

    public function execute(): void
    {
        if ($this->getUserType() === Constants\Rtb::UT_CLERK) {
            $this->syncClerk();
        } elseif ($this->getUserType() === Constants\Rtb::UT_BIDDER) {
            $this->syncBidder();
        } elseif ($this->getUserType() === Constants\Rtb::UT_VIEWER) {
            $this->syncViewer();
        } elseif ($this->getUserType() === Constants\Rtb::UT_PROJECTOR) {
            $this->syncProjector();
        } elseif ($this->getUserType() === Constants\Rtb::UT_AUCTIONEER) {
            $this->syncAuctioneer();
        } elseif ($this->getUserType() === Constants\Rtb::UT_SYSTEM) {
            $this->syncSystem();
        }
    }

    protected function createResponses(): void
    {
        if ($this->getUserType() === Constants\Rtb::UT_CLERK) {
            $this->createResponsesForClerk();
        } elseif ($this->getUserType() === Constants\Rtb::UT_BIDDER) {
            $this->createResponsesForBidder();
        } elseif ($this->getUserType() === Constants\Rtb::UT_VIEWER) {
            $this->createResponsesForViewer();
        } elseif ($this->getUserType() === Constants\Rtb::UT_PROJECTOR) {
            $this->createResponsesForProjector();
        } elseif ($this->getUserType() === Constants\Rtb::UT_AUCTIONEER) {
            $this->createResponsesForAuctioneer();
        } elseif ($this->getUserType() === Constants\Rtb::UT_SYSTEM) {
            $this->createResponsesForSystem();
        }
    }

    protected function syncClerk(): void
    {
        $this->getLogger()->log('Admin clerk sync auction info' . composeSuffix(['a' => $this->getAuctionId()]));
        $this->refreshRtbState();
        $this->createStaticMessage();
    }

    /**
     * @return void
     */
    protected function syncBidder(): void
    {
        $this->getLogger()->log("Bidder {$this->bidderNum} sync auction info ");
        $this->refreshRtbState();
        $this->createStaticMessage();
    }

    protected function syncViewer(): void
    {
        $this->getLogger()->log("Viewer sync auction info");
        $this->refreshRtbState();
        $this->createStaticMessage();
    }

    protected function syncProjector(): void
    {
        $this->getLogger()->log("Projector sync auction info");
        $this->refreshRtbState();
        $this->createStaticMessage();
    }

    protected function syncSystem(): void
    {
        $this->getLogger()->log("System sync auction info");
        $this->refreshRtbState();
        $this->createStaticMessage();
    }

    protected function syncAuctioneer(): void
    {
        $this->getLogger()->log("Auctioneer sync auction info");
        $this->refreshRtbState();
        $this->createStaticMessage();
    }

    /**
     * Perform rtb state update
     */
    protected function refreshRtbState(): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        $rtbCurrent = $this->updateRunningLot($rtbCurrent);
        $rtbCurrent = $this->createRtbStateUpdater()->update($rtbCurrent, $this->getAuction()->AccountId, $this->getViewLanguageId());
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
    }

    protected function createStaticMessage(): void
    {
        $data = $this->produceLotData();
        [$quantity, $isQuantityXMoney] = $this->getRtbCommandHelper()->extractQuantityValuesFromLotData($data);
        $message = $this->createRtbRenderer()->renderQuantityHtml($this->getAuction(), $this->getLotItemId(), $quantity, $isQuantityXMoney);
        if (isset($data[Constants\Rtb::RES_STATUS])) {
            $message .= $this->createRtbRenderer()->renderAuctioneerMessage($data[Constants\Rtb::RES_STATUS], $this->getAuction());
        }
        // Public static message
        if (in_array(
            $this->getUserType(),
            [
                Constants\Rtb::UT_CLERK,
                Constants\Rtb::UT_BIDDER,
                Constants\Rtb::UT_VIEWER,
                Constants\Rtb::UT_AUCTIONEER,
                Constants\Rtb::UT_PROJECTOR,
                Constants\Rtb::UT_SYSTEM,
            ],
            true
        )
        ) {
            $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message, false, true);
        }
        // Admin static message
        if (in_array(
            $this->getUserType(),
            [
                Constants\Rtb::UT_CLERK,
                Constants\Rtb::UT_SYSTEM,
            ],
            true
        )
        ) {
            $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message, true, true);
        }
    }

    protected function createResponsesForClerk(): void
    {
        $responses[Constants\Rtb::RT_SINGLE] = $this->buildJsonResponseForAdmin();
        $this->setResponses($responses);
    }

    /**
     * @return string
     */
    protected function buildJsonResponseForAdmin(): string
    {
        $rtbCurrent = $this->getRtbCurrent();
        $data = $this->produceLotData();
        $currentBidAmount = (float)$data[Constants\Rtb::RES_CURRENT_BID];
        $highBidUserId = (int)$data[Constants\Rtb::RES_CURRENT_BIDDER_USER_ID];
        $responseDataProducer = $this->getResponseDataProducer();
        $data = array_merge(
            $data,
            $responseDataProducer->produceAdminSideData($rtbCurrent),
            $responseDataProducer->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_CLERK, ['highBidUserId' => $highBidUserId]),
            $responseDataProducer->produceIncrementData($rtbCurrent, ['currentBid' => $currentBidAmount])
        );

        $auctionLot = $this->getAuctionLot();
        $currentBid = $auctionLot ? $this->createBidTransactionLoader()->loadById($auctionLot->CurrentBidId) : null;
        $data[Constants\Rtb::RES_IS_CURRENT_BID] = $currentBid && $currentBid->FloorBidder;
        if ($this->getAuction()->isClosed()) {
            $urlBuilder = $this->getUrlBuilder();
            $auctionId = $this->getAuctionId();
            $data[Constants\Rtb::RES_AUCTION_RESULT_URL] = $urlBuilder->build(
                AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_LIVE_AUCTION_RESULT, $auctionId)
            );
            $data[Constants\Rtb::RES_AUCTION_BID_HISTORY_URL] = $urlBuilder->build(
                AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_BID_HISTORY_CSV, $auctionId)
            );
        }

        $data = $this->addPendingActionData($data);
        //check if the current bid is an absentee bid
        $data[Constants\Rtb::RES_IS_ABSENTEE_BID] = $rtbCurrent->AbsenteeBid;
        $data[Constants\Rtb::RES_IS_RELOAD_CATALOG] = true;  // Reload lot catalog
        $data[Constants\Rtb::RES_LOT_AUTO_START] = (int)$rtbCurrent->AutoStart;

        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseJson = json_encode($response);
        return $responseJson;
    }

    protected function createResponsesForBidder(): void
    {
        $responses[Constants\Rtb::RT_SINGLE] = $this->buildJsonResponseForBidder();
        $this->setResponses($responses);
    }

    /**
     * @return string
     */
    protected function buildJsonResponseForBidder(): string
    {
        $data = $this->produceLotData();
        $data = $this->getResponseHelper()->removeSensitiveData($data);

        if ($this->getAuction()->isClosed()) {
            $data[Constants\Rtb::RES_MY_WON_ITEMS_URL] = $this->getUrlBuilder()->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_ITEMS_WON)
            );
        }

        $data = $this->addPendingActionData($data);

        $auctionBidder = $this->getAuctionBidderLoader()->load($this->getEditorUserId(), $this->getAuctionId(), true);
        if (
            $auctionBidder
            && BidderOutstandingHelper::new()->isLimitExceeded($auctionBidder)
        ) {
            $data[Constants\Rtb::RES_IS_OUTSTANDING_LIMIT_EXCEEDED] = true;
        }

        if ($this->isOutOfSyncMessage) {
            $langOutOfSync = $this->translate('BIDDERCLIENT_OUTOFSYNC');
            $data[Constants\Rtb::RES_MESSAGE] = $this->getRtbGeneralHelper()
                ->clean('<span class="bid-denied">' . $langOutOfSync . '</span>');
        }

        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseJson = json_encode($response);
        return $responseJson;
    }

    protected function createResponsesForViewer(): void
    {
        $responses[Constants\Rtb::RT_SINGLE] = $this->buildJsonResponseForViewer();
        $this->setResponses($responses);
    }

    /**
     * It is also used for Projector
     * @return string
     */
    protected function buildJsonResponseForViewer(): string
    {
        $data = $this->produceLotData();
        $data = $this->getResponseHelper()->removeSensitiveData($data);

        if ($this->viewerResourceId) {
            // Specific to viewer-repeater case. We identify SyncQ sender by resource id. (SAM-10677)
            $data[Constants\Rtb::RES_VIEWER_RESOURCE_ID] = $this->viewerResourceId;
        }
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseJson = json_encode($response);
        return $responseJson;
    }

    protected function createResponsesForProjector(): void
    {
        $responses[Constants\Rtb::RT_SINGLE] = $this->buildJsonResponseForViewer();
        $this->setResponses($responses);
    }

    protected function createResponsesForAuctioneer(): void
    {
        $responses[Constants\Rtb::RT_SINGLE] = $this->buildJsonResponseForAuctioneer();
        $this->setResponses($responses);
    }

    /**
     * @return string
     */
    protected function buildJsonResponseForAuctioneer(): string
    {
        $rtbCurrent = $this->getRtbCurrent();
        $data = $this->produceLotData();
        $highBidUserId = (int)$data[Constants\Rtb::RES_CURRENT_BIDDER_USER_ID];
        $responseDataProducer = $this->getResponseDataProducer();
        $data = array_merge(
            $data,
            $responseDataProducer->produceAdminSideData($rtbCurrent),
            $responseDataProducer->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_AUCTIONEER, ['highBidUserId' => $highBidUserId])
        );

        if ($this->getAuction()->isClosed()) {
            $urlBuilder = $this->getUrlBuilder();
            $auctionId = $this->getAuctionId();
            $data[Constants\Rtb::RES_AUCTION_RESULT_URL] = $urlBuilder->build(
                AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_LIVE_AUCTION_RESULT, $auctionId)
            );
            $data[Constants\Rtb::RES_AUCTION_BID_HISTORY_URL] = $urlBuilder->build(
                AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_BID_HISTORY_CSV, $auctionId)
            );
        }

        $data = array_merge_recursive($data, $this->produceLastSoldLotData());

        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S, Constants\Rtb::RES_DATA => $data];
        $responseJson = json_encode($response);
        return $responseJson;
    }

    protected function createResponsesForSystem(): void
    {
        $responseJsonForViewer = $this->buildJsonResponseForViewer();
        $responses[Constants\Rtb::RT_VIEWER] = $responseJsonForViewer;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJsonForViewer;
        $responses[Constants\Rtb::RT_BIDDER] = $this->buildJsonResponseForBidder();
        $responses[Constants\Rtb::RT_AUCTIONEER] = $this->buildJsonResponseForAuctioneer();
        $responses[Constants\Rtb::RT_CLERK] = $this->buildJsonResponseForAdmin();
        $this->setResponses($responses);
    }

    /**
     * Initialize running lot, if it is absent or is not runnable
     * @param RtbCurrent $rtbCurrent
     * @return RtbCurrent
     */
    protected function updateRunningLot(RtbCurrent $rtbCurrent): RtbCurrent
    {
        $auction = $this->getAuction();
        $this->alreadySynchronized = false;

        if ($auction->isStartedOrPaused()) {
            if (!$rtbCurrent->LotItemId) {
                $auctionLot = $this->getRtbCommandHelper()->findNextAuctionLot($rtbCurrent);
                $rtbCurrent->LotItemId = $auctionLot->LotItemId ?? null;
                if ($auction->isStarted()) {
                    $rtbCurrent = $this->getRtbCommandHelper()->activateLot(
                        $rtbCurrent,
                        Constants\Rtb::LA_BY_AUTO_START,
                        $this->detectModifierUserId()
                    );
                }
            } else {
                $auctionLot = $this->getAuctionLot();
                if (!$auctionLot) {
                    $auctionLot = $this->getRtbCommandHelper()->findNextAuctionLot($rtbCurrent);
                } else {
                    $this->alreadySynchronized = true;
                }
            }

            if (!$auctionLot) {
                $targetLotStatuses = [Constants\Lot::LS_ACTIVE];
                if ($auction->isLive()) {
                    $targetLotStatuses[] = Constants\Lot::LS_UNSOLD;
                }
                $auctionLot = $this->getPositionalAuctionLotLoader()
                    ->loadFirstLot($this->getAuctionId(), $targetLotStatuses);
            }

            $runningLotItemId = $auctionLot->LotItemId ?? null;
            if ($rtbCurrent->LotItemId !== $runningLotItemId) {
                // If running lot is changed, then we should drop previous rtb state
                log_info(
                    'Reset rtb state, because running lot is changed'
                    . composeSuffix(['a' => $rtbCurrent->AuctionId, 'li' => $runningLotItemId])
                );
                $rtbCurrent = $this->getRtbStateResetter()->cleanState($rtbCurrent, $this->detectModifierUserId());
                $rtbCurrent->LotItemId = $runningLotItemId;
                $this->setAuctionLot($auctionLot);
            }
        } else {
            if ($rtbCurrent->LotItemId) {
                // no running lot for not running auction
                $rtbCurrent->LotItemId = null;
                $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
            }
        }
        return $rtbCurrent;
    }

    /**
     * Add client's data about pending action
     * @param array $data
     * @return array
     */
    protected function addPendingActionData(array $data): array
    {
        $rtbCurrent = $this->getRtbCurrent();
        if ($rtbCurrent->PendingAction) {
            $data[Constants\Rtb::RES_PENDING_ACTION] = $rtbCurrent->PendingAction;
            if ($rtbCurrent->PendingAction === Constants\Rtb::PA_SELECT_GROUPED_LOTS) {
                $data = $this->addDataForSelectGroupedLots($data);
            } elseif ($rtbCurrent->PendingAction === Constants\Rtb::PA_SELECT_BUYER_BY_AGENT) {
                $data = $this->addDataForSelectBuyer($data);
            }
        }
        return $data;
    }

    /**
     * Add data for "Select Grouped Lots" response
     * @param array $data
     * @return array
     */
    protected function addDataForSelectGroupedLots(array $data): array
    {
        $rtbCurrent = $this->getRtbCurrent();
        if ($rtbCurrent->LotGroup === Constants\Rtb::GROUP_QUANTITY) {
            $auctionLot = $this->getAuctionLot();
            if (!$auctionLot) {
                log_error(
                    "Available auction lot not found"
                    . composeSuffix(['li' => $rtbCurrent->LotItemId, 'a' => $rtbCurrent->AuctionId])
                );
                return $data;
            }
            $currentBid = $this->createBidTransactionLoader()->loadById($auctionLot->CurrentBidId);
            if (!$currentBid) {
                log_error(
                    "Available current bid not found, when composing data for grouped lots selecting response"
                    . composeSuffix(['bt' => $auctionLot->CurrentBidId, 'ali' => $auctionLot->Id])
                );
            } else {
                $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($auctionLot->LotItemId, $auctionLot->AuctionId);
                $data[Constants\Rtb::RES_CURRENT_BID_FULL_AMOUNT] = $auctionLot->multiplyQuantityEffectively($currentBid->Bid, $quantityScale);
                $data[Constants\Rtb::RES_GROUP_LOT_QUANTITY] = $this->getGroupingHelper()->countGroup($this->getAuctionId());
            }
        }
        $groupUserBidder = $this->getUserLoader()->loadBidder($rtbCurrent->GroupUser);
        $groupWinnerUserId = $groupUserBidder->AgentId ?? (int)$rtbCurrent->GroupUser;
        $groupWinnerUserHash = $this->createUserHashGenerator()->generate(
            $groupWinnerUserId,
            $rtbCurrent->LotItemId,
            $rtbCurrent->AuctionId
        );
        $data[Constants\Rtb::RES_GROUP_WINNER_USER_HASH] = $groupWinnerUserHash;
        return $data;
    }

    /**
     * Add data for "Select Buyer by Agent" response
     * @param array $data
     * @return array
     */
    protected function addDataForSelectBuyer(array $data): array
    {
        $rtbCurrent = $this->getRtbCurrent();
        if ($this->getUserType() === Constants\Rtb::UT_BIDDER) {
            $winningAgentUserHash = $this->createUserHashGenerator()->generate(
                $rtbCurrent->BuyerUser,
                $rtbCurrent->LotItemId,
                $rtbCurrent->AuctionId
            );
            $data[Constants\Rtb::RES_WINNING_AGENT_USER_HASH] = $winningAgentUserHash;
        }
        return $data;
    }

    /**
     * Return cached array from ResponseHelper::getLotData()
     * @return array
     */
    protected function produceLotData(): array
    {
        if ($this->lotData === null) {
            $rtbCurrent = $this->getRtbCurrent();
            $this->lotData = $this->getResponseHelper()->getLotData($rtbCurrent);
        }
        return $this->lotData;
    }

    /**
     * Load and collect response data with RES_SOLD_LOT_* values
     * Filling prev lot auctionBidder hammer prices. Load last sold lot only (based on sold date)!
     * @return array
     */
    protected function produceLastSoldLotData(): array
    {
        $lastSoldAuctionLot = $this->getPositionalAuctionLotLoader()->loadLastSoldAuctionLot($this->getAuctionId());
        if (!$lastSoldAuctionLot) {
            return [];
        }

        $lastSoldLotItem = $this->getLotItemLoader()->load($lastSoldAuctionLot->LotItemId);
        if (!$lastSoldLotItem) {
            log_error(
                "Available last sold lot item not found"
                . composeSuffix(['li' => $lastSoldAuctionLot->LotItemId, 'ali' => $lastSoldAuctionLot->Id])
            );
            return [];
        }

        $data = [];
        $auctionBidder = $this->getAuctionBidderLoader()->load($lastSoldLotItem->WinningBidderId, $this->getAuctionId(), true);
        $bidderNum = $auctionBidder ? $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum) : '';
        $lotNo = $this->getLotRenderer()->renderLotNo($lastSoldAuctionLot);
        $data[Constants\Rtb::RES_SOLD_LOT_NO][$lastSoldLotItem->Id] = $lotNo;
        $data[Constants\Rtb::RES_SOLD_LOT_HAMMER_PRICES][$lastSoldLotItem->Id] = $lastSoldLotItem->HammerPrice;
        $data[Constants\Rtb::RES_SOLD_LOT_WINNER_BIDDER_NO][$lastSoldLotItem->WinningBidderId] = $bidderNum;
        $bidderUser = $this->getUserLoader()->load($lastSoldLotItem->WinningBidderId);
        $data[Constants\Rtb::RES_SOLD_LOT_WINNER_USERNAME] = $bidderUser->Username ?? '';
        return $data;
    }
}
