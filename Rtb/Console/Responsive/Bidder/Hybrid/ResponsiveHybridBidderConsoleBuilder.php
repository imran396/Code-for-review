<?php

namespace Sam\Rtb\Console\Responsive\Bidder\Hybrid;

use DateTime;
use Exception;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Core\Constants;
use Sam\Core\Constants\Responsive\BidderBaseConstants;
use Sam\Core\Constants\Responsive\RtbConsoleConstants;
use Sam\Rtb\Console\Responsive\Bidder\Live\ResponsiveLiveBidderConsoleBuilder;
use Sam\Rtb\ConsoleHelper;

/**
 * Class ResponsiveHybridBidderConsoleBuilder
 */
class ResponsiveHybridBidderConsoleBuilder extends ResponsiveLiveBidderConsoleBuilder
{
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

            $rtbControlCollection = $this->getRtbControlCollection();
            $rtbControlBuilder = $this->createRtbControlBuilder();

            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(RtbConsoleConstants::CID_LBL_COUNTDOWN)
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(RtbConsoleConstants::CID_LBL_PENDING_TIMEOUT_SELECT_LOTS)
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(RtbConsoleConstants::CID_LBL_PENDING_TIMEOUT_SELECT_BUYER)
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
        $editorUserId = $this->getEditorUserId();
        $generalDataProvider = $this->getGeneralDataProvider();
        $isSimultaneous = $this->isSimultaneousAuctionAvailable();
        $urlProvider = $this->getUrlProvider();
        $userCreatedOn = $this->getEditorUser()->CreatedOn
            ? (new DateTime($this->getEditorUser()->CreatedOn))->getTimestamp()
            : '';
        $userType = Constants\Rtb::UT_BIDDER;
        $systemAccountId = $this->getSystemAccountId();

