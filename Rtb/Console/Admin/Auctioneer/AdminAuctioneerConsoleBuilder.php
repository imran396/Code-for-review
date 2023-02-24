<?php

namespace Sam\Rtb\Console\Admin\Auctioneer;

use DateTime;
use Exception;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilder;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\AuctioneerConsoleConstants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Rtb\Console\Admin\Internal\Url\UrlProviderAwareTrait;
use Sam\Rtb\Console\Internal\AbstractConsoleBuilder;
use Sam\Rtb\Console\Internal\Load\GeneralDataProviderAwareTrait;
use Sam\Rtb\Control\Render\RtbControlBuilderCreateTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class AdminAuctioneerConsoleBuilder
 */
class AdminAuctioneerConsoleBuilder extends AbstractConsoleBuilder
{
    use EditorUserAwareTrait;
    use GeneralDataProviderAwareTrait;
    use RtbControlBuilderCreateTrait;
    use SystemAccountAwareTrait;
    use UrlProviderAwareTrait;

    /**
     * @var bool
     */
    public bool $isBiddingInterest = false;

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
        $this->isBiddingInterest = $this->cfg()->get('core->rtb->biddingInterest->enabled');
        $this->getGeneralDataProvider()->construct($auctionId, $this->getEditorUserId());
        $this->getUrlProvider()->construct($auctionId);
        $this->initControls();
        return $this;
    }

    public function initControls(): void
    {
        $generalDataProvider = $this->getGeneralDataProvider();
        try {
            parent::initControls();

            $rtbControlCollection = $this->getRtbControlCollection();
            $rtbControlBuilder = $this->createRtbControlBuilder();

            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(
                    AuctioneerConsoleConstants::CID_LBL_LOT_NO
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(
                    AuctioneerConsoleConstants::CID_LBL_LOT_COUNT,
                    ['html' => $generalDataProvider->detectHighestLotNum()]
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(
                    AuctioneerConsoleConstants::CID_LBL_LOT_NAME
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildLink(
                    AuctioneerConsoleConstants::CID_LBL_LOT_DETAIL,
                    ['html' => 'View lot detail screen', 'href' => 'javascript:void(0);']
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(
                    AuctioneerConsoleConstants::CID_LBL_LOW_ESTIMATE
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(
                    AuctioneerConsoleConstants::CID_LBL_HIGH_ESTIMATE
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(
                    AuctioneerConsoleConstants::CID_LBL_RESERVE_PRICE
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(
                    AuctioneerConsoleConstants::CID_LBL_CURRENT_BID
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(
                    AuctioneerConsoleConstants::CID_LBL_OWNER
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(
                    AuctioneerConsoleConstants::CID_LBL_ASKING_BID
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildCheckbox(
                    AuctioneerConsoleConstants::CID_CHK_CATALOG_FOLLOW,
                    ['checked' => 'checked', 'disabled' => 'disabled',]
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildRadio(
                    AuctioneerConsoleConstants::CID_RAD_CATALOG_UPCOMING,
                    ['checked' => 'checked', 'disabled' => 'disabled', 'value' => 'Upcoming',]
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildRadio(
                    AuctioneerConsoleConstants::CID_RAD_CATALOG_PAST,
                    ['disabled' => 'disabled', 'value' => 'Past',]
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildTable(
                    AuctioneerConsoleConstants::CID_LBL_CATALOG,
                    ['style' => 'width:100%;',]
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(
                    AuctioneerConsoleConstants::CID_LBL_LOT_IMAGE
                )
            );

            $lotEmptyImageUrl = UrlBuilder::new()->build(
                LotImageUrlConfig::new()->constructEmptyStub(Constants\Image::MEDIUM, $this->getAuction()->AccountId)
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(
                    AuctioneerConsoleConstants::CID_LBL_LOT_IMAGE_BIG,
                    ['html' => sprintf('<img src~"%s" />', $lotEmptyImageUrl)]
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(
                    AuctioneerConsoleConstants::CID_LBL_CONNECTED_USER,
                    ['class' => 'rtb-users']
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(
                    AuctioneerConsoleConstants::CID_FRM_CONNECTED_USER,
                    ['class' => 'number-of-connected']
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(
                    AuctioneerConsoleConstants::CID_FRM_INTERESTED_BIDDER,
                    ['class' => 'rtb-interested-bidders-list-data-area']
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildLink(
                    AuctioneerConsoleConstants::CID_LNK_AUCTION_RESULT
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildLink(
                    AuctioneerConsoleConstants::CID_LNK_AUCTION_HISTORY
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildText(
                    AuctioneerConsoleConstants::CID_TXT_BIDDER
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildButton(
                    AuctioneerConsoleConstants::CID_BTN_BIDDER_CONFIRM,
                    ['disabled' => 'disabled', 'value' => 'Confirm Bidder #', 'html' => 'Confirm Bidder #',]
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(
                    AuctioneerConsoleConstants::CID_LBL_ABSENTEE_BID_HIGH
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(
                    AuctioneerConsoleConstants::CID_LBL_ABSENTEE_BID_SECOND
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(
                    AuctioneerConsoleConstants::CID_LBL_GROUP_TYPE
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildDiv(
                    AuctioneerConsoleConstants::CID_LBL_NOTICE
                )
            );
            $invoiceLogoUrl = $this->getUrlProvider()->buildInvoiceLogoUrl();
            $rtbControlCollection->add(
                $rtbControlBuilder->buildImg(
                    AuctioneerConsoleConstants::CID_IMG_INVOICE_LOGO,
                    ['src' => $invoiceLogoUrl]
                )
            );
            $rtbControlCollection->add(
                $rtbControlBuilder->buildSpan(
                    AuctioneerConsoleConstants::CID_LBL_BIDDER_ADDRESS
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
        $generalDataProvider = $this->getGeneralDataProvider();
        $urlProvider = $this->getUrlProvider();
        $userType = Constants\Rtb::UT_AUCTIONEER;
        $userCreatedOn = $this->getEditorUser()->CreatedOn
            ? (new DateTime($this->getEditorUser()->CreatedOn))->getTimestamp()
            : '';

        $jsImportValues = [
            // Basic values
            "accountId" => $this->getSystemAccountId(),
            "auctionCurrencySign" => $generalDataProvider->detectDefaultCurrencySign(),
            "auctionId" => $auction->Id,
            "auctionType" => $auction->AuctionType,
            "sessionId" => $this->getSessionId(),
            "typeId" => $userType,
            "userCreatedOn" => $userCreatedOn,
            "userId" => $this->getEditorUserId(),

            // Installation config options
            "arrLiveBiddingCountdown" => $this->liveBiddingCountdowns,
            "blnUsNumFormat" => $this->isUsNumberFormatting,
            "rtbAutoRefreshTimeout" => $this->cfg()->get('core->rtb->autoRefreshTimeout') * 1000,
            "rtbConnectionRetryCount" => $this->cfg()->get('core->rtb->connectionRetryCount'),
            "rtbContextMenuEnabled" => $this->cfg()->get('core->rtb->contextMenuEnabled'),
            "rtbPingInterval" => $this->cfg()->get('core->rtb->ping->interval'),
            "rtbPingQualityIndicator" => $this->cfg()->get('core->rtb->ping->qualityIndicator')->toArray(),
            "rtbPingVariance" => $this->cfg()->get('core->rtb->ping->variance'),
            "rtbSoundVolume" => $this->cfg()->get('core->rtb->soundVolume'),

            // Console validation info
            "isError" => !$success,
            "strError" => $errorMessage,

            // Complete urls and url templates
            "reopenUrl" => $urlProvider->buildAuctionReopenUrl(),
            "runUrl" => $urlProvider->buildAuctionRunUrl(),
            "urlAddRtbMessage" => $urlProvider->buildMessageCenterAddUrlTemplate(),
            "urlCatalogLotM" => $urlProvider->buildLotDetailsUrlTemplate(),
            "urlCenterRtbMessage" => $urlProvider->buildMessageCenterDataUrlTemplate(),
            "urlDelRtbMessage" => $urlProvider->buildMessageCenterDeleteUrlTemplate(),
            "urlEmptyImageStubLarge" => $urlProvider->buildImageStubUrl(Constants\Image::LARGE),
            "urlLotItemCatalog" => $urlProvider->buildLotCatalogDataUrlTemplate(),
            "urlManageBidderInterest" => $urlProvider->buildBidderInterestDataUrlTemplate(),
            "urlManageRtbUsers" => $urlProvider->buildConnectedUsersDataUrlTemplate(),
            "urlUserEdit" => $urlProvider->buildUserEditUrlTemplate(),
            "urlSoundOnlineBidIncomingOnAdmin" => $urlProvider->buildSoundOnlineBidIncomingOnAdmin($this->cfg()->get('core->portal->mainAccountId')),
            "urlSoundClickFromSoundManagerJsVendor" => $urlProvider->buildSoundClickFromSoundManagerJsVendor(),
            "urlSoundChimeFromSoundManagerJsVendor" => $urlProvider->buildSoundChimeFromSoundManagerJsVendor(),
            "wsHost" => $urlProvider->buildRtbdUri($userType),
        ];
        $this->getJsValueImporter()->injectValues($jsImportValues);

        $tr = $this->getTranslator();
        $jsImportTranslations = [
            "bidderClientAuctioneer" => $tr->translateForRtb('BIDDERCLIENT_AUCTIONEER', $auction),
            "bidderClientConnectionTerminated" => $tr->translateForRtb('BIDDERCLIENT_CONNECTION_TERMINATED', $auction),
            "bidderClientConsuccess" => $tr->translateForRtb('BIDDERCLIENT_CONSUCCESS', $auction),
            "bidderClientQuantityRtb" => $tr->translateForRtb('BIDDERCLIENT_QUANTITY_RTB', $auction),
            "catalogBn" => $tr->translateForRtb('BIDDERCLIENT_BN', $auction),
            "catalogSoldThroughBuy" => $tr->translateForRtb('BIDDERCLIENT_SOLD_THROUGH_BUY', $auction),
        ];
        $this->getJsValueImporter()->injectTranslations($jsImportTranslations);
        return '';
    }
}
