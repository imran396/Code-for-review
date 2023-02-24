<?php

namespace Sam\Rtb\Console\Responsive\Viewer\Live;

use Auction;
use DateTime;
use Exception;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilder;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Auction\SaleGroup\SaleGroupManagerAwareTrait;
use Sam\Auction\Validate\AuctionStatusCheckerAwareTrait;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\ConsoleBaseConstants;
use Sam\Core\Constants\Responsive\BidderBaseConstants;
use Sam\Core\Constants\Responsive\RtbConsoleConstants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Currency\Load\AuctionCurrencyLoaderAwareTrait;
use Sam\Currency\Validate\CurrencyExistenceCheckerAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Rtb\Console\Internal\AbstractConsoleBuilder;
use Sam\Rtb\Console\Internal\Load\GeneralDataProviderAwareTrait;
use Sam\Rtb\Console\Responsive\Internal\Load\DataProviderAwareTrait;
use Sam\Rtb\Console\Responsive\Internal\Url\UrlProviderAwareTrait;
use Sam\Rtb\ConsoleHelper;
use Sam\Rtb\Control\LotTitle\Load\RunningLotTitleDataLoaderCreateTrait;
use Sam\Rtb\Control\Render\RtbControlBuilderCreateTrait;
use Sam\Rtb\Video\VideoStreamCreateTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Timezone\Load\TimezoneLoader;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class ResponsiveLiveViewerConsoleBuilder
 */
class ResponsiveLiveViewerConsoleBuilder extends AbstractConsoleBuilder
{
    use AuctionBidderHelperAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use AuctionCurrencyLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use AuctionStatusCheckerAwareTrait;
    use AuthIdentityManagerCreateTrait;
    use BackUrlParserAwareTrait;
    use CurrencyExistenceCheckerAwareTrait;
    use DataProviderAwareTrait;
    use DateHelperAwareTrait;
    use EditorUserAwareTrait;
    use GeneralDataProviderAwareTrait;
    use ParamFetcherForGetAwareTrait;
    use ParamFetcherForRouteAwareTrait;
    use RtbControlBuilderCreateTrait;
    use RunningLotTitleDataLoaderCreateTrait;
    use SaleGroupManagerAwareTrait;
    use ServerRequestReaderAwareTrait;
    use SystemAccountAwareTrait;
    use UrlParserAwareTrait;
    use UrlProviderAwareTrait;
    use UserLoaderAwareTrait;
    use VideoStreamCreateTrait;

    public bool $isProjector = false;
    public bool $isProjectorSimple = false;
    public ?string $redirectUrl = null;
    protected ?int $userType = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @return static
     */
    public function construct(int $auctionId): static
    {
        parent::construct($auctionId);
        $this->getDataProvider()->construct($auctionId, $this->getEditorUserId());
        $this->getGeneralDataProvider()->construct($auctionId, $this->getEditorUserId());
        $this->getUrlProvider()->construct($auctionId);
        $this->initControls();
        return $this;
    }

