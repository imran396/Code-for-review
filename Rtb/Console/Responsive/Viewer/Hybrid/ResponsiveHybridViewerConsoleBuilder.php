<?php

namespace Sam\Rtb\Console\Responsive\Viewer\Hybrid;

use DateTime;
use Exception;
use Sam\Core\Constants;
use Sam\Core\Constants\Responsive\RtbConsoleConstants;
use Sam\Core\Constants\Responsive\BidderBaseConstants;
use Sam\Rtb\Console\Responsive\Viewer\Live\ResponsiveLiveViewerConsoleBuilder;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\User\Auth\Identity\AuthIdentityManager;

/**
 * Class ResponsiveHybridViewerConsoleBuilder
 */
class ResponsiveHybridViewerConsoleBuilder extends ResponsiveLiveViewerConsoleBuilder
{
    use TimezoneLoaderAwareTrait;

    /**
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

            $this->getRtbControlCollection()->add(
                $this->createRtbControlBuilder()->buildDiv(RtbConsoleConstants::CID_LBL_COUNTDOWN)
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
        $auction = $this->getAuction();
        $dataProvider = $this->getDataProvider();
        $generalDataProvider = $this->getGeneralDataProvider();
        $urlProvider = $this->getUrlProvider();
        $actionName = $this->getParamFetcherForRoute()->getActionName();
        // IK: Looks like "Multi currency" works for projector page only
        $isMultiCurrency = $this->isMultiCurrency
            && $actionName !== Constants\ResponsiveRoute::AA_LIVE_SALE;
        $isSimultaneous = $this->isSimultaneousAuctionAvailable();
        $editorUser = $this->getEditorUser();
        $userCreatedOn = $editorUser && $editorUser->CreatedOn
            ? (new DateTime($editorUser->CreatedOn))->getTimestamp()
            : '';
        $userType = Constants\Rtb::UT_VIEWER;
        $systemAccountId = $this->getSystemAccountId();

        $currencyProps = [];
        $currencies = $this->getCurrencyLoader()->loadCurrenciesForAuction(auctionId: $auction->Id, isReadOnlyDb: true);
        foreach ($currencies as $currency) {
            $currencyProps[] = [
                'name' => $currency->Name,
                'sign' => $currency->Sign,
                'exRate' => $currency->ExRate
            ];
        }

        $jsImportValues = [
            // Basic values
            "accountId" => $systemAccountId,
            "auctionCurrencySign" => $generalDataProvider->detectDefaultCurrencySign(),
            "auctionId" => $this->getAuctionId(),
            "auctionType" => $auction->AuctionType,
            "blnProjectorSimple" => $this->isProjectorSimple,
            "isBidder" => $this->hasEditorUserBidderRole(),
            "isBidderPanel" => false,
            "isHideUnsoldLots" => $auction->HideUnsoldLots,
            "isSimultaneousAuction" => $isSimultaneous,
            "lotCount" => $dataProvider->countLots(),
            "sessionId" => $this->getSessionId(),
            "simultaneousAuctionId" => $this->getSimultaneousAuctionId(),
            "rtbUserTypeId" => $userType,
            "userCreatedOn" => $userCreatedOn,
            "userId" => $this->getEditorUserId(),

            // Basic values (hybrid specific)
            "auctionExtendTime" => $auction->ExtendTime,
            "auctionLotStartGapTime" => $auction->LotStartGapTime,
            "auctionStartTimezoneCode" => $generalDataProvider->detectAuctionStartTimezoneCode(),
            "auctionStartTimezoneName" => $generalDataProvider->detectAuctionTimezoneLocation(),
            "auctionStartTimezoneOffset" => $generalDataProvider->detectAuctionTimezoneOffset(),

            // Installation config options
            "rtbAutoRefreshTimeout" => $this->cfg()->get('core->rtb->autoRefreshTimeout') * 1000,
            "rtbBiddingInterestDelayMs" => $this->cfg()->get('core->rtb->biddingInterest->delayMs'),
            "rtbCatalogPageLength" => $this->cfg()->get('core->rtb->catalog->pageLength'),
            "rtbConnectionRetryCount" => $this->cfg()->get('core->rtb->connectionRetryCount'),
            "rtbMessageCenterRenderedMessageCount" => $this->cfg()->get('core->rtb->messageCenter->renderedMessageCount'),
            "rtbPingInterval" => $this->cfg()->get('core->rtb->ping->interval'),
            "rtbPingQualityIndicator" => $this->cfg()->get('core->rtb->ping->qualityIndicator')->toArray(),
            "rtbPingVariance" => $this->cfg()->get('core->rtb->ping->variance'),
            "rtbProjectorNumberRoundPrecision" => $this->isProjector ? $this->cfg()->get('core->rtb->projector->numberRoundPrecision') : 2,
            "rtbSoundVolume" => $this->cfg()->get('core->rtb->soundVolume'),
            "rtbLazyLoadTimeout" => $this->cfg()->get('core->rtb->catalog->lazyLoadTimeout'),

            // System parameters
            "blnClearMsgCenter" => $this->isClearMessageCenter,
            "blnTwentyMsgMax" => $this->isTwentyMessagesMax,
            "blnUsNumFormat" => $this->isUsNumberFormatting,
            "defaultImagePreview" => (int)$this->getSettingsManager()->get(Constants\Setting::DEFAULT_IMAGE_PREVIEW, $auction->AccountId),

            // Complete urls and url templates
            "urlKeepAlive" => $urlProvider->buildKeepAliveUrl(),
            "urlLiveSaleM" => $urlProvider->buildLiveSaleUrlTemplate(),
            "urlLotInfoCatalogMobile" => $urlProvider->buildLotInfoCatalogMobileUrlTemplate(),
            "urlLotInfoChatFront" => $urlProvider->buildLotInfoChatFrontUrlTemplate(),
            "urlLotInfoHistoryFront" => $urlProvider->buildLotInfoHistoryFrontUrlTemplate(),
            "urlRedirectViewerLive" => $this->redirectUrl,
            "urlCatalogLotM" => $urlProvider->buildLotDetailsUrlTemplate(),
            "wsHost" => $urlProvider->buildRtbdUri($userType),
            // auction sound urls
            "urlSoundClickFromSoundManagerJsVendor" => $urlProvider->buildSoundClickFromSoundManagerJsVendor(),
            "urlSoundChimeFromSoundManagerJsVendor" => $urlProvider->buildSoundChimeFromSoundManagerJsVendor(),
            "urlSoundOnlineBidIncomingOnAdmin" => $urlProvider->buildSoundOnlineBidIncomingOnAdmin($this->cfg()->get('core->portal->mainAccountId')),
            "urlSoundPlaceBid" => $urlProvider->buildSoundPlaceBid($systemAccountId),
            "urlSoundBidAccepted" => $urlProvider->buildSoundBidAccepted($systemAccountId),
            "urlSoundOutbid" => $urlProvider->buildSoundOutbid($systemAccountId),
            "urlSoundSoldNotWon" => $urlProvider->buildSoundSoldNotWon($systemAccountId),
            "urlSoundSoldWon" => $urlProvider->buildSoundSoldWon($systemAccountId),
            "urlSoundPassed" => $urlProvider->buildSoundPassed($systemAccountId),
            "urlSoundFairWarning" => $urlProvider->buildSoundFairWarning($systemAccountId),
            "urlSoundPlay" => $urlProvider->buildSoundPlay($systemAccountId),
            "urlSoundBid" => $urlProvider->buildSoundBid($systemAccountId),

            // TODO: ...

            "secSwitch" => ($this->isSlideshowProjectorOnly && !$this->isProjector) ? 0 : $this->switchFrameSeconds,
            "currencies" => $currencyProps,
            "isMultiCurrency" => $isMultiCurrency,

            // Control info
            "pendingActionTimeoutHybrid" => (int)$this->getSettingsManager()
                ->get(Constants\Setting::PENDING_ACTION_TIMEOUT_HYBRID, $auction->AccountId),
            'rtbdConnectionUrl' => $this->getRtbGeneralHelper()->getRtbPingUri(Constants\Rtb::UT_CLIENT, $this->getAuctionId()),
            'rtbdConnectionTestTimeout' => $this->cfg()->get('core->rtb->connectionTest->timeout'),
        ];
        $this->getJsValueImporter()->injectValues($jsImportValues);

        $tr = $this->getTranslator();
        $jsImportTranslations = [
            "bidderClientAuctionclosed" => $tr->translateForRtb('BIDDERCLIENT_AUCTIONCLOSED', $auction),
            "bidderClientAuctioneer" => $tr->translateForRtb('BIDDERCLIENT_AUCTIONEER', $auction),
            "bidderClientCantLoadData" => $tr->translateForRtb('BIDDERCLIENT_CANTLOADDATA', $auction),
            "bidderClientCantLoadKeepAlive" => $tr->translateForRtb('BIDDERCLIENT_CANTLOAD_KEEPALIVE', $auction),
            "bidderClientCategory" => $tr->translateForRtb('BIDDERCLIENT_CATEGORY', $auction),
            "bidderClientConnectionTerminated" => $tr->translateForRtb('BIDDERCLIENT_CONNECTION_TERMINATED', $auction),
            "bidderClientConsuccess" => $tr->translateForRtb('BIDDERCLIENT_CONSUCCESS', $auction),
            "bidderClientQuantityRtb" => $tr->translateForRtb('BIDDERCLIENT_QUANTITY_RTB', $auction),
            "bidderClientReservenotmet" => $tr->translateForRtb('BIDDERCLIENT_RESERVENOTMET', $auction),
            "bidderClientShowunsold" => $tr->translateForRtb('BIDDERCLIENT_SHOWUNSOLD', $auction),
            "bidderClientYou" => $tr->translateForRtb('BIDDERCLIENT_YOU', $auction),
            "catalogBn" => $tr->translateForRtb('BIDDERCLIENT_BN', $auction),
            "catalogSoldThroughBuy" => $tr->translateForRtb('BIDDERCLIENT_SOLD_THROUGH_BUY', $auction),
            "generalNoBidderPrivilege" => $tr->translateForRtb('BIDDERCLIENT_NO_BIDDER_PRIVILEGE', $auction),
            "portNotice" => $this->getConsoleHelper()->portNotice($auction),
            // hybrid
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

        return ''; //Please, do not uncomment it. We refactor code. The new code is in assets/js/src/M/Auctions/LiveSale/ViewerHybrid.js now
    }

    /**
     * TODO: need to refactor this huge method, split its logic to separate class (ViewerHybridMobileControlRenderer class) methods. In fact, this method alone makes up more than 90% of the entire code of this class.
     * @param string $bidderNum '' when not auction bidder
     */
    public function render(string $bidderNum): void
    {
        $auction = $this->getAuction();
        $tr = $this->getTranslator();
        $sm = $this->getSettingsManager();
        $isLiveChat = (bool)$sm->get(Constants\Setting::LIVE_CHAT, $auction->AccountId);
        $isPlaceBidRequireCc = (bool)$sm->get(Constants\Setting::PLACE_BID_REQUIRE_CC, $auction->AccountId);
        $defaultImagePreview = (int)$sm->get(Constants\Setting::DEFAULT_IMAGE_PREVIEW, $auction->AccountId);
        $shouldRenderPreview = in_array($defaultImagePreview, Constants\SettingRtb::DIP_PREVIEW_ENABLED_OPTIONS);

        $hasCc = true;
        if (AuthIdentityManager::new()->isAuthorized()) {
            $hasCc = $this->getConsoleHelper()->hasCc($auction->AccountId);
        }
        $isViewer = !AuthIdentityManager::new()->isAuthorized() || ($isPlaceBidRequireCc && !$hasCc);
        $messageCenterHtml = $this->renderMobileMessageCenter();

        $auctionTzLocation = $this->getGeneralDataProvider()->detectAuctionTimezoneLocation();
        $dateFormatted = $this->getDateHelper()->formatUtcDate($auction->StartClosingDate, null, $auctionTzLocation);

        $currencyStyle = $this->getCurrencyExistenceChecker()->countAdditionalCurrencies($this->getAuctionId())
            ? ''
            : 'style="display:none;"';

        echo $this->renderBegin();
        try {
            // @formatter:off
?>
<div class="mobile-content-wrap">
    <div class="lot-header">
        <ul class="lot-header-elements">
            <li class="<?php echo RtbConsoleConstants::CLASS_LST_AUCTION_LOT_DETAILS; ?>">
                <div class="auction-details">
                    <div class="auction-title">
                        <?php echo $this->getAuctionRenderer()->renderName($auction, true); ?>
                    </div>
                    <div class="<?php echo RtbConsoleConstants::CLASS_BLK_AUCTION_DATE; ?>">
                        <?php echo $dateFormatted; ?>
                    </div>
                    <div class="auction-location">&nbsp;</div>
                </div>
                <div class="short-sep <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>"></div>
                <div class="<?php echo RtbConsoleConstants::CLASS_BLK_LOT_TITLE; ?> <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>">
                    <span class="lot-label"><?php echo $tr->translateForRtb('BIDDERCLIENT_LOT',$auction);?></span>
                    <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_LOT_NO) ?>&nbsp;<?php echo $tr->translateForRtb('BIDDERCLIENT_LOTOF',$auction);?>
                    <span class="num-lots"><?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_LOT_COUNT) ?></span>
                    <span class="<?php echo RtbConsoleConstants::CLASS_BLK_LOT_NAME; ?>"> - <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_LOT_NAME) ?></span>
                    <div class="<?php echo RtbConsoleConstants::CLASS_BLK_LOT_NUMBER; ?>"></div>
                </div>
                <div class="<?php echo RtbConsoleConstants::CLASS_BLK_GRP_TITLE; ?> <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>"></div>
            </li>
            <li class="live-bidder-num">
                <div class="bidder-num <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>"><?php echo $bidderNum; ?></div>
            </li>
        </ul>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
    <div class="lot-images-container <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>">
        <!-- this is supposed to be an image slider -->
        <!-- span class="slider-left"><img src="/m/images/live-slider-left.png" /></span -->
        <ul class="lot-images-slider <?php echo RtbConsoleConstants::CLASS_BLK_LOT_IMAGES; ?>">
            <li class="live-slide-left"><a class="<?php echo RtbConsoleConstants::CLASS_BLK_PREV_IMG; ?>"><img src="/m/images/live-slider-left.png" alt=""></a></li>
            <li class="current-image">
                <div class="curr-img-wrap lot-images-current">
                    <a onclick="return false;">
                        <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_LOT_IMAGE) ?>
                        <span class="img-overlay"></span>
                    </a>
                </div>
            </li>
            <li class="live-slide-right"><a class="<?php echo RtbConsoleConstants::CLASS_BTN_NEXT_IMG; ?>"><img src="/m/images/live-slider-right.png" alt=""></a></li>
        </ul>
        <!-- span class="slider-right"><img src="/m/images/live-slider-right.png" /></span -->
    </div>
    <div class="<?php echo RtbConsoleConstants::CLASS_BLK_LOT_BIDDING; ?> <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?> <?php echo $this->cssClassForSimultaneousAuction() ?>">
        <ul class="info">
            <li class="info-bidding">
                <p>
                    <?php echo $this->renderEstimates($auction); ?>
                </p>
            </li>
            <li class="info-links"></li>
        </ul>
        <ul class="bidding-main">
            <li class="current">
                <span class="current-bid-label"><?php echo $tr->translateForRtb('BIDDERCLIENT_CURRENTBID',$auction);?></span>
                <span class="current-bid-amt">
                    <span class="amt"><?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_CURRENT_BID) ?></span>
                </span>
            </li>
            <li class="current-btn">