        $jsImportValues = [
            // Basic values
            "accountId" => $systemAccountId,
            "arrAucLCAccepted" => $dataProvider->loadChangesAgreementAcceptedAuctionLotIds(),
            "auctionCurrencySign" => $generalDataProvider->detectDefaultCurrencySign(),
            "auctionId" => $auction->Id,
            "auctionType" => $auction->AuctionType,
            "buyerGroupsIds" => $dataProvider->loadBuyerGroupIds(),
            "currencyId" => $auction->Currency,
            "exRate" => $dataProvider->detectExchangeRate(),
            "intOutstandingExceed" => (int)$dataProvider->isOutstandingLimitExceed(),
            "isBidderPanel" => true,
            "isHideUnsoldLots" => $auction->HideUnsoldLots,
            "isSimultaneousAuction" => $isSimultaneous,
            "lotCount" => $dataProvider->countLots(),
            "bidderInfo" => $dataProvider->detectBidderInfo(),
            "secSwitch" => $this->isSlideshowProjectorOnly ? 0 : $this->switchFrameSeconds,
            "sessionId" => $this->getSessionId(),
            "simultaneousAuctionId" => $this->getSimultaneousAuctionId(),
            "rtbUserTypeId" => $userType,
            "userId" => $editorUserId,
            "userCreatedOn" => (string)$userCreatedOn,
            "httpReferrer" => $this->createCookieHelper()->getHttpReferer(),
            "reportFormUrl" => $this->getUrlBuilder()->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_REPORT_PROBLEMS_GET_ERROR_FORM)
            ),
            "projectorImagesUrl" => $this->getUrlBuilder()->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_PROJECTOR_IMAGES_AJAX)
            ),

            // Basic values (hybrid specific)
            "auctionExtendTime" => $auction->ExtendTime,
            "auctionLotStartGapTime" => $auction->LotStartGapTime,
            "auctionStartTimezoneCode" => $generalDataProvider->detectAuctionStartTimezoneCode(),
            "auctionStartTimezoneName" => $generalDataProvider->detectAuctionTimezoneLocation(),
            "auctionStartTimezoneOffset" => $generalDataProvider->detectAuctionTimezoneOffset(),

            // Installation config options
            "rtbAutoRefreshTimeout" => $this->cfg()->get('core->rtb->autoRefreshTimeout') * 1000,
            "rtbBiddingInterestDelayMs" => $this->cfg()->get('core->rtb->biddingInterest->delayMs'),
            "rtbBiddingInterestEnabled" => (bool)$this->cfg()->get('core->rtb->biddingInterest->enabled'),
            "rtbCatalogPageLength" => $this->cfg()->get('core->rtb->catalog->pageLength'),
            "rtbConnectionRetryCount" => $this->cfg()->get('core->rtb->connectionRetryCount'),
            "rtbMessageCenterRenderedMessageCount" => $this->cfg()->get('core->rtb->messageCenter->renderedMessageCount'),
            "rtbPingInterval" => $this->cfg()->get('core->rtb->ping->interval'),
            "rtbPingQualityIndicator" => $this->cfg()->get('core->rtb->ping->qualityIndicator')->toArray(),
            "rtbPingVariance" => $this->cfg()->get('core->rtb->ping->variance'),
            "rtbSoundVolume" => $this->cfg()->get('core->rtb->soundVolume'),
            "rtbLazyLoadTimeout" => $this->cfg()->get('core->rtb->catalog->lazyLoadTimeout'),

            // System parameters
            "blnClearMsgCenter" => $this->isClearMessageCenter,
            "blnHideBidderNumber" => $this->isHideBidderNumber,
            "blnTwentyMsgMax" => $this->isTwentyMessagesMax,
            "blnUsNumFormat" => $this->isUsNumberFormatting,
            "pendingActionTimeoutHybrid" => $this->getSettingsManager()->get(Constants\Setting::PENDING_ACTION_TIMEOUT_HYBRID, $auction->AccountId),
            "defaultImagePreview" => (int)$this->getSettingsManager()->get(Constants\Setting::DEFAULT_IMAGE_PREVIEW, $auction->AccountId),

            // Complete urls and url templates
            "urlCatalogLotM" => $urlProvider->buildLotDetailsUrlTemplate(),
            "urlEmptyImageStubMedium" => $urlProvider->buildImageStubUrl(Constants\Image::MEDIUM),
            "urlKeepAlive" => $urlProvider->buildKeepAliveUrl(),
            "urlLiveSaleM" => $urlProvider->buildLiveSaleUrlTemplate(),
            "urlLotInfoCatalogMobile" => $urlProvider->buildLotInfoCatalogMobileUrlTemplate(),
            "urlLotInfoChatFront" => $urlProvider->buildLotInfoChatFrontUrlTemplate(),
            "urlLotInfoHistoryFront" => $urlProvider->buildLotInfoHistoryFrontUrlTemplate(),
            "urlLotItemBuyer" => $urlProvider->buildLotBuyerDataUrlTemplate(),
            "urlLotItemCatalog" => $urlProvider->buildLotCatalogDataUrlTemplate(),
            "urlLotItemGroup" => $urlProvider->buildLotGroupDataUrlTemplate(),
            "urlLotItemPreview" => $urlProvider->buildLotPreviewUrlTemplate(),
            "urlReportProblemsLiveSale" => $urlProvider->buildReportProblemUrl(),
            "urlSpecialTermsAndConditionsM" => $urlProvider->buildSpecialTermsUrlTemplate(),
            "wsHost" => $urlProvider->buildRtbdUri($userType),
            'rtbdConnectionUrl' => $this->getRtbGeneralHelper()->getRtbPingUri(Constants\Rtb::UT_CLIENT, $this->getAuctionId()),
            'rtbdConnectionTestTimeout' => $this->cfg()->get('core->rtb->connectionTest->timeout'),

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
            "urlSoundBid" => $urlProvider->buildSoundBid($systemAccountId)
        ];
        $this->getJsValueImporter()->injectValues($jsImportValues);

        $tr = $this->getTranslator();
        $jsImportTranslations = [
            "bidderClientApprox" => $tr->translateForRtb('BIDDERCLIENT_APPROX', $auction),
            "bidderClientAuctionclosed" => $tr->translateForRtb('BIDDERCLIENT_AUCTIONCLOSED', $auction),
            "bidderClientAuctioneer" => $tr->translateForRtb('BIDDERCLIENT_AUCTIONEER', $auction),
            "bidderClientBidderFasterButton" => $tr->translateForRtb('BIDDERCLIENT_BIDDER_FASTER_BUTTON', $auction),
            "bidderClientBidderOutbid" => $tr->translateForRtb('BIDDERCLIENT_BIDDER_OUTBID', $auction),
            "bidderClientBidderOutbidButton" => $tr->translateForRtb('BIDDERCLIENT_BIDDER_OUTBID_BUTTON', $auction),
            "bidderClientBidderSpecialTerms" => $tr->translateForRtb('BIDDERCLIENT_BIDDER_SPECIAL_TERMS', $auction),
            "bidderClientBidxxnow" => $tr->translateForRtb('BIDDERCLIENT_BIDXXNOW', $auction),
            "bidderClientCantKeepAlive" => $tr->translateForRtb('BIDDERCLIENT_CANTLOAD_KEEPALIVE', $auction),
            "bidderClientCantLoadData" => $tr->translateForRtb('BIDDERCLIENT_CANTLOADDATA', $auction),
            "bidderClientCategory" => $tr->translateForRtb('BIDDERCLIENT_CATEGORY', $auction),
            "bidderClientConnectionTerminated" => $tr->translateForRtb('BIDDERCLIENT_CONNECTION_TERMINATED', $auction),
            "bidderClientConsuccess" => $tr->translateForRtb('BIDDERCLIENT_CONSUCCESS', $auction),
            "bidderClientDownloadwon" => $tr->translateForRtb('BIDDERCLIENT_DOWNLOADWON', $auction),
            "bidderClientFloorbidder" => $tr->translateForRtb('BIDDERCLIENT_FLOORBIDDER', $auction),
            "bidderClientFloorbidder2" => $tr->translateForRtb('BIDDERCLIENT_FLOORBIDDER2', $auction),
            "bidderClientHowManyLots" => $tr->translateForRtb('BIDDERCLIENT_HOWMANY_LOTS', $auction),
            "bidderClientInternetbidder" => $tr->translateForRtb('BIDDERCLIENT_INTERNETBIDDER', $auction),
            "bidderClientInternetbidder2" => $tr->translateForRtb('BIDDERCLIENT_INTERNETBIDDER2', $auction),
            "bidderClientMsgAskingbid" => $tr->translateForRtb('BIDDERCLIENT_MSG_ASKINGBID', $auction),
            "bidderClientMsgYourhighbid" => $tr->translateForRtb('BIDDERCLIENT_MSG_YOURHIGHBID', $auction),
            "bidderClientOutbid" => $tr->translateForRtb('BIDDERCLIENT_OUTBID', $auction),
            "bidderClientOutstandingLimitExceeded" => $tr->translateForRtb('BIDDERCLIENT_OUTSTANDING_LIMIT_EXCEEDED', $auction),
            "bidderClientQuantityRtb" => $tr->translateForRtb('BIDDERCLIENT_QUANTITY_RTB', $auction),
            "bidderClientReservenotmet" => $tr->translateForRtb('BIDDERCLIENT_RESERVENOTMET', $auction),
            "bidderClientSelectGroup" => $tr->translateForRtb('BIDDERCLIENT_SELECTGROUP', $auction),
            "bidderClientSelectLot" => $tr->translateForRtb('BIDDERCLIENT_SELECT_LOT', $auction),
            "bidderClientShowunsold" => $tr->translateForRtb('BIDDERCLIENT_SHOWUNSOLD', $auction),
            "bidderClientUnsold" => $tr->translateForRtb('BIDDERCLIENT_UNSOLD', $auction),
            "bidderClientYou" => $tr->translateForRtb('BIDDERCLIENT_YOU', $auction),
            "bidderClientYourbid" => $tr->translateForRtb('BIDDERCLIENT_YOURBID', $auction),
            "catalogBn" => $tr->translateForRtb('BIDDERCLIENT_BN', $auction),
            "catalogSoldThroughBuy" => $tr->translateForRtb('BIDDERCLIENT_SOLD_THROUGH_BUY', $auction),
            "errorReportDialogButtonCancel" => $tr->translateForRtb('BIDDERCLIENT_ERROR_REPORTING_DIALOG_BUTTON_CANCEL', $auction), // SAM-5235
            "errorReportDialogButtonSend" => $tr->translateForRtb('BIDDERCLIENT_ERROR_REPORTING_DIALOG_BUTTON_SEND', $auction), // SAM-5235
            "generalRestrictedGroup" => $tr->translateForRtb('BIDDERCLIENT_RESTRICTED_GROUP', $auction),
            "portNotice" => ConsoleHelper::new()->portNotice($auction),

            // hybrid
            "bidderClientGapTimeCountdown" => $tr->translateForRtb('BIDDERCLIENT_GAP_TIME_COUNTDOWN', $auction),
            "catalogRtbCountdownDays" => $tr->translateForRtb('BIDDERCLIENT_COUNTDOWN_DAYS', $auction),
            "catalogRtbCountdownHrs" => $tr->translateForRtb('BIDDERCLIENT_COUNTDOWN_HRS', $auction),
            "catalogRtbCountdownMins" => $tr->translateForRtb('BIDDERCLIENT_COUNTDOWN_MINS', $auction),
            "catalogRtbCountdownSecs" => $tr->translateForRtb('BIDDERCLIENT_COUNTDOWN_SECS', $auction),
            "rtbdConnectionFailedMessage" => sprintf(
                $tr->translateForRtb('BIDDERCLIENT_RTBD_CONNECTION_FAILED_MSG', $auction),
                $this->getRtbGeneralHelper()->getPublicHost(),
                $this->getRtbGeneralHelper()->getPublicPort()
            )
        ];
        $this->getJsValueImporter()->injectTranslations($jsImportTranslations);
        return '';
    }

    /**
     * @param string $bidderNum '' when not auction bidder
     */
    public function render(string $bidderNum): void
    {
        $auction = $this->getAuction();
        $auctionAccountId = $auction->AccountId;
        $tr = $this->getTranslator();
        $sm = $this->getSettingsManager();
        $isLiveChat = (bool)$sm->get(Constants\Setting::LIVE_CHAT, $auctionAccountId);
        $defaultImagePreview = (int)$sm->get(Constants\Setting::DEFAULT_IMAGE_PREVIEW, $auctionAccountId);
        $shouldRenderPreview = in_array($defaultImagePreview, Constants\SettingRtb::DIP_PREVIEW_ENABLED_OPTIONS);
        $isRejectedToBidBecauseCcInfo = $this->getConsoleHelper()->isRejectedToBidBecauseCcInfo($auctionAccountId);
        $messageCenterHtml = $this->renderMobileMessageCenter();
        $auctionTzLocation = $this->getGeneralDataProvider()->detectAuctionTimezoneLocation();
        $dateFormatted = $this->getDateHelper()->formatUtcDate($auction->StartClosingDate, null, $auctionTzLocation);

        echo $this->renderBegin();
        try {
            // @formatter:off
?>
        <div class="mobile-content-wrap">
            <div class="clear">
                <span class="link-report-problem">
                    <a href="#" id="<?php echo BidderBaseConstants::CID_BTN_REPORT_PROBLEMS; ?>">
                        <?php echo $tr->translateForRtb('BIDDERCLIENT_REPORT_PROBLEMS', $auction); ?>
                    </a>
                </span>
            </div>
            <div id="<?php echo BidderBaseConstants::CID_BLK_DIALOG_FORM_CONTAINER; ?>"></div>
            <div class="lot-header">
                <ul class="lot-header-elements">
                    <li class="<?php echo RtbConsoleConstants::CLASS_LST_AUCTION_LOT_DETAILS; ?>">
                        <div class="<?php echo RtbConsoleConstants::CLASS_BLK_LOT_ITEMS_WON; ?>"><?php echo $this->renderControl(RtbConsoleConstants::CID_LNK_ITEMS_WON) ?></div>
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
                        </div>
                        <div class="<?php echo RtbConsoleConstants::CLASS_BLK_GRP_TITLE; ?> <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>"></div>
                    </li>
                    <li class="live-bidder-num <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>">
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
                    <li class="live-slide-left"><a class="<?php echo RtbConsoleConstants::CLASS_BLK_PREV_IMG; ?>"><img src="/m/images/live-slider-left.png"  alt=""></a></li>
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
                </ul>
                <ul class="bidding-main">
                    <li class="current">
                        <span class="current-bid-label"><?php echo $tr->translateForRtb('BIDDERCLIENT_CURRENTBID', $auction);?></span>
                        <span class="current-bid-amt">
                            <span class="amt"><?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_CURRENT_BID) ?></span>
                        </span>
                        </li>
                        <li class="current-btn">
<?php
            if ($isRejectedToBidBecauseCcInfo) {
?>
                            <div class="warning">
                                <?php echo $tr->translateForRtb('BIDDERCLIENT_PLACINGBID_WITH_NOCCINFO', $auction); ?>
                            </div>
<?php
            } else {
?>
                            <div class="unibtn <?php echo RtbConsoleConstants::CLASS_BLK_PLACE_CONT; ?>">
                                <?php echo $this->renderControl(RtbConsoleConstants::CID_BTN_PLACE_BID);  ?>
                            </div>
<?php
            }
?>
                            <div class="bid-count-cont">
                                <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_BID_COUNTDOWN); ?>
                            </div>
                            <div class="lot-end-countdown">
                                <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_COUNTDOWN); ?>
                            </div>
                        </li>
                    </ul>