    public function initControls(): void
    {
        $urlProvider = $this->getUrlProvider();
        $auction = $this->getAuction();
        try {
            parent::initControls();

            $tr = $this->getTranslator();

            $rtbControlCollection = $this->getRtbControlCollection();
            $rtbControlBuilder = $this->createRtbControlBuilder();

            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(ConsoleBaseConstants::CID_LBL_STATUS)
            );

            $highestLotNum = $this->createRunningLotTitleDataLoader()->detectHighestLotNum($this->getAuctionId(), true);
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(
                    RtbConsoleConstants::CID_LBL_LOT_COUNT,
                    ['html' => $highestLotNum]
                )
            );

            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(RtbConsoleConstants::CID_LBL_LOT_NO)
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(RtbConsoleConstants::CID_LBL_LOT_NAME)
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(RtbConsoleConstants::CID_LBL_LOT_CATEGORY)
            );

            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(
                    RtbConsoleConstants::CID_LBL_LOT_IMAGE,
                    ['class' => 'wrap', 'html' => '<img src~"' . $urlProvider->buildImageStubUrl(Constants\Image::MEDIUM) . '" />']
                )
            );


            // YV, 2020-08, SAM-6328:
            // we load info about images at JS assets/js/src/M/Auctions/LiveSale/ResponseHandlers/Helpers/LoadData.js::load()
            // with ajax request to '/lot-info/info_mobile_<auctionId>.txt?id=<lotId>'
            $lotEmptyImageUrl = UrlBuilder::new()->build(
                LotImageUrlConfig::new()->constructEmptyStub(Constants\Image::MEDIUM, $auction->AccountId)
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(
                    RtbConsoleConstants::CID_LBL_LOT_IMAGE_BIG,
                    ['html' => "<img src~{$lotEmptyImageUrl} />"]
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(RtbConsoleConstants::CID_LBL_LOT_IMAGE_BIG_THUMB)
            );

            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(RtbConsoleConstants::CID_LBL_LOW_ESTIMATE)
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(RtbConsoleConstants::CID_LBL_HIGH_ESTIMATE)
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(RtbConsoleConstants::CID_LBL_CURRENT_BID)
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(RtbConsoleConstants::CID_LBL_ASKING_BID)
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(RtbConsoleConstants::CID_LBL_RESERVE_MET)
            );

            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(
                    RtbConsoleConstants::CID_LBL_LOT_DESCRIPTION,
                    ['html' => '']
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(
                    RtbConsoleConstants::CID_LBL_MESSAGE,
                    ['class' => 'messages']
                )
            );

            $currentUserId = $this->getEditorUserId();

            if (!$currentUserId) {
                $liveSaleUrl = $urlProvider->buildLiveSaleUrl();
                $liveSaleUrl = $this->getUrlParser()->replaceParams($liveSaleUrl, [Constants\UrlParam::GOTO_AUCTION_REGISTRATION => 1]);
                $loginUrl = $urlProvider->buildLoginUrl();
                $this->redirectUrl = $this->getBackUrlParser()->replace($loginUrl, $liveSaleUrl);
                $langLoginToBid = $tr->translateForRtb('BIDDERCLIENT_LOGINTOBID', $auction);
                $rtbControlCollection->add(
                    $rtbControlBuilder->buildButton(
                        RtbConsoleConstants::CID_BTN_PLACE_BID,
                        ['class' => 'orng live-bid', 'html' => $langLoginToBid, 'value' => 'Confirm']
                    )
                );
            } else {
                $auctionBidder = $this->getAuctionBidderLoader()->load($currentUserId, $this->getAuctionId(), true);
                if (
                    $auctionBidder
                    && !$this->getAuctionBidderHelper()->isApproved($auctionBidder)
                ) {
                    $langLoginToBid = $tr->translateForRtb('BIDDERCLIENT_PENDINGAPPROVAL', $this->getAuction());
                    $rtbControlCollection->add(
                        $rtbControlBuilder->buildButton(
                            RtbConsoleConstants::CID_BTN_PLACE_BID,
                            ['class' => 'grey live-bid disabled', 'disabled' => 'disabled', 'html' => $langLoginToBid, 'value' => 'Confirm']
                        )
                    );
                } else {
                    $this->redirectUrl = $urlProvider->buildAuctionRegisterUrl();
                    if ($this->getParamFetcherForGet()->has(Constants\UrlParam::GOTO_AUCTION_REGISTRATION)) {
                        $this->createApplicationRedirector()->redirect($this->redirectUrl);
                    }
                    $userAuthentication = $this->getUserLoader()->loadUserAuthenticationOrCreate($currentUserId, true);
                    if (
                        $userAuthentication->VerificationCode !== Constants\User::VC_NOCODE
                        && $userAuthentication->VerificationCode !== ''
                    ) {
                        $translateField = !$userAuthentication->EmailVerified ? 'BIDDERCLIENT_VERIFY_ACCOUNT' : 'BIDDERCLIENT_REGISTERTOBID';
                        $langRegister = $tr->translateForRtb($translateField, $auction);
                    } else {
                        $langRegister = $tr->translateForRtb('BIDDERCLIENT_REGISTERTOBID', $auction);
                    }

                    if ($this->getAuctionStatusChecker()->isRegistrationActive($this->getAuctionId())) {
                        $rtbControlCollection->add(
                            $rtbControlBuilder->buildButton(
                                RtbConsoleConstants::CID_BTN_PLACE_BID,
                                ['class' => 'orng live-bid', 'html' => $langRegister, 'value' => 'Confirm']
                            )
                        );
                    } else {
                        $rtbControlCollection->add(
                            $rtbControlBuilder->buildLink(
                                RtbConsoleConstants::CID_BTN_PLACE_BID,
                                ['class' => 'grey live-bid disabled', 'disabled' => 'disabled', 'html' => $langRegister, 'value' => 'Confirm']
                            )
                        );
                    }
                }
            }

            $rtbControlCollection->add(
                $rtbControlBuilder->buildUl(RtbConsoleConstants::CID_LBL_CATALOG)
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildCheckbox(
                    RtbConsoleConstants::CID_CHK_CATALOG_FOLLOW,
                    ['checked' => 'checked', 'disabled' => 'disabled']
                )
            );

            $rtbControlCollection->add(
                $rtbControlBuilder->buildRadio(
                    RtbConsoleConstants::CID_RAD_CATALOG_UPCOMING,
                    ['checked' => 'checked', 'disabled' => 'disabled', 'value' => 'Upcoming']
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildRadio(
                    RtbConsoleConstants::CID_RAD_CATALOG_PAST,
                    ['disabled' => 'disabled', 'value' => 'Past']
                )
            );

            $rtbControlCollection->add(
                $rtbControlBuilder->buildCheckbox(
                    RtbConsoleConstants::CID_CHK_SOUND,
                    ['class' => 'chk-sound', 'checked' => 'false']
                )
            );

            $rtbControlCollection->add(
                $rtbControlBuilder->buildLink(RtbConsoleConstants::CID_LNK_ITEMS_WON)
            );

            $rtbControlCollection->add(
                $rtbControlBuilder->buildButton(
                    RtbConsoleConstants::CID_BTN_GROUP_ADD,
                    ['html' => 'Confirm', 'value' => 'Confirm']
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSelect(RtbConsoleConstants::CID_LST_GROUP_QTY)
            );

            $rtbControlCollection->add(
                $rtbControlBuilder->buildButton(
                    RtbConsoleConstants::CID_BNT_GROUP_OK,
                    ['class' => 'button', 'html' => 'Ok', 'value' => 'OK']
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildButton(
                    RtbConsoleConstants::CID_BTN_GROUP_CANCEL,
                    ['html' => 'Cancel', 'value' => 'Cancel']
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(RtbConsoleConstants::CID_LBL_GROUP_LOTS)
            );

            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(RtbConsoleConstants::CID_LBL_GROUP_PRICE)
            );

            $rtbControlCollection->add(
                $rtbControlBuilder->buildSelect(
                    RtbConsoleConstants::CID_LST_CURRENCY,
                    ['option' => ':']

                )
            );

            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(
                    RtbConsoleConstants::CID_LBL_GROUP_BY,
                    ['class' => 'group-by']
                )
            );

            $rtbControlCollection->add(
                $rtbControlBuilder->buildText(
                    RtbConsoleConstants::CID_TXT_MESSAGE,
                    ['class' => 'txt-msg', 'disabled' => 'disabled', 'maxlength' => '200']
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildLink(
                    RtbConsoleConstants::CID_BTN_SEND_MESSAGE,
                    [
                        'class' => 'drkblu disabled',
                        'disabled' => 'disabled',
                        'html' => 'Send',
                        'value' => 'Send',
                    ]
                )
            );

            if ($auction->Simultaneous) {
                $rtbControlCollection->add(
                    $rtbControlBuilder->buildDiv(
                        RtbConsoleConstants::CID_LBL_MESSAGE_2,
                        ['class' => 'messages']
                    )
                );
                $rtbControlCollection->add(
                    $rtbControlBuilder->buildCheckbox(
                        RtbConsoleConstants::CID_CHK_SOUND_2,
                        ['class' => 'chk-sound2', 'checked' => 'false']
                    )
                );

                $rtbControlCollection->add(
                    $rtbControlBuilder->buildRadio(
                        RtbConsoleConstants::CID_RAD_AUCTION_1,
                        ['name' => 'simul-auction', 'checked' => 'checked', 'class' => 'rad-bid-sale1']
                    )
                );
                $rtbControlCollection->add(
                    $rtbControlBuilder->buildRadio(
                        RtbConsoleConstants::CID_RAD_AUCTION_2,
                        ['class' => 'rad-bid-sale2', 'name' => 'simul-auction']
                    )
                );
            }
            $langShowTexts = $tr->translateForRtb('BIDDERCLIENT_SHOWALL', $auction) . ':' . RtbConsoleConstants::CATALOG_FILTER_STATUS_ALL . '|' .
                $tr->translateForRtb('BIDDERCLIENT_SHOWSOLD', $auction) . ':' . RtbConsoleConstants::CATALOG_FILTER_STATUS_SOLD . '|' .
                $tr->translateForRtb('BIDDERCLIENT_SHOWUNSOLD', $auction) . ':' . RtbConsoleConstants::CATALOG_FILTER_STATUS_UNSOLD;

            $rtbControlCollection->add(
                $rtbControlBuilder->buildSelect(
                    RtbConsoleConstants::CID_LST_CATALOG_FILTER_STATUS,
                    ['class' => 'combobox', 'option' => $langShowTexts]
                )
            );

            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(
                    RtbConsoleConstants::CID_LBL_BID_COUNTDOWN,
                    ['class' => 'bid-countdown']
                )
            );
            $html = '';
            $js = '';

            if (
                $this->cfg()->get('core->vendor->bidpathStreaming->enabled')
                && in_array(
                    $auction->StreamDisplay,
                    [Constants\Auction::SD_BPAV, Constants\Auction::SD_BPA, Constants\Auction::SD_BPV],
                    true
                )
            ) {
                $html = $this->createVideoStream()
                    ->construct()
                    ->getPlayerHtml($auction->StreamDisplay, $this->getAuctionId());
            }

            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(
                    RtbConsoleConstants::CID_LBL_STREAM,
                    ['html' => $html]
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildScript(
                    RtbConsoleConstants::CID_SCR_STREAM,
                    ['script' => $js]
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
        $dataProvider = $this->getDataProvider();
        $generalDataProvider = $this->getGeneralDataProvider();
        $urlProvider = $this->getUrlProvider();
        $userType = Constants\Rtb::UT_VIEWER;
        $auction = $this->getAuction();
        $actionName = $this->getParamFetcherForRoute()->getActionName();
        $isSimultaneous = $this->isSimultaneousAuctionAvailable();
        // IK: Looks like "Multi currency" works for projector page only
        $isMultiCurrency = ($this->isMultiCurrency
            && $actionName !== Constants\ResponsiveRoute::AA_LIVE_SALE)
            ? 'true' : 'false';
        $editorUser = $this->getEditorUser();
        $userCreatedOn = $editorUser && $editorUser->CreatedOn
            ? (new DateTime($editorUser->CreatedOn))->getTimestamp()
            : '';
        $systemAccountId = $this->getSystemAccountId();

        $mainCurrencyId = $auction->Currency ?: $this->getCurrencyLoader()->detectDefaultCurrencyId($auction->Id, true);
        $auctionCurrency = $this->getCurrencyLoader()->load($mainCurrencyId, true);
        $exRate = $auctionCurrency ? $auctionCurrency->ExRate : 0;

        $currencyProps = [];
        $currencies = $this->getCurrencyLoader()->loadCurrenciesForAuction($auction->Id, $mainCurrencyId, true);
        foreach ($currencies as $currency) {
            $currencyProps[] = [
                'name' => $currency->Name,
                'sign' => $currency->Sign,
                'exRate' => $currency->ExRate
            ];
        }

        // YV: "consoleType" repeats "typeId", We can't remove it or rename. We need both of them as for Projector userType we create ResponsiveLiveViewerConsoleBuilder - console type.
        $jsImportValues = [
            // Basic values
            "accountId" => $systemAccountId,
            "auctionCurrencySign" => $generalDataProvider->detectDefaultCurrencySign(),
            "auctionId" => $this->getAuctionId(),
            "auctionType" => $auction->AuctionType,
            "blnProjector" => $this->isProjector,
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

            // Installation config options
            "rtbAutoRefreshTimeout" => $this->cfg()->get('core->rtb->autoRefreshTimeout') * 1000,
            "rtbBiddingInterestDelayMs" => $this->cfg()->get('core->rtb->biddingInterest->delayMs'),
            "rtbCatalogPageLength" => $this->cfg()->get('core->rtb->catalog->pageLength'),
            "rtbConnectionRetryCount" => $this->cfg()->get('core->rtb->connectionRetryCount'),
            "rtbMessageCenterRenderedMessageCount" => $this->cfg()->get('core->rtb->messageCenter->renderedMessageCount'),
            "rtbProjectorAmountCachedImages" => $this->cfg()->get('core->rtb->projector->amountCachedImages'),
            "rtbProjectorNumberRoundPrecision" => $this->isProjector ? $this->cfg()->get('core->rtb->projector->numberRoundPrecision') : 2,
            "rtbSoundVolume" => $this->cfg()->get('core->rtb->soundVolume'),
            "rtbLazyLoadTimeout" => $this->cfg()->get('core->rtb->catalog->lazyLoadTimeout'),

            // System parameters
            "blnUsNumFormat" => $this->isUsNumberFormatting,
            "blnTwentyMsgMax" => $this->isTwentyMessagesMax,
            "blnClearMsgCenter" => $this->isClearMessageCenter,
            "systemParamsSlideshowProjectorOnly" => (bool)$this->getSettingsManager()->get(Constants\Setting::SLIDESHOW_PROJECTOR_ONLY, $auction->AccountId),
            "systemParamsSwitchFrameSeconds" => (int)$this->getSettingsManager()->get(Constants\Setting::SWITCH_FRAME_SECONDS, $auction->AccountId),
            "defaultImagePreview" => (int)$this->getSettingsManager()->get(Constants\Setting::DEFAULT_IMAGE_PREVIEW, $auction->AccountId),

            // Complete urls and url templates
            "urlCatalogLotM" => $urlProvider->buildLotDetailsUrlTemplate(),
            "urlKeepAlive" => $urlProvider->buildKeepAliveUrl(),
            "urlLiveSaleM" => $urlProvider->buildLiveSaleUrlTemplate(),
            "urlLotInfoCatalogMobile" => $urlProvider->buildLotInfoCatalogMobileUrlTemplate(),
            "urlLotInfoChatFront" => $urlProvider->buildLotInfoChatFrontUrlTemplate(),
            "urlLotInfoHistoryFront" => $urlProvider->buildLotInfoHistoryFrontUrlTemplate(),
            "urlRedirectViewerLive" => $this->redirectUrl,
            "wsHost" => $urlProvider->buildRtbdUri($userType),
            // auction sound urls
            "urlEmptyImageStubMedium" => $urlProvider->buildImageStubUrl(Constants\Image::MEDIUM),
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
            "exRate" => $exRate,
            "isMultiCurrency" => $isMultiCurrency,

            // Control info
            "rtbPingInterval" => $this->cfg()->get('core->rtb->ping->interval'),
            "rtbPingQualityIndicator" => $this->cfg()->get('core->rtb->ping->qualityIndicator')->toArray(),
            "rtbPingVariance" => $this->cfg()->get('core->rtb->ping->variance'),
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
            "portNotice" => ConsoleHelper::new()->portNotice($auction),
            'rtbdConnectionFailedMessage' => sprintf(
                $tr->translateForRtb('BIDDERCLIENT_RTBD_CONNECTION_FAILED_MSG', $auction),
                $this->getRtbGeneralHelper()->getPublicHost(),
                $this->getRtbGeneralHelper()->getPublicPort()
            )
        ];
        $this->getJsValueImporter()->injectTranslations($jsImportTranslations);

        return '';
    }

    /**
     * @return string
     */
    public function renderMobileMessageCenter(): string
    {
        if ($this->isSimultaneousAuctionAvailable()) {
            $output = $this->renderSimultaneousAuctionMessageCenter();
        } else {
            $output = $this->renderRunningAuctionMessageCenter();
        }
        $output .= '<div id="' . RtbConsoleConstants::CID_BLK_CHAT_MESSAGES . '"></div>';
        return $output;
    }

    /**
     * @return string
     */
    public function renderRunningAuctionMessageCenter(): string
    {
        $langPlaySounds = $this->getTranslator()->translateForRtb('BIDDERCLIENT_MOBILE_PLAYSOUNDS', $this->getAuction());
        $cidChkSound1 = RtbConsoleConstants::CID_CHK_SOUND;
        $chkSound1 = $this->renderControl($cidChkSound1);
        $lblMessage1 = $this->renderControl(RtbConsoleConstants::CID_LBL_MESSAGE);
        $output = <<<MSG1

<div class="lot-messages">
    <div class="sound"><span class="sound-label">{$langPlaySounds}</span>{$chkSound1}<label for="{$cidChkSound1}"></label></div>
    <div class="messages">
        {$lblMessage1}
    </div>
</div>
MSG1;
        return $output;
    }

    /**
     * @return string
     */
    public function renderSimultaneousAuctionMessageCenter(): string
    {
        $tr = $this->getTranslator();
        $auction = $this->getAuction();
        $rows = $this->getSaleGroupManager()->loadAuctionRows(
            $auction->SaleGroup,
            $auction->AccountId,
            false,
            [$this->getAuctionId()]
        );
        $row = !empty($rows) ? $rows[0] : null;
        if (!$row) {
            log_error("Simultaneous auction data not found" . composeSuffix(['a' => $auction->Id]));
            return '';
        }

        $langPlaySounds = $tr->translateForRtb('BIDDERCLIENT_MOBILE_PLAYSOUNDS', $auction);
        $lblMessage1 = $this->renderControl(RtbConsoleConstants::CID_LBL_MESSAGE);
        $lblMessage2 = $this->renderControl(RtbConsoleConstants::CID_LBL_MESSAGE_2);
        $cidChkSound1 = RtbConsoleConstants::CID_CHK_SOUND;
        $cidChkSound2 = RtbConsoleConstants::CID_CHK_SOUND_2;
        $chkSound1 = $this->renderControl($cidChkSound1);
        $chkSound2 = $this->renderControl($cidChkSound2);
        $saleNo1 = ($auction->SaleNum + ord($auction->SaleNumExt));
        $saleNo2 = ($row['sale_num'] + ord($row['sale_num_ext']));
        $auctionId1 = $this->getAuctionId();
        $auctionId2 = (int)$row['id'];
        $auctionName1 = '(' . $this->getAuctionRenderer()->renderName($auction, true) . ')';
        $auctionName2 = '(' . $row['name'] . ')';
        $liveSaleUrl = $this->getUrlProvider()->buildLiveSaleUrlForSimultaneousAuction(
            (int)$row['id'],
            $row['auction_seo_url'],
            (int)$row['account_id']
        );
        $newTabHtml = ' <a href="' . $liveSaleUrl . '" class="new-tab" target="_blank">'
            . $tr->translateForRtb('BIDDERCLIENT_OPEN_NEW_TAB', $auction)
            . '</a>';

        if ($saleNo1 < $saleNo2) {
            $radAuction1Html = $this->renderControl(RtbConsoleConstants::CID_RAD_AUCTION_1, ['value' => $auctionId1]);
            $radAuction1Html .= $tr->translateForRtb('BIDDERCLIENT_BID_IN_THIS_SALE', $auction);
            $radAuction2Html = $this->renderControl(RtbConsoleConstants::CID_RAD_AUCTION_2, ['value' => $auctionId2]);
            $radAuction2Html .= $tr->translateForRtb('BIDDERCLIENT_BID_IN_THIS_SALE', $auction);

            $hideCloseClass = RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE;
            $output = <<<HTML
<div class="lot-messages {$hideCloseClass} active">
    <div class="sound">
        <span class="sound-label">{$langPlaySounds}</span>
        {$chkSound1}
        <label for="{$cidChkSound1}"></label> 
        <span class="simul-auction">{$auctionName1}{$radAuction1Html}</span>
    </div>
    <div class="messages">
        {$lblMessage1}
    </div>
</div>

<div class="lot-messages-2 {$hideCloseClass}">
    <div class="sound2">
        <span class="sound-label2">{$langPlaySounds}</span>
        {$chkSound2}
        <label for="{$cidChkSound2}"></label> 
        <span class="simul-auction">{$auctionName2}{$radAuction2Html}{$newTabHtml}</span>
    </div>
    <div class="messages">
        {$lblMessage2}
    </div>
</div>

HTML;
        } else {
            $radAuction1Html = $this->renderControl(RtbConsoleConstants::CID_RAD_AUCTION_1, ['value' => $auctionId1, 'class' => 'rad-bid-sale2']);
            $radAuction1Html .= $tr->translateForRtb('BIDDERCLIENT_BID_IN_THIS_SALE', $auction);
            $radAuction2Html = $this->renderControl(RtbConsoleConstants::CID_RAD_AUCTION_2, ['value' => $auctionId2, 'class' => 'rad-bid-sale1']);
            $radAuction2Html .= $tr->translateForRtb('BIDDERCLIENT_BID_IN_THIS_SALE', $auction);

            $hideCloseClass = RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE;
            $output = <<<HTML
<div class="lot-messages {$hideCloseClass}">
    <div class="sound2">
        <span class="sound-label2">{$langPlaySounds}</span>
        {$chkSound2}
        <label for="{$cidChkSound2}"></label>
        <span class="simul-auction">{$auctionName2}{$radAuction2Html}{$newTabHtml}</span>
    </div>
    <div class="messages">
        {$lblMessage2}
    </div>
</div>

<div class="lot-messages-2 {$hideCloseClass} active">
    <div class="sound">
        <span class="sound-label">{$langPlaySounds}</span>
        {$chkSound1}
        <label for="{$cidChkSound1}"></label>
        <span class="simul-auction">{$auctionName1}{$radAuction1Html}</span>
    </div>
    <div class="messages">
        {$lblMessage1}
    </div>
</div>


HTML;
        }
        return $output;
    }

    /**
     * @param string $bidderNum '' when not auction bidder
     */
    public function render(string $bidderNum): void
    {
        $auction = $this->getAuction();
        $tr = $this->getTranslator();
        $sm = $this->getSettingsManager();

        $isLiveChat = $sm->get(Constants\Setting::LIVE_CHAT, $auction->AccountId)
            && $this->createAuthIdentityManager()->isAuthorized();
        $isPlaceBidRequireCc = (bool)$sm->get(Constants\Setting::PLACE_BID_REQUIRE_CC, $auction->AccountId);
        $defaultImagePreview = (int)$sm->get(Constants\Setting::DEFAULT_IMAGE_PREVIEW, $auction->AccountId);
        $shouldRenderPreview = in_array($defaultImagePreview, Constants\SettingRtb::DIP_PREVIEW_ENABLED_OPTIONS);
        $hasCc = true;
        if ($this->createAuthIdentityManager()->isAuthorized()) {
            $hasCc = $this->getConsoleHelper()->hasCc($auction->AccountId);
        }
        $messageCenterHtml = $this->renderMobileMessageCenter();

        $auctionTimezone = TimezoneLoader::new()->load($auction->TimezoneId, true);
        $auctionTzLocation = $auctionTimezone->Location ?? null;
        $dateFormatted = $this->getDateHelper()->formatUtcDate($auction->StartClosingDate, null, $auctionTzLocation);

        echo $this->renderBegin();
        // @formatter:off
        try {
?>
            <div class="mobile-content-wrap">
                <div class="lot-header">
                    <ul class="lot-header-elements">
                        <li class="<?php echo RtbConsoleConstants::CLASS_LST_AUCTION_LOT_DETAILS; ?>">
                            <div class="auction-details">
                                <div class="auction-title">
                                    <?php echo ee($this->getAuctionRenderer()->renderName($auction, true)); ?>
                                </div>
                                <div class="<?php echo RtbConsoleConstants::CLASS_BLK_AUCTION_DATE; ?>">
                                    <?php echo $dateFormatted; ?>
                                </div>
                                <div class="auction-location">&nbsp;</div>
                            </div>
                            <div class="short-sep <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>"></div>
                            <div class="<?php echo RtbConsoleConstants::CLASS_BLK_LOT_TITLE; ?> <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>">
                                <span class="lot-label">
                                    <?php echo $tr->translateForRtb('BIDDERCLIENT_LOT', $auction); ?>
                                </span>
                                <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_LOT_NO) ?>
                                &nbsp;<?php echo $tr->translateForRtb('BIDDERCLIENT_LOTOF', $auction); ?>
                                <span class="num-lots">
                                    <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_LOT_COUNT) ?>
                                </span>
                                <span class="<?php echo RtbConsoleConstants::CLASS_BLK_LOT_NAME; ?>"> - <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_LOT_NAME); ?></span>
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
                <div id="<?php echo RtbConsoleConstants::CID_BLK_GROUP_SLIDESHOW; ?>"></div>
                <div class="lot-images-container <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>">
                    <!-- this is supposed to be an image slider -->
                    <!-- span class="slider-left"><img src="/m/images/live-slider-left.png" /></span -->
                    <ul class="lot-images-slider <?php echo RtbConsoleConstants::CLASS_BLK_LOT_IMAGES; ?>">
                        <li class="live-slide-left">
                            <a class="<?php echo RtbConsoleConstants::CLASS_BLK_PREV_IMG; ?>">
                                <img src="/m/images/live-slider-left.png" alt="">
                            </a>
                        </li>
                        <li class="current-image">
                            <div class="curr-img-wrap lot-images-current">
                                <a onclick="return false;">
                                    <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_LOT_IMAGE) ?>
                                    <span class="img-overlay"></span>
                                </a>
                            </div>
                        </li>
                        <li class="live-slide-right">
                            <a class="<?php echo RtbConsoleConstants::CLASS_BTN_NEXT_IMG; ?>">
                                <img src="/m/images/live-slider-right.png" alt="">
                            </a>
                        </li>
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
                            <span class="current-bid-label"><?php echo $tr->translateForRtb('BIDDERCLIENT_CURRENTBID', $auction); ?></span>
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
                                    <?php echo $this->renderControl(RtbConsoleConstants::CID_BTN_PLACE_BID); ?>
                                </div>
<?php
            }
