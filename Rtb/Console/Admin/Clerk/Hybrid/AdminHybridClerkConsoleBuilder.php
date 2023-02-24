<?php

namespace Sam\Rtb\Console\Admin\Clerk\Hybrid;

use DateTime;
use Exception;
use Sam\Auction\Available\AuctionAvailabilityCheckerFactory;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\ClerkConsoleConstants;
use Sam\Rtb\Console\Admin\Clerk\Live\AdminLiveClerkConsoleBuilder;

/**
 * Class AdminHybridClerkConsoleBuilder
 */
class AdminHybridClerkConsoleBuilder extends AdminLiveClerkConsoleBuilder
{
    public string $langResetRunningLotCountdown = 'Reset Countdown';

    /**
     * Get a AdminLiveClerkConsoleBuilder instance
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function initControls(): void
    {
        try {
            parent::initControls();

            $rtbControlCollection = $this->getRtbControlCollection();
            $rtbControlBuilder = $this->createRtbControlBuilder();

            $rtbControlCollection->add(
                $rtbControlBuilder->buildButton(
                    ClerkConsoleConstants::CID_BTN_RESET_RUNNING_LOT_COUNTDOWN,
                    ['class' => 'button rl-reset-countdown', 'disabled' => 'disabled', 'html' => $this->langResetRunningLotCountdown, 'value' => $this->langResetRunningLotCountdown]
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(
                    ClerkConsoleConstants::CID_LBL_PENDING_TIMEOUT_SELECT_LOTS
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(
                    ClerkConsoleConstants::CID_LBL_PENDING_TIMEOUT_ENTER_BIDDER
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(
                    ClerkConsoleConstants::CID_LBL_PENDING_TIMEOUT_WAIT_SELECT_LOTS
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(
                    ClerkConsoleConstants::CID_LBL_PENDING_TIMEOUT_WAIT_SELECT_BUYER
                )
            );
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @return string
     */
    public function loadScript(): string
    {
        $validator = $this->createRtbAuctionValidator();
        $success = $validator->validate($this->getAuctionId());
        $errorMessage = $validator->buildErrorMessageHtmlForAdmin();

        $auction = $this->getAuction();
        $dataProvider = $this->getDataProvider();
        $generalDataProvider = $this->getGeneralDataProvider();
        $urlProvider = $this->getUrlProvider();
        $userType = Constants\Rtb::UT_CLERK;
        $userCreatedOn = $this->getEditorUser()->CreatedOn
            ? (new DateTime($this->getEditorUser()->CreatedOn))->getTimestamp()
            : '';

        // Hybrid special:
        $isBiddingConsoleAvailable = AuctionAvailabilityCheckerFactory::new()
            ->create(Constants\Auction::HYBRID)
            ->isBiddingConsoleAvailable($auction);

        $jsImportValues = [
            // Basic values
            "accountId" => $this->getSystemAccountId(),
            "arrLiveBiddingCountdown" => $this->liveBiddingCountdowns,
            "auctionCurrencySign" => $generalDataProvider->detectDefaultCurrencySign(),
            "auctionExchangeRate" => 1,
            "auctionExtendTime" => $auction->ExtendTime,
            "auctionId" => $auction->Id,
            "auctionLotStartGapTime" => $auction->LotStartGapTime,
            "auctionStartTimezoneCode" => $generalDataProvider->detectAuctionStartTimezoneCode(),
            "auctionStartTimezoneName" => $generalDataProvider->detectAuctionTimezoneLocation(),
            "auctionStartTimezoneOffset" => $generalDataProvider->detectAuctionTimezoneOffset(),
            "auctionType" => $auction->AuctionType,
            "isBiddingConsoleAvailable" => $isBiddingConsoleAvailable,
            "rtbCurrentDefaultIncrement" => $this->rtbCurrent->DefaultIncrement > 0 ? $this->rtbCurrent->DefaultIncrement : 1,
            "rtbCurrentEnableDecrement" => $this->rtbCurrent->EnableDecrement,
            "rtbCurrentIncrement" => $this->rtbCurrent->Increment > 0 ? $this->rtbCurrent->Increment : 0.,
            "rtbLiveHistoryServiceCommands" => $dataProvider->detectUndoCommands(),
            "sessionId" => $this->getSessionId(),
            "strClerkingStyle" => $this->clerkingStyle,
            "strCurrency" => $this->currencySign,
            "typeId" => $userType,
            "userCreatedOn" => $userCreatedOn,
            "userId" => $this->getEditorUserId(),

            // Installation config options
            "auctionHybridClosingDelay" => (int)$this->cfg()->get('core->auction->hybrid->closingDelay'), // hybrid
            "blnFloorBiddersDropdown" => $this->isFloorBiddersFromDropdown,
            "rtbAutoRefreshTimeout" => $this->cfg()->get('core->rtb->autoRefreshTimeout') * 1000,
            "rtbBidsBelowCurrent" => $this->cfg()->get('core->rtb->bidsBelowCurrent'),
            "rtbClerkBidderAddressEnabled" => $this->cfg()->get('core->rtb->clerk->bidder->address->enabled'),
            "rtbConnectionRetryCount" => $this->cfg()->get('core->rtb->connectionRetryCount'),
            "rtbContextMenuEnabled" => $this->cfg()->get('core->rtb->contextMenuEnabled'),
            "rtbMessageCenterRenderedMessageCount" => $this->cfg()->get('core->rtb->messageCenter->renderedMessageCount'),
            "rtbPingInterval" => $this->cfg()->get('core->rtb->ping->interval'),
            "rtbPingQualityIndicator" => $this->cfg()->get('core->rtb->ping->qualityIndicator')->toArray(),
            "rtbPingVariance" => $this->cfg()->get('core->rtb->ping->variance'),
            "rtbSoundVolume" => $this->cfg()->get('core->rtb->soundVolume'),

            // System parameters
            "blnClearMsgCenter" => $this->isClearMessageCenter,
            "blnTwentyMsgMax" => $this->isTwentyMessagesMax,
            "blnUsNumFormat" => $this->isUsNumberFormatting,
            "intDelayBlockSell" => $this->delayBlockSell,
            "intDelaySoldItem" => $this->delaySoldItem,
            "pendingActionTimeoutHybrid" => (int)$this->getSettingsManager()->get(Constants\Setting::PENDING_ACTION_TIMEOUT_HYBRID, $auction->AccountId),

            // Console validation info
            "isError" => !$success,
            "strError" => $errorMessage,

            // Translation labels / TODO: why are they there, not in $jsImportTranslations?
            "strOnline" => $this->langOnline,
            "strFair" => $this->langFair,
            "strFloor" => $this->langFloor,

            // Complete urls and url templates
            "dataProviderBiddersUrl" => $urlProvider->buildRtbBidderDataUrl(),
            "reopenUrl" => $urlProvider->buildAuctionReopenUrl(),
            "runUrl" => $urlProvider->buildAuctionRunUrl(),
            "urlAddRtbMessage" => $urlProvider->buildMessageCenterAddUrlTemplate(),
            "urlCatalogLotM" => $urlProvider->buildLotDetailsUrlTemplate(),
            "urlCenterRtbMessage" => $urlProvider->buildMessageCenterDataUrlTemplate(),
            "urlDelRtbMessage" => $urlProvider->buildMessageCenterDeleteUrlTemplate(),
            "urlLotItemCatalog" => $urlProvider->buildLotCatalogDataUrlTemplate(),
            "urlLotItemGroup" => $urlProvider->buildLotGroupDataUrlTemplate(),
            "urlLotItemPreview" => $urlProvider->buildLotPreviewUrlTemplate(),
            "urlManageBidderInterest" => $urlProvider->buildBidderInterestDataUrlTemplate(),
            "urlManageRtbUsers" => $urlProvider->buildConnectedUsersDataUrlTemplate(),
            "urlRtbIncrementAdd" => $urlProvider->buildIncrementAddUrl(),
            "urlRtbIncrementDel" => $urlProvider->buildIncrementDeleteUrlTemplate(),
            "urlUserEdit" => $urlProvider->buildUserEditUrlTemplate(),
            "urlSoundOnlineBidIncomingOnAdmin" => $urlProvider->buildSoundOnlineBidIncomingOnAdmin($this->cfg()->get('core->portal->mainAccountId')),
            "urlSoundClickFromSoundManagerJsVendor" => $urlProvider->buildSoundClickFromSoundManagerJsVendor(),
            "urlSoundChimeFromSoundManagerJsVendor" => $urlProvider->buildSoundChimeFromSoundManagerJsVendor(),
            "wsHost" => $urlProvider->buildRtbdUri($userType),

            //rtbd connection status check
            'rtbdConnectionUrl' => $this->getRtbGeneralHelper()->getRtbPingUri(Constants\Rtb::UT_CLIENT, $this->getAuctionId()),
            'rtbdConnectionTestTimeout' => $this->cfg()->get('core->rtb->connectionTest->timeout'),
        ];
        $this->getJsValueImporter()->injectValues($jsImportValues);

        $tr = $this->getTranslator();
        $jsImportTranslations = [
            "bidderClientCantLoadData" => $tr->translateForRtb('BIDDERCLIENT_CANTLOADDATA', $auction),
            "bidderClientConnectionTerminated" => $tr->translateForRtb('BIDDERCLIENT_CONNECTION_TERMINATED', $auction),
            "bidderClientConsuccess" => $tr->translateForRtb('BIDDERCLIENT_CONSUCCESS', $auction),
            "bidderClientLotReopened" => $tr->translateForRtb('BIDDERCLIENT_LOTREOPENED', $auction),
            "bidderClientLotSoldToFloor" => $tr->translateForRtb('BIDDERCLIENT_LOTSOLDTOFLOOR', $auction),
            "bidderClientMsgFairWarn" => $tr->translateForRtb('BIDDERCLIENT_MSG_FAIRWARN', $auction),
            "bidderClientQuantityRtb" => $tr->translateForRtb('BIDDERCLIENT_QUANTITY_RTB', $auction),
            "bidderClientSelectGroup" => $tr->translateForRtb('BIDDERCLIENT_SELECTGROUP', $auction),
            "bidderClientSelectLot" => $tr->translateForRtb('BIDDERCLIENT_SELECT_LOT', $auction),
            "catalogBn" => $tr->translateForRtb('BIDDERCLIENT_BN', $auction),
            "catalogSoldThroughBuy" => $tr->translateForRtb('BIDDERCLIENT_SOLD_THROUGH_BUY', $auction),
            //for hybrid
            "bidderClientGapTimeCountdown" => $tr->translateForRtb('BIDDERCLIENT_GAP_TIME_COUNTDOWN', $auction),
            "catalogRtbCountdownDays" => $tr->translateForRtb('BIDDERCLIENT_COUNTDOWN_DAYS', $auction),
            "catalogRtbCountdownHrs" => $tr->translateForRtb('BIDDERCLIENT_COUNTDOWN_HRS', $auction),
            "catalogRtbCountdownMins" => $tr->translateForRtb('BIDDERCLIENT_COUNTDOWN_MINS', $auction),
            "catalogRtbCountdownSecs" => $tr->translateForRtb('BIDDERCLIENT_COUNTDOWN_SECS', $auction),
            'rtbdConnectionFailedMessage' => sprintf(
                $tr->translateForRtb('BIDDERCLIENT_RTBD_CONNECTION_FAILED_MSG', $auction),
                $this->getRtbGeneralHelper()->getPublicHost(),
                $this->getRtbGeneralHelper()->getPublicPort()
            )
        ];
        $this->getJsValueImporter()->injectTranslations($jsImportTranslations);
        return '';
    }
}
