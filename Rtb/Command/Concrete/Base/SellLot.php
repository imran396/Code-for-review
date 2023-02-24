<?php

namespace Sam\Rtb\Command\Concrete\Base;

use AuctionBidder;
use AuctionLotItem;
use BidTransaction;
use LotItem;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\Bidder\Outstanding\BidderOutstandingHelper as OutstandingHelper;
use Sam\Core\Constants;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Catalog\Bidder\Manage\BidderCatalogManagerFactoryCreateTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\Group\GroupingHelperAwareTrait;
use Sam\Rtb\Lot\Note\Save\LotNoteSaverCreateTrait;
use Sam\Rtb\LotInfo\LotInfoServiceAwareTrait;
use Sam\Rtb\Sell\Internal\FloorBidder\FloorBidderProducerCreateTrait;
use Sam\Rtb\Sell\LotSellerAwareTrait;
use Sam\Rtb\State\PendingAction\PendingActionUpdaterCreateTrait;
use Sam\Rtb\State\Reset\RtbStateResetterAwareTrait;
use Sam\Rtb\State\RtbStateUpdaterCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\BidTransaction\BidTransactionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;
use Sam\User\Privilege\Validate\BidderPrivilegeCheckerAwareTrait;

/**
 * Class SellLot
 * @package Sam\Rtb\Command\Concrete\Base
 * @method AuctionLotItem getAuctionLot() - existence checked in checkRunningLot()
 * @method LotItem getLotItem() - existence checked in checkRunningLot()
 */
abstract class SellLot extends CommandBase implements RtbCommandHelperAwareInterface
{
    use AdminPrivilegeCheckerAwareTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use BidTransactionWriteRepositoryAwareTrait;
    use BidderPrivilegeCheckerAwareTrait;
    use BidderCatalogManagerFactoryCreateTrait;
    use FloorBidderProducerCreateTrait;
    use GroupingHelperAwareTrait;
    use LotInfoServiceAwareTrait;
    use LotItemWriteRepositoryAwareTrait;
    use LotNoteSaverCreateTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use LotRendererAwareTrait;
    use LotSellerAwareTrait;
    use PendingActionUpdaterCreateTrait;
    use ResponseDataProducerAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbRendererCreateTrait;
    use RtbStateResetterAwareTrait;
    use RtbStateUpdaterCreateTrait;

    // Externally defined properties

    protected string $generalNote = '';
    protected string $winningBidderIdentifier = '';

    // Internally defined properties

    protected bool $isLastLot = false;
    protected bool $isFloor = false;
    protected ?BidTransaction $currentBidTransaction = null;
    protected array $lotNo = [];
    protected array $soldStatuses = [];
    protected array $bidderNums = [];
    protected string $username = '';
    protected string $publicMessage = '';
    protected string $adminMessage = '';
    protected string $lotReopenedTpl = '%s reopened lot %s to bidder';
    protected string $langAuctioneer = '';
    protected string $langReopen = '';
    protected ?AuctionLotItem $auctionLot = null;

    /**
     * @param string $generalNote
     * @return static
     */
    public function setGeneralNote(string $generalNote): static
    {
        $this->generalNote = $generalNote;
        return $this;
    }