?>
                            <div class="bid-count-cont">
                                <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_BID_COUNTDOWN); ?>
                            </div>
                        </li>
                    </ul>
<?php
            $style = $this->getCurrencyExistenceChecker()->countAdditionalCurrencies($this->getAuctionId())
                ? ''
                : 'style="display:none;"';
?>
                    <div class="<?php echo RtbConsoleConstants::CLASS_BLK_CURRENCY_CONT; ?>" <?php echo $style; ?>>
                        <?php echo $tr->translateForRtb('BIDDERCLIENT_MYCURRENCY', $auction); ?>:
                        <br/>
                        <?php echo $this->renderControl(RtbConsoleConstants::CID_LST_CURRENCY) ?><br/>
                        <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_GROUP_BY) ?>
                    </div>
                    <div class="clear"></div>
                    <hr class="sep"/>
                    <div id="<?php echo BidderBaseConstants::CID_BLK_BP_MEDIA; ?>"></div>
                    <?php echo $messageCenterHtml; ?>

<?php
            if ($isLiveChat) {
?>
                        <input type="text" name="temp" id="<?php echo BidderBaseConstants::CID_BLK_TEMP; ?>temp" style="display:none;" title=""/>
                        <div class="live-chat frm signfrm  <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>">
                            <ul class="live-chat-controls">
                                <li class="live-chat-input">
                                    <?php echo $this->renderControl(RtbConsoleConstants::CID_TXT_MESSAGE) ?>
                                </li>
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
                    <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_STREAM) ?>
                    <?php echo $this->renderControl(RtbConsoleConstants::CID_SCR_STREAM) ?>
                </div>
                <div class="lot-description <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?> ">
                    <div class="lot-description-header">
                        <?php echo $tr->translateForRtb('BIDDERCLIENT_LOTDESC', $auction); ?>
                    </div>
                    <div class="lot-description-content">
                        <div class="<?php echo RtbConsoleConstants::CLASS_BLK_LOT_CATEGORY; ?> <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>"><?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_LOT_CATEGORY) ?></div>
                        <?php echo $this->renderControl(RtbConsoleConstants::CID_LBL_LOT_DESCRIPTION) ?>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="lot-upcoming <?php echo RtbConsoleConstants::CLASS_BLK_HIDE_CLOSE; ?>">
                    <h3><?php echo $tr->translateForRtb('BIDDERCLIENT_OTHERLOTS', $auction); ?></h3>
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
                                    <?php echo $this->renderControl(RtbConsoleConstants::CID_RAD_CATALOG_UPCOMING) ?>
                                    <?php echo $tr->translateForRtb('BIDDERCLIENT_UPCOMING', $auction); ?>
                                </span>
                                <span style="margin-right: 10px;">
                                    <?php echo $this->renderControl(RtbConsoleConstants::CID_RAD_CATALOG_PAST) ?>
                                    <?php echo $tr->translateForRtb('BIDDERCLIENT_PAST', $auction); ?>
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
                                <th class="icon"><?php echo $tr->translateForRtb('BIDDERCLIENT_IMGCOL', $auction); ?></th>