<?php
            $style = $this->getCurrencyExistenceChecker()->countAdditionalCurrencies($this->getAuctionId())
                ? ''
                : 'style="display:none;"';
?>
                    <div class="<?php echo RtbConsoleConstants::CLASS_BLK_CURRENCY_CONT; ?>" <?php echo $style; ?>>
                        <?php echo $tr->translateForRtb('BIDDERCLIENT_MYCURRENCY', $auction); ?>: <br/>
                        <?php echo $this->renderControl(RtbConsoleConstants::CID_LST_CURRENCY) ?><br/>
                        <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_GROUP_BY) ?>
                    </div>
                    <div class="clear"></div>
                    <hr class="sep"/>
                    <div id="<?php echo BidderBaseConstants::CID_BLK_BP_MEDIA; ?>"></div>
                    <?php echo $messageCenterHtml; ?>

<?php
            if (
                $isLiveChat
                && $this->createAuthIdentityManager()->isAuthorized()
            ) {
?>
                    <input type="text" name="temp" id="<?php echo BidderBaseConstants::CID_BLK_TEMP; ?>" style="display:none;" title=""/>
                    <div class="live-chat frm signfrm  <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>">
                        <ul class="live-chat-controls">
                            <li class="live-chat-input"><?php echo $this->renderControl(RtbConsoleConstants::CID_TXT_MESSAGE) ?></li>
                            <li class="live-chat-btn">
                                <span class="unibtn"><?php echo $this->renderControl(RtbConsoleConstants::CID_BTN_SEND_MESSAGE) ?></span>
                            </li>
                        </ul>
                    </div>
<?php
            }