<?php
            if (
                $isPlaceBidRequireCc
                && !$hasCc
            ) {
?>
                <div class="warning">
                    <?php echo $tr->translateForRtb('BIDDERCLIENT_PLACINGBID_WITH_NOCCINFO', $auction); ?>
                </div>
<?php
            } else {
?>
                <div class="unibtn">
                    <?php echo $this->renderControl(RtbConsoleConstants::CID_BTN_PLACE_BID);  ?>
                </div>
<?php
            }
?>
                <div class="bid-count-cont">
                    <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_BID_COUNTDOWN);  ?>
                </div>
                <div class="lot-end-countdown <?php echo $isViewer ? 'viewer' : ''; ?>">
                    <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_COUNTDOWN); ?>
                </div>
            </li>
        </ul>
        <div class="<?php echo RtbConsoleConstants::CLASS_BLK_CURRENCY_CONT; ?>" <?php echo $currencyStyle; ?>>
            <?php echo $tr->translateForRtb('BIDDERCLIENT_MYCURRENCY',$auction);?>: <br />
            <?php echo $this->renderControl(RtbConsoleConstants::CID_LST_CURRENCY) ?><br />
            <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_GROUP_BY) ?>
        </div>
        <div class="clear"></div>
        <hr class="sep" />
        <div id="<?php echo BidderBaseConstants::CID_BLK_BP_MEDIA; ?>"></div>
        <?php echo $messageCenterHtml; ?>

