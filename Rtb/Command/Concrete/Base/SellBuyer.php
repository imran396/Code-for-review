<?php

namespace Sam\Rtb\Command\Concrete\Base;

use AuctionLotItem;
use LotItem;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Bidder\MyBuyer\RtbMyBuyerListDataBuilderCreateTrait;
use Sam\Rtb\Catalog\Bidder\Manage\BidderCatalogManagerFactoryCreateTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\Group\GroupingHelperAwareTrait;
use Sam\Rtb\LotInfo\LotInfoServiceAwareTrait;
use Sam\Rtb\Sell\LotSellerAwareTrait;
use Sam\Rtb\State\PendingAction\PendingActionUpdaterCreateTrait;
use Sam\Rtb\State\Reset\RtbStateResetterAwareTrait;
use Sam\Rtb\State\RtbStateUpdaterCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class SellBuyer
 * @package Sam\Rtb\Command\Concrete\Base
 * @method AuctionLotItem getAuctionLot() - existence checked in checkRunningLot()
 * @method LotItem getLotItem() - existence checked in checkRunningLot()
 */
abstract class SellBuyer extends CommandBase implements RtbCommandHelperAwareInterface
{
    use AuctionLotItemWriteRepositoryAwareTrait;
    use BidderCatalogManagerFactoryCreateTrait;
    use GroupingHelperAwareTrait;
    use LotInfoServiceAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use LotRendererAwareTrait;
    use LotSellerAwareTrait;
    use PendingActionUpdaterCreateTrait;
    use ResponseDataProducerAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbMyBuyerListDataBuilderCreateTrait;
    use RtbRendererCreateTrait;
    use RtbStateResetterAwareTrait;
    use RtbStateUpdaterCreateTrait;

    protected ?int $buyerUserId = null;
    protected bool $isLastLot = false;

    /**
     * @param int|null $buyerUserId user id of buyer. null/"0" means current high bidder
     * @return static
     */
    public function setBuyerUserId(?int $buyerUserId): static
    {
        $this->buyerUserId = $buyerUserId;
        return $this;
    }

    public function execute(): void
    {
        $this->getLotSeller()->setLogger($this->getLogger());

        if (!$this->validate()) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        if (in_array($this->getUserType(), [Constants\Rtb::UT_CLERK, Constants\Rtb::UT_SYSTEM], true)) {
            $this->sellByAdmin();
        } elseif ($this->getUserType() === Constants\Rtb::UT_BIDDER) {
            $this->sellByBidder();
        }
    }

    protected function sellByAdmin(): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        $auctionLot = $this->getAuctionLot();
        $currentBidTransaction = $this->createBidTransactionLoader()->loadById($auctionLot->CurrentBidId);
        if (!$currentBidTransaction) {
            log_error(
                "Available current bid not found, when selecting agent's buyer for purchase via admin console"
                . composeSuffix(['bt' => $auctionLot->CurrentBidId, 'ali' => $auctionLot->Id])
            );
            return;
        }

        $lotItem = $this->getLotItem();

        /** IK: Looks like we always pass "0" as "WnBuy" at admin side to continue sale. See, $(this).SelectBuyerQ('0');
         * */
        $buyerUserId = $this->detectBuyerUserId();

        $soldStatuses = [];
        $bidderNums = [];