?>
                </div>
                <div class="clear"></div>
                <div class="video-stream">
                    <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_STREAM)?>
                    <?php echo $this->renderControl(RtbConsoleConstants::CID_SCR_STREAM)?>
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
                                    <?php echo $tr->translateForRtb('BIDDERCLIENT_FOLLOW', $auction); ?>
                                </span>
                            </li>
                            <li>
                                <span style="margin-right: 10px;">
                                    <?php echo $this->renderControl(RtbConsoleConstants::CID_RAD_CATALOG_UPCOMING) ?><?php echo $tr->translateForRtb('BIDDERCLIENT_UPCOMING', $auction); ?>
                                </span>
                                <span style="margin-right: 10px;">
                                    <?php echo $this->renderControl(RtbConsoleConstants::CID_RAD_CATALOG_PAST) ?><?php echo $tr->translateForRtb('BIDDERCLIENT_PAST', $auction); ?>
                                </span>
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
                    <div id="<?php echo BidderBaseConstants::CID_BLK_PREVIEW_IMAGE_CONTAINER; ?>" class="default-img-view" style="display:none;">
                        <table>
                            <tr>
                                <td>
                                    <img id="<?php echo BidderBaseConstants::CID_BLK_PREVIEW_IMAGE; ?>" src="/images/spacer.png" alt="" />
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

                <div id="<?php echo BidderBaseConstants::CID_BLK_ADD_LOTS; ?>" title="<?php echo $tr->translateForRtb('BIDDERCLIENT_SELECT_GROUPED_LOTS_TITLE', $auction); ?>">
                    <div>
                        <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_GROUP_PRICE) ?><br/>
                        <div style="text-align:center;">
                            <?php echo $this->renderControl(RtbConsoleConstants::CID_LST_GROUP_QTY) ?>
                            <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_GROUP_LOTS) ?>
                            <div class="clear"></div>
                            <?php echo $this->renderControl(RtbConsoleConstants::CID_BTN_GROUP_ADD) ?>
                            <?php echo $this->renderControl(RtbConsoleConstants::CID_BNT_GROUP_OK) ?>
                            <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_PENDING_TIMEOUT_SELECT_LOTS) ?>
                            <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_GROUP_LOT_PREVIEW) ?>
                        </div>
                    </div>
                </div>

                <div id="<?php echo BidderBaseConstants::CID_BLK_SEL_BUYER; ?>" title="<?php echo $tr->translateForRtb('BIDDERCLIENT_SELECT_BUYER_BY_AGENT_TITLE', $auction); ?>">
                    <div>
                        <?php echo $tr->translateForRtb('BIDDERCLIENT_SELECT_BUYER_BY_AGENT_QUESTION', $auction); ?>
                        <br/>
                        <div style="text-align:center;">
                            <?php echo $this->renderControl(RtbConsoleConstants::CID_LST_SELECT_BUYER) ?>
                            <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_SELECT_BUYER) ?>
                            <div class="clear"></div>
                            <?php echo $this->renderControl(RtbConsoleConstants::CID_BTN_SUB_BUYER) ?>
                            <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_PENDING_TIMEOUT_SELECT_BUYER) ?>
                        </div>
                    </div>
                </div>

                <div id="<?php echo BidderBaseConstants::CID_BLK_LOT_CHANGES; ?>" title="<?php echo $tr->translateForRtb('BIDDERCLIENT_CONFIRM_CHANGES', $auction); ?>">
                    <div>
                        <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_LOT_CHANGES) ?>
                        <div style="text-align:center;">
                            <?php echo $this->renderControl(RtbConsoleConstants::CID_TXT_LOT_CHANGES) ?>
                            <div id="<?php echo BidderBaseConstants::CID_BLK_CHANGES_CONFIRM; ?>">
                                <?php echo $this->renderControl(RtbConsoleConstants::CID_CHK_LOT_CHANGES_AGREE) ?>
                                <?php echo $tr->translateForRtb('BIDDERCLIENT_ICONFIRM_THIS_CHANGES', $auction); ?>
                            </div>
                            <?php echo $this->renderControl(RtbConsoleConstants::CID_BTN_LOT_CHANGES_OK) ?>
                            <?php echo $this->renderControl(RtbConsoleConstants::CID_BTN_LOT_CHANGES_CANCEL) ?>
                        </div>
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