<?php
            if (
                $isLiveChat
                && AuthIdentityManager::new()->isAuthorized())
            {
?>
        <input type="text" name="temp" id="<?php echo BidderBaseConstants::CID_BLK_TEMP; ?>" style="display:none;"  title=""/>
        <div class="live-chat frm signfrm  <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>">
            <ul class="live-chat-controls">
                <li class="live-chat-input"><?php echo $this->renderControl(RtbConsoleConstants::CID_TXT_MESSAGE) ?></li>
                <li class="live-chat-btn"><span class="unibtn"><?php echo $this->renderControl(RtbConsoleConstants::CID_BTN_SEND_MESSAGE) ?></span></li>
            </ul>
        </div>
<?php
            }
?>
    </div>
    <div class="clear"></div>
    <div class="video-stream">
        <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_STREAM) ?>
        <?php echo $this->renderControl(RtbConsoleConstants::CID_SCR_STREAM) ?>
    </div>
    <div class="lot-description <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>">
        <div class="lot-description-header">
            <?php echo $tr->translateForRtb('BIDDERCLIENT_LOTDESC',$auction);?>
        </div>
        <div class="lot-description-content">
            <div class="<?php echo RtbConsoleConstants::CLASS_BLK_LOT_CATEGORY; ?> <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>"><?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_LOT_CATEGORY) ?></div>
            <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_LOT_DESCRIPTION) ?>
        </div>
    </div>
    <div class="clear"></div>
    <div class="lot-upcoming <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>">
        <h3><?php echo $tr->translateForRtb('BIDDERCLIENT_OTHERLOTS',$auction);?></h3>
        <div class="short-sep"></div>
        <div class="list-options">
            <ul class="list-opts">
                <li>
                    <span style="margin-right: 10px;">
                        <?php echo $this->renderControl(RtbConsoleConstants::CID_CHK_CATALOG_FOLLOW) ?>
                        <?php echo $tr->translateForRtb('BIDDERCLIENT_FOLLOW',$auction);?>
                    </span>
                </li>
                <li>
                    <span style="margin-right: 10px;"><?php echo $this->renderControl(RtbConsoleConstants::CID_RAD_CATALOG_UPCOMING) ?> <?php echo $tr->translateForRtb('BIDDERCLIENT_UPCOMING',$auction);?></span>
                    <span style="margin-right: 10px;"><?php echo $this->renderControl(RtbConsoleConstants::CID_RAD_CATALOG_PAST) ?> <?php echo $tr->translateForRtb('BIDDERCLIENT_PAST',$auction);?></span>
                </li>
                <li class="show-all frm">
                    <div class="ui-widget">
                        <?php echo $this->renderControl(RtbConsoleConstants::CID_LST_CATALOG_FILTER_STATUS) ?>
                        <div class="clear"></div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="clear"></div>
        <table class="<?php echo RtbConsoleConstants::CLASS_TBL_FOOTABLE; ?>">
            <thead>
            <tr>