        if ($rtbCurrent->LotGroup === '') { // No group
            [$adminMessage, $publicMessage] = $this->getLotSeller()->sellLot(
                $auctionLot,
                $buyerUserId,
                $this->getUserType(),
                $this->detectModifierUserId()
            );
            $auctionLot->toSold();
            $this->getAuctionLotItemWriteRepository()->save($auctionLot); // IK: it was ->forceSave($auctionLot), IDK why (SAM-5436)
            $lotItem->Reload();
        } else {
            if (in_array($rtbCurrent->LotGroup, [Constants\Rtb::GROUP_CHOICE, Constants\Rtb::GROUP_QUANTITY], true)) {
                $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($auctionLot->LotItemId, $auctionLot->AuctionId);
                $data = [
                    Constants\Rtb::RES_LOT_ITEM_ID => $lotItem->Id,
                    Constants\Rtb::RES_CURRENT_BID_FULL_AMOUNT => $auctionLot->multiplyQuantityEffectively($currentBidTransaction->Bid, $quantityScale),
                ];
                if ($rtbCurrent->LotGroup === Constants\Rtb::GROUP_QUANTITY) {
                    $data[Constants\Rtb::RES_GROUP_LOT_QUANTITY] = $this->getGroupingHelper()->countGroup($this->getAuctionId());
                }

                $response = [
                    Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SELL_LOTS_S,
                    Constants\Rtb::RES_DATA => $data
                ];
                $responseJson = json_encode($response);
                $responses[Constants\Rtb::RT_INDIVIDUAL] = [$buyerUserId, $responseJson];

                $response = [
                    Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_WAIT_SELL_LOTS_S,
                    Constants\Rtb::RES_DATA => $data
                ];
                $responseJson = json_encode($response);
                $responses[Constants\Rtb::RT_CLERK] = $responseJson;
                $responses[Constants\Rtb::RT_BIDDER] = $responseJson;

                $rtbCurrent = $this->createPendingActionUpdater()
                    ->update($rtbCurrent, Constants\Rtb::PA_SELECT_GROUPED_LOTS);
                $rtbCurrent->GroupUser = $buyerUserId;
                $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

                $this->setResponses($responses);
                return;
            }

            [$adminMessage, $publicMessage, $soldStatuses] = $this->getLotSeller()->sellGroup(
                $auctionLot,
                (string)$buyerUserId,
                $this->getUserType(),
                $this->detectModifierUserId()
            );
            $lotItem->Reload();
        }

        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $soldLotNo[$lotItem->Id] = $lotNo;
        $soldStatuses[$lotItem->Id] = $lotItem->HammerPrice;

        $catalogManager = $this->createCatalogManagerFactory()
            ->createByRtbCurrent($rtbCurrent, $this->getAuction()->AccountId, $this->getViewLanguageId());
        $catalogManager->updateRow($auctionLot);

        $auctionBidderLoader = $this->getAuctionBidderLoader();