    /**
     * IK, 2021-09: Rather ambiguous field for identifying winning bidder user. Must be refactored.
     * We expect string and integers.
     * It could be bidder# - must be numeric only,
     * or alpha-numeric username's suffix, when creating or loading floor bidder record by username pattern "auction_<auction.id>_floor_<suffix>"
     * or user.id see \Sam\Rtb\Hybrid\Run\RunningAuction\SingleAuctionProcessor::sell()
     * @param string $winningBidderIdentifier
     * @return static
     */
    public function setWinningBidderIdentifier(string $winningBidderIdentifier): static
    {
        $this->winningBidderIdentifier = $winningBidderIdentifier;
        return $this;
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $this->getLotSeller()->setLogger($this->getLogger());

        if (
            !$this->checkConsoleSync()
            || !$this->checkRunningLot()
            || !$this->checkCurrentBid()
        ) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        $this->getTranslator()->setAccountId($this->getAuction()->AccountId);
        $rtbCurrent = $this->getRtbCurrent();

        log_debug(
            "Trying to sell lot"
            . composeSuffix(['a' => $rtbCurrent->AuctionId, 'li' => $rtbCurrent->LotItemId, 'wb' => $this->winningBidderIdentifier,])
        );

        $lotItem = $this->getLotItem();

        $rtbCurrent->BidCountdown = ''; // Reset bid countdown
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        $this->auctionLot = $this->getAuctionLotLoader()->load($this->getLotItemId(), $this->getAuctionId());
        $this->currentBidTransaction = $this->createBidTransactionLoader()->loadById($this->auctionLot->CurrentBidId);
        if (!$this->currentBidTransaction) {
            // this already checked in $this->checkCurrentBid():
            log_error(
                "Available current bid not found"
                . composeSuffix(['bt' => $this->auctionLot->CurrentBidId, 'ali' => $this->auctionLot->Id])
            );
            return;
        }

        $this->lotNo[$lotItem->Id] = $this->getLotRenderer()->renderLotNo($this->auctionLot);

        $this->isFloor = $this->currentBidTransaction->UserId === null;
        if ($this->isFloor) {
            $winningBidderNumPad = $this->getBidderNumberPadding()->add($this->winningBidderIdentifier);
            $auctionBidder = $this->getAuctionBidderLoader()->loadByBidderNum($winningBidderNumPad, $this->getAuctionId(), true);
            $logData = [
                'a' => $this->getAuctionId(),
                'bidder# identifier' => $this->winningBidderIdentifier,
                'bidder# padded' => $winningBidderNumPad,
            ];

            if ($auctionBidder) {
                $logData += [
                    'u' => $auctionBidder->UserId,
                    'ab' => $auctionBidder->Id
                ];
                log_debug("Auction bidder is found by bidder# in auction" . composeSuffix($logData));
                $isOutstandingExceeded = OutstandingHelper::new()->isLimitExceeded($auctionBidder);
                if ($isOutstandingExceeded) {
                    /**
                     * If selected bidder has exceeded outstanding limit,
                     * then show warning message and don't sell lot (SAM-2710)
                     */
                    $rtbCurrent = $this->getRtbCommandHelper()->activateLot(
                        $rtbCurrent,
                        Constants\Rtb::LA_STARTED,
                        $this->detectModifierUserId()
                    );    // lot should become active again
                    $rtbCurrent = $this->createPendingActionUpdater()->update($rtbCurrent, null);
                    $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
                    // "Lot re-opened" message
                    $this->langReopen = sprintf(
                        $this->translate('BIDDERCLIENT_LOTREOPENED'),
                        $this->getLotRenderer()->renderLotNo($this->auctionLot)
                    );
                    $this->logLotReopened();
                    $this->langReopen = $this->createRtbRenderer()->renderAuctioneerMessage($this->langReopen, $this->getAuction());
                    $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $this->langReopen, true);
                    $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $this->langReopen);

                    $this->setResponses($this->produceOutstandingExceededResponses($auctionBidder));
                    return;
                }

                $this->currentBidTransaction->UserId = $auctionBidder->UserId;
                $this->bidderNums[$auctionBidder->UserId] = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
                $this->username = $this->getUserLoader()->load($auctionBidder->UserId)->Username;
            } else {
                if ($this->winningBidderIdentifier) {
                    $isAutoCreateFloorBidderRecord = (bool)$this->getSettingsManager()
                        ->get(Constants\Setting::AUTO_CREATE_FLOOR_BIDDER_RECORD, $this->getAuction()->AccountId);
                    $hasPrivilegeToCreateBidder = $this->getAdminPrivilegeChecker()
                        ->initByUserId($this->getEditorUserId())
                        ->hasSubPrivilegeForCreateBidder();
                    if (
                        $isAutoCreateFloorBidderRecord
                        && $hasPrivilegeToCreateBidder
                    ) {
                        $floorAuctionBidder = $this->createFloorBidderProducer()->create(
                            $this->getAuctionId(),
                            $this->winningBidderIdentifier,
                            $this->getEditorUserId(),
                            $this->getAuction()->AccountId
                        );
                        if ($floorAuctionBidder) {
                            $this->currentBidTransaction->UserId = $floorAuctionBidder->UserId;
                            log_info(
                                "Created floor user"
                                . composeSuffix(['u' => $floorAuctionBidder->UserId, 'bidder#' => $floorAuctionBidder->BidderNum])
                                . " and assign his user id to high winning bid transaction that was for floor bidder"
                            );
                            $this->bidderNums[$floorAuctionBidder->UserId] = $this->getBidderNumberPadding()->clear($floorAuctionBidder->BidderNum);
                            $this->username = $this->getUserLoader()->load($floorAuctionBidder->UserId)->Username;
                        }
                    }
                }
            }
            $this->currentBidTransaction->FloorBidder = true;
            $this->getBidTransactionWriteRepository()->saveWithModifier($this->currentBidTransaction, $this->detectModifierUserId());
        }
        $this->getAuctionLotItemWriteRepository()->saveWithModifier($this->auctionLot, $this->detectModifierUserId()); // ->Save(false, true);
        $auctionLot = $this->createLotNoteSaver()->save(
            $this->generalNote,
            $this->getLotItemId(),
            $this->getAuctionId(),
            $this->detectModifierUserId(),
            $this->winningBidderIdentifier
        );    // SAM-4260
        $this->setAuctionLot($auctionLot);
        $hasPrivilegeForAgent = $this->getBidderPrivilegeChecker()
            ->enableReadOnlyDb(true)
            ->initByUserId($this->currentBidTransaction->UserId)
            ->hasPrivilegeForAgent();
        if ($hasPrivilegeForAgent) {
            if ($this->continueToSelectBuyerByAgent()) {
                return;
            }
        }

        if ($rtbCurrent->LotGroup === '') { // No group

            [$this->adminMessage, $this->publicMessage] = $this->getLotSeller()->sellLot(
                $this->auctionLot,
                null,
                $this->getUserType(),
                $this->detectModifierUserId()
            );
            $this->auctionLot->toSold();
            $this->getAuctionLotItemWriteRepository()->saveWithModifier($this->auctionLot, $this->detectModifierUserId()); // ->Save(false, true);
            $lotItem->Reload();
        } else {
            if (in_array($rtbCurrent->LotGroup, [Constants\Rtb::GROUP_CHOICE, Constants\Rtb::GROUP_QUANTITY], true)) {
                if ($this->continueToSelectGroupedLots()) {
                    return;
                }
            } else {
                [$this->adminMessage, $this->publicMessage, $this->soldStatuses] = $this->getLotSeller()->sellGroup(
                    $this->auctionLot,
                    $this->winningBidderIdentifier,
                    $this->getUserType(),
                    $this->detectModifierUserId()
                );
                $lotItem->Reload();
            }
        }

        $this->soldStatuses[$lotItem->Id] = $lotItem->HammerPrice;

        $catalogManager = $this->createCatalogManagerFactory()
            ->createByRtbCurrent($rtbCurrent, $this->getAuction()->AccountId, $this->getViewLanguageId());
        $catalogManager->updateRow($this->auctionLot);

        if (!$this->currentBidTransaction->FloorBidder) {
            $floorAuctionBidder = $this->getAuctionBidderLoader()
                ->load($this->currentBidTransaction->UserId, $this->getAuctionId(), true);
            $this->bidderNums[$this->currentBidTransaction->UserId] = $this->getBidderNumberPadding()
                ->clear($floorAuctionBidder->BidderNum);
            $this->username = $this->getUserLoader()->load($this->currentBidTransaction->UserId)->Username;
            unset($floorAuctionBidder);
        }

        $this->getLotInfoService()->drop($this->getAuctionId());

        $lotItem = $this->findNextLot();
        $lotActivity = $this->isLastLot ? Constants\Rtb::LA_IDLE : Constants\Rtb::LA_BY_AUTO_START;

        $rtbCurrent = $this->getRtbCommandHelper()->switchRunningLot($rtbCurrent, $lotItem);
        $rtbCurrent = $this->getRtbStateResetter()
            ->enableUngroup(false)
            ->cleanState($rtbCurrent, $this->detectModifierUserId());
        $rtbCurrent = $this->getRtbCommandHelper()->activateLot($rtbCurrent, $lotActivity, $this->detectModifierUserId());
        $rtbCurrent = $this->createRtbStateUpdater()->update($rtbCurrent, $this->getAuction()->AccountId, $this->getViewLanguageId());
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        $this->setResponses($this->produceSoldResponses());
    }

    /**
     * Finds next lot. Return current lot, if there is no next lots
     * @return LotItem|null
     */
    protected function findNextLot(): ?LotItem
    {
        $lotItem = $this->getRtbCommandHelper()->findNextLotItem($this->getRtbCurrent());
        $this->isLastLot = !$lotItem;
        if ($this->isLastLot) {
            // if no next lot, then use running lot
            $lotItem = $this->getLotItem();
        }
        return $lotItem;
    }

    /**
     * Prepare rtb state waiting from bidder (Agent) to select buyer.
     * Define respective responses.
     * Send respective response to consoles
     * @return bool
     */
    protected function continueToSelectBuyerByAgent(): bool
    {
        $rtbCurrent = $this->getRtbCurrent();
        $rtbCurrent = $this->createPendingActionUpdater()->update($rtbCurrent, Constants\Rtb::PA_SELECT_BUYER_BY_AGENT);
        $rtbCurrent->BuyerUser = $this->currentBidTransaction->UserId;
        $rtbCurrent = $this->getRtbCommandHelper()->activateLot($rtbCurrent, Constants\Rtb::LA_IDLE, $this->detectModifierUserId());
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        $this->setResponses($this->produceSelectBuyerResponses());
        return true;
    }

    /**
     * Prepare rtb state for choosing grouped lots.
     * Define respective responses.
     * @return bool
     */
    protected function continueToSelectGroupedLots(): bool
    {
        $rtbCurrent = $this->getRtbCurrent();
        if ($this->isFloor) {
            $winningBidderNumPad = $this->getBidderNumberPadding()->add($this->winningBidderIdentifier);
            $auctionBidder = $this->getAuctionBidderLoader()->loadByBidderNum($winningBidderNumPad, $this->getAuctionId(), true);
            $isAutoCreateFloorBidderRecord = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::AUTO_CREATE_FLOOR_BIDDER_RECORD, $this->getAuction()->AccountId);
            if ($this->winningBidderIdentifier) {
                if (
                    $auctionBidder
                    && $isAutoCreateFloorBidderRecord
                ) {
                    /** Save winning bidder id and sale id on lot table
                     *  if auto create floor user is enabled on settings */
                    $lotItem = $this->getLotItem();
                    $lotItem->AuctionId = $this->getAuctionId();
                    $lotItem->WinningBidderId = $auctionBidder->UserId;
                    $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $this->detectModifierUserId());
                    log_info(
                        "Auto create floor user is enabled in lot item" . composeSuffix(
                            [
                                'wb' => $auctionBidder->UserId,
                                'sale id' => $this->getAuctionId(),
                            ]
                        )
                    );
                }
            }
        }

        $rtbCurrent = $this->createPendingActionUpdater()->update($rtbCurrent, Constants\Rtb::PA_SELECT_GROUPED_LOTS);
        $rtbCurrent->GroupUser = $this->isFloor ? null : $this->currentBidTransaction->UserId;
        $rtbCurrent = $this->getRtbCommandHelper()->activateLot($rtbCurrent, Constants\Rtb::LA_IDLE, $this->detectModifierUserId());
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        $this->setResponses($this->produceSelectGroupedLotsResponses());
        return true;
    }

    /**
     * Return response for exceeded outstandings.
     * At the moment we come to this kind of responses, only when Clerk sales lot to floor bidder,
     * then he selects existing auction bidder and this bidder's outstanding limit is exceeded.
     * @param AuctionBidder $auctionBidder
     * @return array
     */
    protected function produceOutstandingExceededResponses(AuctionBidder $auctionBidder): array
    {
        $rtbCurrent = $this->getRtbCurrent();

        // "Outstanding limit exceeded" message
        $outstandingHtml = sprintf(
            $this->translate('BIDDERCLIENT_OUTSTANDING_ALREADY_EXCEEDED_CONTACT_AUCTIONEER'),
            $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum)
        );
        $outstandingHtml = '<span style="font-weight:bold;color:#ff0000;">' . $outstandingHtml . '</span>';

        $data = $this->getResponseHelper()->getLotData($rtbCurrent);
        $data = array_merge(
            $data,
            $this->getResponseDataProducer()->produceAdminSideData($rtbCurrent)
        );

        $currentBidAmount = (float)$data[Constants\Rtb::RES_CURRENT_BID];
        $incrementData = $this->getResponseDataProducer()->produceIncrementData($rtbCurrent, ['currentBid' => $currentBidAmount]);
        $data = array_merge($data, $incrementData);

        $data[Constants\Rtb::RES_STATUS] = $outstandingHtml . "<br />\n" . $this->langReopen;
        $data = $this->getResponseHelper()->removeSensitiveData($data);
        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S, Constants\Rtb::RES_DATA => $data];
        $responseJson = json_encode($response);
        /**
         * This RT_SINGLE is Clerk console, who performs selling.
         */
        $responses[Constants\Rtb::RT_SINGLE] = $responseJson;

        /** We need to send "Lot re-opened" message to others
         * Copied code from "CancelEnterBidderNumQ" action */
        $langReopen = sprintf(
            $this->translate('BIDDERCLIENT_LOTREOPENED'),
            $this->getLotRenderer()->renderLotNo($this->auctionLot)
        );
        $data = [Constants\Rtb::RES_STATUS => $langReopen];
        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_CANCEL_ENTER_BIDDER_NUM_S, Constants\Rtb::RES_DATA => $data];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;
        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $langReopen
        );
        return $responses;
    }

    /**
     * Return response for Pending Action "Select Buyer by Agent"
     * @return array
     */
    protected function produceSelectBuyerResponses(): array
    {
        $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($this->auctionLot->LotItemId, $this->auctionLot->AuctionId);
        $data = [
            Constants\Rtb::RES_LOT_ITEM_ID => $this->auctionLot->LotItemId,
            Constants\Rtb::RES_CURRENT_BID_FULL_AMOUNT => $this->auctionLot->multiplyQuantityEffectively($this->currentBidTransaction->Bid, $quantityScale),
        ];

        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_WAIT_SELECT_BUYER_S, Constants\Rtb::RES_DATA => $data];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;

        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SELECT_BUYER_S, Constants\Rtb::RES_DATA => $data];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_INDIVIDUAL] = [$this->currentBidTransaction->UserId, $responseJson];

        return $responses;
    }

    /**
     * Return response for Pending Action "Select Grouped Lots"
     * @return array
     */
    protected function produceSelectGroupedLotsResponses(): array
    {
        $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($this->auctionLot->LotItemId, $this->auctionLot->AuctionId);
        $data = [
            Constants\Rtb::RES_LOT_ITEM_ID => $this->auctionLot->LotItemId,
            Constants\Rtb::RES_CURRENT_BID_FULL_AMOUNT => $this->auctionLot->multiplyQuantityEffectively($this->currentBidTransaction->Bid, $quantityScale)
        ];
        $rtbCurrent = $this->getRtbCurrent();
        if ($rtbCurrent->LotGroup === Constants\Rtb::GROUP_QUANTITY) {
            $data[Constants\Rtb::RES_GROUP_LOT_QUANTITY] = $this->getGroupingHelper()->countGroup($this->getAuctionId());
        }

        if ($this->isFloor) {
            $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SELL_LOTS_S, Constants\Rtb::RES_DATA => $data];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_CLERK] = $responseJson;

            $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_WAIT_SELL_LOTS_S, Constants\Rtb::RES_DATA => $data];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        } else { // Online bidder

            $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SELL_LOTS_S, Constants\Rtb::RES_DATA => $data];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_INDIVIDUAL] = [$this->currentBidTransaction->UserId, $responseJson];

            $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_WAIT_SELL_LOTS_S, Constants\Rtb::RES_DATA => $data];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_CLERK] = $responseJson;
            $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        }

        return $responses;
    }

    /**
     * Return response, if lot is successfully sold
     * @return array
     */
    protected function produceSoldResponses(): array
    {
        $rtbCurrent = $this->getRtbCurrent();
        $data = $this->getResponseHelper()->getLotData($rtbCurrent);

        if ($this->isLastLot) {
            $data[Constants\Rtb::RES_STATUS] = '';
        }

        $lotDataStatus = $data[Constants\Rtb::RES_STATUS];

        $shouldClearMessageCenterLog = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::CLEAR_MESSAGE_CENTER_LOG, $this->getAuction()->AccountId);
        if ($shouldClearMessageCenterLog) {
            $this->getMessenger()->clearStaticMessage($this->getAuctionId(), true);
            $this->getMessenger()->clearStaticMessage($this->getAuctionId());
        }

        $data[Constants\Rtb::RES_IS_ABSENTEE_BID] = $rtbCurrent->AbsenteeBid;
        $data[Constants\Rtb::RES_SOLD_LOT_HAMMER_PRICES] = $this->soldStatuses; // Sold lot
        $data[Constants\Rtb::RES_SOLD_LOT_NO] = $this->lotNo; // Sold lot no
        $data[Constants\Rtb::RES_SOLD_LOT_WINNER_BIDDER_NO] = $this->getResponseHelper()
            ->hashSoldLotWinnerBidderNoUserId($this->bidderNums, $rtbCurrent); //user id hash, bidder num
        $data[Constants\Rtb::RES_SOLD_LOT_WINNER_USERNAME] = $this->username;

        $clerkData = $auctioneerData = array_merge(
            $data,
            $this->getResponseDataProducer()->produceAdminSideData($rtbCurrent),
            [Constants\Rtb::RES_SOLD_LOT_WINNER_BIDDER_NO => $this->bidderNums] //user id, bidder num
        );

        $messageHtml = '';
        if (isset($this->publicMessage)) {
            $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($this->publicMessage, $this->getAuction());
            $this->publicMessage .= '| ' . $lotDataStatus;
            $data[Constants\Rtb::RES_STATUS] = $this->publicMessage;
        }
        if ($lotDataStatus) {
            $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($lotDataStatus, $this->getAuction())
                . $messageHtml;
        }
        [$quantity, $isQuantityXMoney] = $this->getRtbCommandHelper()->extractQuantityValuesFromLotData($data);
        $messageHtml = $this->createRtbRenderer()->renderQuantityHtml($this->getAuction(), $this->getLotItemId(), $quantity, $isQuantityXMoney)
            . $messageHtml;
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageHtml);

        // Make bidder, viewer, projector console responses

        $data = $this->getResponseHelper()->removeSensitiveData($data);
        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S, Constants\Rtb::RES_DATA => $data];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;

        $shouldClearMessageCenter = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::CLEAR_MESSAGE_CENTER, $this->getAuction()->AccountId);
        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $this->publicMessage,
            $shouldClearMessageCenter
        );

        // Make auctioneer console response

        $auctioneerData = array_replace(
            $auctioneerData,
            $this->getResponseDataProducer()->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_AUCTIONEER)
        );
        if (isset($this->adminMessage)) {
            $auctioneerData[Constants\Rtb::RES_STATUS] = $this->adminMessage;
        }
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $auctioneerData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

        // Make clerk console response

        /**
         * SAM-2710: Check if bidder's outstanding limit exceeded after current lot selling
         * */
        $auctionBidder = $this->getAuctionBidderLoader()->load($this->currentBidTransaction->UserId, $this->getAuctionId(), true);
        $isOutstandingExceeded = $auctionBidder
            && OutstandingHelper::new()->isLimitExceeded($auctionBidder);
        if ($isOutstandingExceeded) {
            // Send warning message to bidder
            $clerkData[Constants\Rtb::RES_IS_OUTSTANDING_LIMIT_EXCEEDED] = true;    // To block bid button
            $bidderNo = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
            $message = "@" . $this->username . " (bidder# " . $bidderNo . "): "
                . $this->translate('BIDDERCLIENT_OUTSTANDING_EXCEED_MAKE_PAYMENT');
            $message = '<span style="font-weight:bold;color:#ff0000;">' . $message . '</span>';
            $clerkData[Constants\Rtb::RES_STATUS] = $message;
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
                Constants\Rtb::RES_DATA => $clerkData
            ];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_INDIVIDUAL] = [$auctionBidder->UserId, $responseJson];
            unset($clerkData[Constants\Rtb::RES_IS_OUTSTANDING_LIMIT_EXCEEDED]);
            // Send the same message to this bidder's simultaneous auction
            $responses = $this->getResponseHelper()->addIndividualForSimultaneousAuction(
                $responses,
                $this->getSimultaneousAuctionId(),
                $message,
                $auctionBidder->UserId
            );
            if ($this->getSimultaneousAuctionId() > 0) {
                // Send warning message to all clerks
                $message = sprintf(
                    $this->translate('BIDDERCLIENT_OUTSTANDING_EXCEED_CONTACT_AUCTIONEER'),
                    $bidderNo
                );
                $message = '<span style="font-weight:bold;color:#ff0000;">' . $message . '</span><br />' . "\n";
                $this->adminMessage = $message . $this->adminMessage;
            }
        }

        $messageHtml = '';
        if (isset($this->adminMessage)) {
            $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($this->adminMessage, $this->getAuction());
            $this->adminMessage .= '| ' . $lotDataStatus;
            $clerkData[Constants\Rtb::RES_STATUS] = $this->adminMessage;
        }
        if ($lotDataStatus) {
            $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($lotDataStatus, $this->getAuction())
                . $messageHtml;
        }

        [$quantity, $isQuantityXMoney] = $this->getRtbCommandHelper()->extractQuantityValuesFromLotData($clerkData);
        $messageHtml = $this->createRtbRenderer()->renderQuantityHtml($this->getAuction(), $this->getLotItemId(), $quantity, $isQuantityXMoney)
            . $messageHtml;
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageHtml, true);

        $currentBidAmount = (float)$clerkData[Constants\Rtb::RES_CURRENT_BID];
        $clerkData = array_merge(
            $clerkData,
            $this->getResponseDataProducer()->produceIncrementData($rtbCurrent, ['currentBid' => $currentBidAmount]),
            $this->getResponseDataProducer()->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_CLERK)
        );

        $clerkData[Constants\Rtb::RES_INCREMENT_RESTORE] = 0.;

        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S, Constants\Rtb::RES_DATA => $clerkData];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;

        if (!$this->isLastLot) {
            $isTextMsgEnabled = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::TEXT_MSG_ENABLED, $this->getAuction()->AccountId);
            $responses = $this->getResponseHelper()
                ->addSmsTextResponse($responses, $this->getAuction(), $rtbCurrent->LotItemId, $isTextMsgEnabled);
        }

        return $responses;
    }

    /**
     * Log to auction trail
     */
    protected function logLotReopened(): void
    {
        $role = $this->getLogger()->getUserRoleName($this->getUserType());
        $lotInfo = $this->getLotRenderer()->renderLotNo($this->auctionLot)
            . composeSuffix(['li' => $this->auctionLot->LotItemId]);
        $message = sprintf($this->lotReopenedTpl, $role, $lotInfo);
        $this->getLogger()->log($message);
    }
}
