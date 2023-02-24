<?php
/**
 * SAM-6758: Rtb console output builders
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Console\Responsive\Internal\Url;

use Sam\Application\Url\Build\Config\Asset\SoundUrlConfig;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionRegistrationUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveLiveSaleUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\AnySingleAuctionLotUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Auth\ResponsiveLoginUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class UrlProvider
 * @package Sam\Rtb\Console\Responsive\Internal
 */
class UrlProvider extends CustomizableClass
{
    use AuctionAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param array $optionals
     * @return $this
     */
    public function construct(int $auctionId, array $optionals = []): static
    {
        $this->setAuctionId($auctionId);
        return $this;
    }

    /**
     * --- Web page urls ---
     */

    // -- Console url --

    /**
     * Public uri to rtbd server
     * @param int $userType
     * @return string
     */
    public function buildRtbdUri(int $userType): string
    {
        return $this->getRtbGeneralHelper()->getRtbdUri($userType, $this->getAuctionId());
    }

    // -- Auction urls --

    /**
     * Return url template for constructing "Live Sale" page url of responsive side.
     * @return string
     */
    public function buildLiveSaleUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveLiveSaleUrlConfig::new()->forTemplate()
        );
    }

    /**
     * Return url template for constructing "Lot Preview" page url of responsive side.
     * @return string
     */
    public function buildReportProblemUrl(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::P_REPORT_PROBLEMS_LIVE_SALE, $this->getAuctionId())
        );
    }

    /**
     * Return url for constructing "Live Sale" page url of responsive side for simultaneous auction
     * @param int $auctionId
     * @param string|null $seoUrl
     * @param int $accountId
     * @return string
     */
    public function buildLiveSaleUrlForSimultaneousAuction(int $auctionId, ?string $seoUrl, int $accountId): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveLiveSaleUrlConfig::new()->forWeb(
                $auctionId,
                $seoUrl,
                [UrlConfigConstants::OP_ACCOUNT_ID => $accountId]
            )
        );
    }

    /**
     * Return "Auction Registration" page url
     * @return string
     */
    public function buildAuctionRegisterUrl(): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveAuctionRegistrationUrlConfig::new()->forWeb($this->getAuctionId())
        );
    }

    /**
     * Return url for "Live Sale" page
     * @return string
     */
    public function buildLiveSaleUrl(): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveLiveSaleUrlConfig::new()->forWeb($this->getAuctionId())
        );
    }

    // -- Lot urls --

    /**
     * Return url template for constructing "Lot Details" page url of responsive side.
     * @return string
     */
    public function buildLotDetailsUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveLotDetailsUrlConfig::new()->forTemplate()
        );
    }

    /**
     * Return url template for constructing "Lot Preview" page url of responsive side.
     * @return string
     */
    public function buildLotPreviewUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionLotUrlConfig::new()->forTemplateByType(Constants\Url::P_LOT_ITEM_PREVIEW)
        );
    }

    /**
     * Return url template for constructing "Special Terms and Conditions" page url of responsive side.
     * @return string
     */
    public function buildSpecialTermsUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionLotUrlConfig::new()->forTemplateByType(Constants\Url::P_REGISTER_SPECIAL_TERMS_AND_CONDITIONS)
        );
    }

    // -- User urls --

    /**
     * Return url for "Responsive Login" page
     * @return string
     */
    public function buildLoginUrl(): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveLoginUrlConfig::new()->forWeb()
        );
    }

    // -- Image urls --

    /**
     * Return url of default empty image with size expected by responsive consoles
     * @param string $size
     * @return string
     */
    public function buildImageStubUrl(string $size): string
    {
        return $this->getUrlBuilder()->build(
            LotImageUrlConfig::new()->constructEmptyStub($size, $this->getAuction()->AccountId)
        );
    }

    /**
     * --- Non-web page urls ---
     */

    /**
     * Return url template for constructing route to read lot grouping data from server.
     * @return string
     */
    public function buildLotGroupDataUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forTemplateByType(Constants\Url::P_LOT_ITEM_GROUP)
        );
    }

    /**
     * Return url template for constructing route to read lot catalog data from server.
     * @return string
     */
    public function buildLotCatalogDataUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forTemplateByType(Constants\Url::A_LOT_ITEM_CATALOG)
        );
    }

    /**
     * Return url template for constructing route to read lot buyer data from server.
     * @return string
     */
    public function buildLotBuyerDataUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forTemplateByType(Constants\Url::P_LOT_ITEM_BUYER)
        );
    }

    /**
     * Return url template for constructing route to read lot info front chat data from server.
     * @return string
     */
    public function buildLotInfoCatalogMobileUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionLotUrlConfig::new()->forTemplateByType(Constants\Url::P_LOT_INFO_CATALOG_MOBILE)
        );
    }

    /**
     * Return url template for constructing route to read lot info front chat data from server.
     * @return string
     */
    public function buildLotInfoChatFrontUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionLotUrlConfig::new()->forTemplateByType(Constants\Url::P_LOT_INFO_CHAT_FRONT)
        );
    }

    /**
     * Return url template for constructing route to read lot info history chat data from server.
     * @return string
     */
    public function buildLotInfoHistoryFrontUrlTemplate(): string
    {
        return $this->getUrlBuilder()->build(
            AnySingleAuctionLotUrlConfig::new()->forTemplateByType(Constants\Url::P_LOT_INFO_HISTORY_FRONT)
        );
    }

    /**
     * @return string
     */
    public function buildKeepAliveUrl(): string
    {
        return $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_AUCTIONS_KEEP_ALIVE)
        );
    }

    // --- Sound urls

    public function buildSoundClickFromSoundManagerJsVendor(): string
    {
        return $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_SOUND_MANAGER_VENDOR_SOUND_CLICK)
        );
    }

    public function buildSoundChimeFromSoundManagerJsVendor(): string
    {
        return $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_SOUND_MANAGER_VENDOR_SOUND_CHIME)
        );
    }

    public function buildSoundOnlineBidIncomingOnAdmin(int $accountId): string
    {
        return $this->getUrlBuilder()->build(SoundUrlConfig::new()->forWeb(Constants\SettingSound::ONLINE_BID_INCOMING_ON_ADMIN, $accountId));
    }

    public function buildSoundPlaceBid(int $accountId): string
    {
        return $this->getUrlBuilder()->build(SoundUrlConfig::new()->forWeb(Constants\SettingSound::PLACE_BID, $accountId));
    }

    public function buildSoundBidAccepted(int $accountId): string
    {
        return $this->getUrlBuilder()->build(SoundUrlConfig::new()->forWeb(Constants\SettingSound::BID_ACCEPTED, $accountId));
    }

    public function buildSoundOutbid(int $accountId): string
    {
        return $this->getUrlBuilder()->build(SoundUrlConfig::new()->forWeb(Constants\SettingSound::USER_OUTBID, $accountId));
    }

    public function buildSoundSoldNotWon(int $accountId): string
    {
        return $this->getUrlBuilder()->build(SoundUrlConfig::new()->forWeb(Constants\SettingSound::LOT_SOLD_NOT_WON, $accountId));
    }

    public function buildSoundSoldWon(int $accountId): string
    {
        return $this->getUrlBuilder()->build(SoundUrlConfig::new()->forWeb(Constants\SettingSound::LOT_SOLD_WON, $accountId));
    }

    public function buildSoundPassed(int $accountId): string
    {
        return $this->getUrlBuilder()->build(SoundUrlConfig::new()->forWeb(Constants\SettingSound::LOT_PASSED, $accountId));
    }

    public function buildSoundFairWarning(int $accountId): string
    {
        return $this->getUrlBuilder()->build(SoundUrlConfig::new()->forWeb(Constants\SettingSound::FAIR_WARNING, $accountId));
    }

    public function buildSoundPlay(int $accountId): string
    {
        return $this->getUrlBuilder()->build(SoundUrlConfig::new()->forWeb(Constants\SettingSound::ENABLE_PLAY, $accountId));
    }

    public function buildSoundBid(int $accountId): string
    {
        return $this->getUrlBuilder()->build(SoundUrlConfig::new()->forWeb(Constants\SettingSound::BID, $accountId));
    }
}