        if (!$currentBidTransaction->FloorBidder) {
            $auctionBidder = $auctionBidderLoader->load($buyerUserId, $this->getAuctionId(), true);
            $bidderNum = $auctionBidder ? $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum) : '';
            $bidderNums[$buyerUserId] = $bidderNum;
        }
        $buyerUser = $this->getUserLoader()->load($buyerUserId);
        $username = $buyerUser->Username ?? '';

        $this->getLotInfoService()->drop($this->getAuctionId());

        $nextLotItem = $this->findNextLot();
        $lotActivity = $this->isLastLot ? Constants\Rtb::LA_IDLE : Constants\Rtb::LA_BY_AUTO_START;

        $rtbCurrent = $this->getRtbCommandHelper()->switchRunningLot($rtbCurrent, $nextLotItem);
        $rtbCurrent = $this->getRtbStateResetter()->cleanState($rtbCurrent, $this->detectModifierUserId());
        $rtbCurrent = $this->getRtbCommandHelper()->activateLot($rtbCurrent, $lotActivity, $this->detectModifierUserId());
        $rtbCurrent = $this->createRtbStateUpdater()->update($rtbCurrent, $this->getAuction()->AccountId, $this->getViewLanguageId());
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        $data = $this->getResponseHelper()->getLotData($rtbCurrent);

        if ($this->isLastLot) {
            $data[Constants\Rtb::RES_STATUS] = '';
        }

        $status = $data[Constants\Rtb::RES_STATUS];

        $shouldClearMessageCenterLog = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::CLEAR_MESSAGE_CENTER_LOG, $this->getAuction()->AccountId);
        if ($shouldClearMessageCenterLog) {
            $this->getMessenger()->clearStaticMessage($this->getAuctionId(), true);
            $this->getMessenger()->clearStaticMessage($this->getAuctionId());
        }

        $data[Constants\Rtb::RES_IS_ABSENTEE_BID] = $rtbCurrent->AbsenteeBid;
        $data[Constants\Rtb::RES_SOLD_LOT_HAMMER_PRICES] = $soldStatuses; // Sold lot
        $data[Constants\Rtb::RES_SOLD_LOT_NO] = $soldLotNo;
        $data[Constants\Rtb::RES_SOLD_LOT_WINNER_BIDDER_NO] = $this->getResponseHelper()
            ->hashSoldLotWinnerBidderNoUserId($bidderNums, $rtbCurrent); //user id hash, bidder num
        $data[Constants\Rtb::RES_SOLD_LOT_WINNER_USERNAME] = $username;

        $messageHtml = '';
        if (isset($publicMessage)) {
            $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($publicMessage, $this->getAuction());
            $publicMessage .= '| ' . $status;
            $data[Constants\Rtb::RES_STATUS] = $publicMessage;
        }

        if (isset($adminMessage)) {
            $data[Constants\Rtb::RES_STATUS] = $adminMessage;
        }

        $responseDataProducer = $this->getResponseDataProducer();
        $clerkData = $auctioneerData = array_merge(
            $data,
            $responseDataProducer->produceAdminSideData($rtbCurrent),
            [Constants\Rtb::RES_SOLD_LOT_WINNER_BIDDER_NO => $bidderNums] //user id, bidder num
        );

        // Make bidder/viewer/projector console responses

        if ($status) {
            $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($status, $this->getAuction())
                . $messageHtml;
        }
        [$quantity, $isQuantityXMoney] = $this->getRtbCommandHelper()->extractQuantityValuesFromLotData($data);
        $messageHtml = $this->createRtbRenderer()->renderQuantityHtml($this->getAuction(), $lotItem->Id, $quantity, $isQuantityXMoney)
            . $messageHtml;
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageHtml);

        $data = $this->getResponseHelper()->removeSensitiveData($data);
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;

        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $publicMessage
        );

        // Make auctioneer console response

        $auctioneerData = array_replace(
            $auctioneerData,
            $responseDataProducer->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_AUCTIONEER)
        );

        if (isset($adminMessage)) {
            $auctioneerData[Constants\Rtb::RES_STATUS] = $adminMessage;
        }
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $auctioneerData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

        // Make clerk console response

        if (isset($adminMessage)) {
            $clerkData[Constants\Rtb::RES_STATUS] = $adminMessage;
        }
        /** SAM-2710: Probably, we are checking outstanding limit for agent now
         * */
        $auctionBidder = $auctionBidderLoader->load($buyerUserId, $this->getAuctionId(), true);
        $isOutstandingExceeded = $auctionBidder
            && \Sam\Bidder\Outstanding\BidderOutstandingHelper::new()->isLimitExceeded($auctionBidder);
        if ($isOutstandingExceeded) {
            /** Bidder's outstanding limit exceeded after current lot selling,
             * send warning message */
            $data[Constants\Rtb::RES_IS_OUTSTANDING_LIMIT_EXCEEDED] = true;    // To block bid button
            $bidderNum = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
            $message = "@" . $username . " (bidder# " . $bidderNum . "): "
                . $this->translate('BIDDERCLIENT_OUTSTANDING_EXCEED_MAKE_PAYMENT');
            $message = '<span style="font-weight:bold;color:#ff0000;">' . $message . '</span>';
            $data[Constants\Rtb::RES_STATUS] = $message;
            $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S, Constants\Rtb::RES_DATA => $data];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_INDIVIDUAL] = [$buyerUserId, $responseJson];
            unset($data[Constants\Rtb::RES_IS_OUTSTANDING_LIMIT_EXCEEDED]);
            // Send the same message to this bidder's simultaneous auction
            $responses = $this->getResponseHelper()->addIndividualForSimultaneousAuction(
                $responses,
                $this->getSimultaneousAuctionId(),
                $message,
                $buyerUserId
            );
            // Send warning message to all clerks
            $message = sprintf(
                $this->translate('BIDDERCLIENT_OUTSTANDING_EXCEED_CONTACT_AUCTIONEER'),
                $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum)
            );
            $message = '<span style="font-weight:bold;color:#ff0000;">' . $message . '</span><br />' . "\n";
            $adminMessage = $message . $adminMessage;
        }

        $messageHtml = '';
        if (isset($adminMessage)) {
            $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($adminMessage, $this->getAuction());
            $adminMessage .= '| ' . $status;
            $clerkData[Constants\Rtb::RES_STATUS] = $adminMessage;
        }
        if ($status !== '') {
            $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($status, $this->getAuction())
                . $messageHtml;
        }
        [$quantity, $isQuantityXMoney] = $this->getRtbCommandHelper()->extractQuantityValuesFromLotData($clerkData);
        $messageHtml = $this->createRtbRenderer()->renderQuantityHtml($this->getAuction(), $lotItem->Id, $quantity, $isQuantityXMoney)
            . $messageHtml;
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageHtml, true);

        $currentBidAmount = (float)$clerkData[Constants\Rtb::RES_CURRENT_BID];

        $clerkData = array_merge(
            $clerkData,
            $responseDataProducer->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_CLERK),
            $responseDataProducer->produceIncrementData($rtbCurrent, ['currentBid' => $currentBidAmount])
        );

        $clerkData[Constants\Rtb::RES_INCREMENT_RESTORE] = 0.;

        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $clerkData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;

        if (!$this->isLastLot) {
            $isTextMsgEnabled = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::TEXT_MSG_ENABLED, $this->getAuction()->AccountId);
            $responses = $this->getResponseHelper()->addSmsTextResponse(
                $responses,
                $this->getAuction(),
                $rtbCurrent->LotItemId,
                $isTextMsgEnabled
            );
        }

        $this->setResponses($responses);
    }

    protected function sellByBidder(): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        $auctionLot = $this->getAuctionLot();
        $currentBidTransaction = $this->createBidTransactionLoader()->loadById($auctionLot->CurrentBidId);
        if (!$currentBidTransaction) {
            log_error(
                "Available current bid not found, when selecting agent's buyer for purchase via bidder console"
                . composeSuffix(['bt' => $auctionLot->CurrentBidId, 'ali' => $auctionLot->Id])
            );
            return;
        }

        $lotItem = $this->getLotItem();
        $buyerUserId = $this->detectBuyerUserId();

        $hammerPrices = [];
        $bidderNums = [];

        if ($rtbCurrent->LotGroup === '') { // No group

            [$adminMessage, $publicMessage] = $this->getLotSeller()->sellLot(
                $auctionLot,
                $buyerUserId,
                $this->getUserType(),
                $this->detectModifierUserId()
            );
            $auctionLot->toSold();
            $this->getAuctionLotItemWriteRepository()->save($auctionLot);  // IK: it was ->forceSave($auctionLot), IDK why (SAM-5436)
            $lotItem->Reload();
        } else {
            if (in_array($rtbCurrent->LotGroup, [Constants\Rtb::GROUP_CHOICE, Constants\Rtb::GROUP_QUANTITY], true)) { // group choice
                $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($auctionLot->LotItemId, $auctionLot->AuctionId);
                $data = [
                    Constants\Rtb::RES_LOT_ITEM_ID => $lotItem->Id,
                    Constants\Rtb::RES_CURRENT_BID_FULL_AMOUNT => $auctionLot->multiplyQuantityEffectively($currentBidTransaction->Bid, $quantityScale),
                ];
                if ($rtbCurrent->LotGroup === Constants\Rtb::GROUP_QUANTITY) {
                    $data[Constants\Rtb::RES_GROUP_LOT_QUANTITY] = $this->getGroupingHelper()->countGroup($this->getAuctionId());
                }

                $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SELL_LOTS_S, Constants\Rtb::RES_DATA => $data];
                $responseJson = json_encode($response);
                $responses[Constants\Rtb::RT_INDIVIDUAL] = [$this->getEditorUserId(), $responseJson];

                $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_WAIT_SELL_LOTS_S, Constants\Rtb::RES_DATA => $data];
                $responseJson = json_encode($response);
                $responses[Constants\Rtb::RT_CLERK] = $responseJson;
                $responses[Constants\Rtb::RT_BIDDER] = $responseJson;

                $rtbCurrent = $this->createPendingActionUpdater()
                    ->update($rtbCurrent, Constants\Rtb::PA_SELECT_GROUPED_LOTS);
                $rtbCurrent->GroupUser = $buyerUserId;
                $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

                $this->setResponses($responses);
                return;
            }

            [$adminMessage, $publicMessage, $hammerPrices] = $this->getLotSeller()->sellGroup(
                $auctionLot,
                (string)$buyerUserId,
                $this->getUserType(),
                $this->detectModifierUserId()
            );
            $lotItem->Reload();
        }

        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $soldLotNo[$lotItem->Id] = $lotNo;
        $hammerPrice = $lotItem->HammerPrice;
        $hammerPrices[$lotItem->Id] = $hammerPrice;

        $catalogManager = $this->createCatalogManagerFactory()
            ->createByRtbCurrent($rtbCurrent, $this->getAuction()->AccountId, $this->getViewLanguageId());
        $catalogManager->updateRow($auctionLot);

        $auctionBidderLoader = $this->getAuctionBidderLoader();

        if (!$currentBidTransaction->FloorBidder) {
            $auctionBidder = $auctionBidderLoader->load($buyerUserId, $this->getAuctionId(), true);
            $bidderNum = $auctionBidder ? $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum) : '';
            $bidderNums[$buyerUserId] = $bidderNum;
        }
        $buyerUser = $this->getUserLoader()->load($buyerUserId);
        $username = $buyerUser->Username ?? '';

        $this->getLotInfoService()->drop($this->getAuctionId());

        $nextLotItem = $this->findNextLot();
        $lotActivity = $this->isLastLot ? Constants\Rtb::LA_IDLE : Constants\Rtb::LA_BY_AUTO_START;

        $rtbCurrent = $this->getRtbCommandHelper()->switchRunningLot($rtbCurrent, $nextLotItem);
        $rtbCurrent = $this->getRtbStateResetter()->cleanState($rtbCurrent, $this->detectModifierUserId());
        $rtbCurrent = $this->getRtbCommandHelper()->activateLot($rtbCurrent, $lotActivity, $this->detectModifierUserId());
        $rtbCurrent = $this->createRtbStateUpdater()->update($rtbCurrent, $this->getAuction()->AccountId, $this->getViewLanguageId());
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        $runningAuctionLot = $this->getAuctionLotLoader()->load($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $quantity = $runningAuctionLot->Quantity ?? 0;
        $isQuantityXMoney = $runningAuctionLot->QuantityXMoney ?? false;

        $data = $this->getResponseHelper()->getLotData($rtbCurrent);
        if ($this->isLastLot) {
            $data[Constants\Rtb::RES_STATUS] = '';
        }

        $status = $data[Constants\Rtb::RES_STATUS];
        $shouldClearMessageCenterLog = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::CLEAR_MESSAGE_CENTER_LOG, $this->getAuction()->AccountId);
        if ($shouldClearMessageCenterLog) {
            $this->getMessenger()->clearStaticMessage($this->getAuctionId(), true);
            $this->getMessenger()->clearStaticMessage($this->getAuctionId());
        }

        $data[Constants\Rtb::RES_IS_ABSENTEE_BID] = $rtbCurrent->AbsenteeBid;
        $data[Constants\Rtb::RES_SOLD_LOT_HAMMER_PRICES] = $hammerPrices; // Sold lot
        $data[Constants\Rtb::RES_SOLD_LOT_NO] = $soldLotNo;
        $data[Constants\Rtb::RES_SOLD_LOT_WINNER_BIDDER_NO] = $this->getResponseHelper()
            ->hashSoldLotWinnerBidderNoUserId($bidderNums, $rtbCurrent); //user id hash, bidder num
        $data[Constants\Rtb::RES_SOLD_LOT_WINNER_USERNAME] = $username;

        $messageHtml = '';
        if (isset($publicMessage)) {
            $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($publicMessage, $this->getAuction());
            $publicMessage .= '| ' . $status;
            $data[Constants\Rtb::RES_STATUS] = $publicMessage;
        }

        $responseDataProducer = $this->getResponseDataProducer();
        $clerkData = $auctioneerData = array_merge(
            $data,
            $responseDataProducer->produceAdminSideData($rtbCurrent),
            [Constants\Rtb::RES_SOLD_LOT_WINNER_BIDDER_NO => $bidderNums] //user id, bidder num
        );

        // Make bidder/viewer/projector console responses

        if ($status) {
            $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($status, $this->getAuction())
                . $messageHtml;
        }

        $messageHtml = $this->createRtbRenderer()->renderQuantityHtml($this->getAuction(), $lotItem->Id, $quantity, $isQuantityXMoney)
            . $messageHtml;
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageHtml);

        $data = $this->getResponseHelper()->removeSensitiveData($data);
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;

        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $publicMessage
        );

        // Make auctioneer console response

        $auctioneerData = array_replace(
            $auctioneerData,
            $responseDataProducer->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_AUCTIONEER)
        );

        if (isset($adminMessage)) {
            $auctioneerData[Constants\Rtb::RES_STATUS] = $adminMessage;
        }
        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S, Constants\Rtb::RES_DATA => $auctioneerData];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

        /** SAM-2710, IK: We check outstanding limit only for bidders of auction.
         * Non-bidders are not limited by outstanding value.
         */
        $auctionBidder = $auctionBidderLoader->load($buyerUserId, $this->getAuctionId(), true);
        $isOutstandingExceeded = $auctionBidder
            && \Sam\Bidder\Outstanding\BidderOutstandingHelper::new()->isLimitExceeded($auctionBidder);
        if ($isOutstandingExceeded) {
            /** Bidder's outstanding limit exceeded after current lot selling,
             * send warning message to agent */
            if ($buyerUserId === $this->getEditorUserId()) {
                $data[Constants\Rtb::RES_IS_OUTSTANDING_LIMIT_EXCEEDED] = true;
            }
            $bidderNum = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
            if ($buyerUserId === $this->getEditorUserId()) {
                $statusInfo = "@" . $username . " (bidder# " . $bidderNum . "): "
                    . $this->translate('BIDDERCLIENT_OUTSTANDING_EXCEED_MAKE_PAYMENT');
            } else {
                $statusInfo = sprintf(
                    $this->translate('BIDDERCLIENT_OUTSTANDING_EXCEED_CONTACT_AUCTIONEER'),
                    $bidderNum
                );
            }
            $statusInfo = '<span style="font-weight:bold;color:#ff0000;">' . $statusInfo . '</span>';
            $data[Constants\Rtb::RES_STATUS] = $statusInfo;
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
                Constants\Rtb::RES_DATA => $data
            ];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_INDIVIDUAL] = [$this->getEditorUserId(), $responseJson];
            unset($data[Constants\Rtb::RES_IS_OUTSTANDING_LIMIT_EXCEEDED]);
            // Send the same message to agent's simultaneous auction
            $responses = $this->getResponseHelper()->addIndividualForSimultaneousAuction(
                $responses,
                $this->getSimultaneousAuctionId(),
                $statusInfo,
                $this->getEditorUserId()
            );

            // Send the same message to all clerks
            $adminMessage = $statusInfo . '<br />' . "\n" . $this->createRtbRenderer()->renderAuctioneerMessage($adminMessage, $this->getAuction());
        }

        $messageHtml = '';
        if (isset($adminMessage)) {
            $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($adminMessage, $this->getAuction());
            $adminMessage .= '| ' . $status;
            $clerkData[Constants\Rtb::RES_STATUS] = $adminMessage;
        }
        if ($status !== '') {
            $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($status, $this->getAuction())
                . $messageHtml;
        }

        $messageHtml = $this->createRtbRenderer()->renderQuantityHtml($this->getAuction(), $lotItem->Id, $quantity, $isQuantityXMoney)
            . $messageHtml;
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageHtml, true);

        $currentBidAmount = (float)$clerkData[Constants\Rtb::RES_CURRENT_BID];
        $clerkData = array_merge(
            $clerkData,
            $responseDataProducer->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_CLERK),
            $responseDataProducer->produceIncrementData($rtbCurrent, ['currentBid' => $currentBidAmount])
        );

        $clerkData[Constants\Rtb::RES_INCREMENT_RESTORE] = 0.;

        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $clerkData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;

        $this->setResponses($responses);
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
     * Return buyer id.
     * At admin side we don't pass buyer id. Admin could only continue sale (pass 0/null),
     * that means lot is sold to agent (he is current bidder)
     * @return int|null null means not found
     */
    protected function detectBuyerUserId(): ?int
    {
        if ($this->buyerUserId > 0) {
            return $this->buyerUserId;
        }

        if ($this->getAuctionLot()->CurrentBidId) {
            $currentBid = $this->createBidTransactionLoader()->loadById($this->getAuctionLot()->CurrentBidId);
            if ($currentBid) {
                return $currentBid->UserId;
            }
        }

        return null;
    }

    /**
     * Validate request command availability and its data correctness
     * @return bool
     */
    protected function validate(): bool
    {
        $success = false;
        $rtbCurrent = $this->getRtbCurrent();
        $pendingActionDateIso = $rtbCurrent->PendingActionDate
            ? $rtbCurrent->PendingActionDate->format(Constants\Date::ISO) : '';
        $logInfo = composeSuffix(
            [
                'a' => $rtbCurrent->AuctionId,
                'li' => $rtbCurrent->LotItemId,
                'ut' => $this->getUserType(),
                'u' => $this->getEditorUserId(),
                'bu' => $rtbCurrent->BuyerUser,
                'pa' => $rtbCurrent->PendingAction,
                'pad' => $pendingActionDateIso,
            ]
        );

        if (
            $rtbCurrent->PendingAction === Constants\Rtb::PA_SELECT_BUYER_BY_AGENT
            && $rtbCurrent->PendingActionDate
        ) {
            if ($this->getUserType() === Constants\Rtb::UT_BIDDER) {
                if ($rtbCurrent->BuyerUser === $this->getEditorUserId()) {
                    $success = true;
                }
            } elseif (in_array($this->getUserType(), [Constants\Rtb::UT_CLERK, Constants\Rtb::UT_SYSTEM], true)) {
                $success = true;
            }
        }
        if (!$success) {
            log_warning("Unexpected command {$logInfo}");
            return false;
        }

        $buyerUserId = $this->detectBuyerUserId();
        if (!$buyerUserId) {
            log_warning("Unexpected command: Unknown Buyer Id {$logInfo}");
            return false;
        }

        if (!$this->isValidBuyer()) {
            log_warning("Unexpected command: Invalid Buyer {$logInfo}");
            return false;
        }

        $auctionBidder = $this->getAuctionBidderLoader()->load($buyerUserId, $this->getAuctionId(), true);
        $isOutstandingExceeded = $auctionBidder
            && \Sam\Bidder\Outstanding\BidderOutstandingHelper::new()->isLimitExceeded($auctionBidder);
        if ($isOutstandingExceeded) {
            log_warning("Unexpected command: Trying to sell lot to bidder with exceeded outstanding limit {$logInfo}");
            return false;
        }

        if (
            !$this->checkConsoleSync()
            || !$this->checkRunningLot()
        ) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    private function isValidBuyer(): bool
    {
        if (!$this->buyerUserId) {
            return true;
        }

        $buyersList = $this->createRtbMyBuyerListDataBuilder()
            ->construct($this->getEditorUserId(), $this->getAuctionId())
            ->build();
        $isValidBuyerId = array_key_exists($this->buyerUserId, $buyersList['Buyers']);
        return $isValidBuyerId;
    }
}