<?php
            }
?>
                            <th class="lot"><?php echo $tr->translateForRtb('BIDDERCLIENT_LOTCOL', $auction); ?></th>
                            <th class="title"><?php echo $tr->translateForRtb('BIDDERCLIENT_TITLECOL', $auction); ?></th>
                            <th class="estimate"><?php echo $tr->translateForRtb('BIDDERCLIENT_ESTCOL', $auction); ?></th>
                            <th class="hammer"><?php echo $tr->translateForRtb('BIDDERCLIENT_HAMMERCOL', $auction); ?></th>
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
                                        <img id="<?php echo BidderBaseConstants::CID_BLK_PREVIEW_IMAGE; ?>" src="/images/spacer.png" alt=""/>
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

    /**
     * @return int
     */
    public function getUserType(): int
    {
        if ($this->userType === null) {
            $this->userType = Constants\Rtb::UT_VIEWER;
        }
        return $this->userType;
    }

    /**
     * @param int $userType
     * @return static
     */
    public function setUserType(int $userType): static
    {
        $this->userType = Cast::toInt($userType, Constants\Rtb::$userTypes);
        return $this;
    }

    /**
     * Render Estimates block
     * @param Auction $auction
     * @return string
     */
    protected function renderEstimates(Auction $auction): string
    {
        $sm = $this->getSettingsManager();
        $isShowLowEst = (bool)$sm->get(Constants\Setting::SHOW_LOW_EST, $auction->AccountId);
        $isShowHighEst = (bool)$sm->get(Constants\Setting::SHOW_HIGH_EST, $auction->AccountId);
        $divVis = '';
        $estDash = '';
        if (
            !$isShowLowEst
            && !$isShowHighEst
        ) {
            $divVis = 'style="display: none;"';
        } elseif (
            $isShowLowEst
            && $isShowHighEst
        ) {
            $estDash = ' - ';
        }

        $estimatesHtml = '';
        if ($isShowLowEst) {
            $estimatesHtml .= $this->renderControl(RtbConsoleConstants::CID_LBL_LOW_ESTIMATE);
        }
        $estimatesHtml .= $estDash;
        if ($isShowHighEst) {
            $estimatesHtml .= $this->renderControl(RtbConsoleConstants::CID_LBL_HIGH_ESTIMATE);
        }

        $langEstimate = $this->getTranslator()->translateForRtb('BIDDERCLIENT_ESTIMATE', $auction);
        $estAmountClass = RtbConsoleConstants::CLASS_BLK_EST_AMOUNT;
        $estLabelClass = RtbConsoleConstants::CLASS_BLK_EST_LABEL;
        $html = <<<HTML

<span class="{$estLabelClass}" {$divVis}>
    {$langEstimate}
</span>
<span class="{$estAmountClass}">
    {$estimatesHtml}
</span>

HTML;
        return $html;
    }
}