<?php
            if ($shouldRenderPreview) {
?>
                <th class="icon"><?php echo $tr->translateForRtb('BIDDERCLIENT_IMGCOL', $auction);?></th>
<?php
            }
?>
                <th class="lot"><?php echo $tr->translateForRtb('BIDDERCLIENT_LOTCOL', $auction);?></th>
                <th class="title"><?php echo $tr->translateForRtb('BIDDERCLIENT_TITLECOL', $auction);?></th>
                <th class="countdown"><?php echo $tr->translateForRtb('BIDDERCLIENT_COWNTDOWNCOL', $auction);?></th>
                <th class="estimate"><?php echo $tr->translateForRtb('BIDDERCLIENT_ESTCOL', $auction);?></th>
                <th class="hammer"><?php echo $tr->translateForRtb('BIDDERCLIENT_HAMMERCOL', $auction);?></th>
            </tr>
            </thead>
        </table>
<?php
            if ($shouldRenderPreview) {
?>
        <div id="<?php echo BidderBaseConstants::CID_BLK_PREVIEW_IMAGE_CONTAINER;?>" class="default-img-view" style="display:none;">
            <table>
                <tr>
                    <td>
                        <img id="<?php echo BidderBaseConstants::CID_BLK_PREVIEW_IMAGE;?>" src="/images/spacer.png" alt="" />
                    </td>
                </tr>
            </table>
        </div>
<?php
           }
?>
        <div id="<?php echo BidderBaseConstants::CID_BLK_UPCOMING_SCROLL; ?>">
            <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_CATALOG) ?>
        </div>
    </div>
</div>

<?php
           // @formatter:on
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        echo $this->renderEnd();
    }
}
