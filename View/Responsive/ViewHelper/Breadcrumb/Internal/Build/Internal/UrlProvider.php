<?php
/**
 * SAM-4500: Front-end breadcrumb
 * https://bidpath.atlassian.net/browse/SAM-4500
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\ViewHelper\Breadcrumb\Internal\Build\Internal;

use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionInfoUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionListUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionRegistrationUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveCatalogUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveLiveSaleUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveAbsenteeBidsUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveAskQuestionUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveBiddingHistoryUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveTellFriendUrlConfig;
use Sam\Application\Url\Build\Config\Auth\ResetPasswordUrlConfig;
use Sam\Application\Url\Build\Config\Auth\SignupUrlConfig;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\Config\Invoice\ResponsiveInvoiceViewUrlConfig;
use Sam\Application\Url\Build\Config\Landing\ResponsiveLandingUrlConfig;
use Sam\Application\Url\Build\Config\Search\ResponsiveSearchUrlConfig;
use Sam\Application\Url\Build\Config\Settlement\ResponsiveSettlementViewUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class UrlProvider
 * @package Sam\View\Responsive\ViewHelper\Breadcrumb\Build\PathSettingsBuild\Internal
 */
class UrlProvider extends CustomizableClass
{
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    //Helper methods

    /**
     * @return string
     */
    public function buildLandingPageUrl(): string
    {
        $urlConfig = ResponsiveLandingUrlConfig::new()->forWeb();
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildAuctionListUrl(): string
    {
        $urlConfig = ResponsiveAuctionListUrlConfig::new()->forWeb();
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildInvoicesUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_INVOICES_LIST);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildMyItemsUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_ITEMS);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildAccessErrorUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_ACCESS_ERROR);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildAccountUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_ACCESS_ERROR);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildChangePasswordUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_CHANGE_PASSWORD);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildForgotPasswordUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_FORGOT_PASSWORD);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildForgotUsernameUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_FORGOT_USERNAME);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildLoginUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_LOGIN);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildItemAllUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_ITEMS_ALL);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildItemBiddingUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_ITEMS_BIDDING);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildItemConsignedUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_ITEMS_CONSIGNED);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildItemNotWonUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_ITEMS_NOT_WON);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildItemWonUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_ITEMS_WON);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildWatchListUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_ITEMS);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildAlertUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_ALERTS);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildProfileUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_PROFILE);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildResetPasswordUrl(): string
    {
        $urlConfig = ResetPasswordUrlConfig::new()->forWeb();
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildSearchUrl(): string
    {
        $urlConfig = ResponsiveSearchUrlConfig::new()->forWeb();
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildSignUpUrl(): string
    {
        $urlConfig = SignupUrlConfig::new()->forWeb();
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @param int $invoiceId
     * @return string
     */
    public function buildMyInvoiceViewUrl(int $invoiceId): string
    {
        $urlConfig = ResponsiveInvoiceViewUrlConfig::new()->forWeb($invoiceId);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @param int $settlementId
     * @return string
     */
    public function buildMySettlementsViewUrl(int $settlementId): string
    {
        $urlConfig = ResponsiveSettlementViewUrlConfig::new()->forWeb($settlementId);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @return string
     */
    public function buildSettlementsUrl(): string
    {
        $urlConfig = ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_SETTLEMENTS);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @param int $auctionId
     * @return string
     */
    public function buildAuctionInfoUrl(int $auctionId): string
    {
        $urlConfig = ResponsiveAuctionInfoUrlConfig::new()->forWeb($auctionId);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @param int $auctionId
     * @return string
     */
    public function buildAuctionCatalogUrl(int $auctionId): string
    {
        $urlConfig = ResponsiveCatalogUrlConfig::new()->forWeb($auctionId);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @param int $lotItemId
     * @param int|null $auctionId
     * @return string
     */
    public function buildLotUrl(int $lotItemId, ?int $auctionId): string
    {
        $urlConfig = ResponsiveLotDetailsUrlConfig::new()->forWeb($lotItemId, $auctionId);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @param int $auctionId
     * @return string
     */
    public function buildAuctionLiveSaleUrl(int $auctionId): string
    {
        $urlConfig = ResponsiveLiveSaleUrlConfig::new()->forWeb($auctionId);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @param int $auctionId
     * @return string
     */
    public function buildRegistrationUrl(int $auctionId): string
    {
        $urlConfig = ResponsiveAuctionRegistrationUrlConfig::new()->forWeb($auctionId);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @param int $auctionId
     * @param int $lotItemId
     * @return string
     */
    public function buildTellFriendUrl(int $lotItemId, int $auctionId): string
    {
        $urlConfig = ResponsiveTellFriendUrlConfig::new()->forWeb($lotItemId, $auctionId);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @param int $auctionId
     * @param int $lotItemId
     * @return string
     */
    public function buildAskQuestionUrl(int $lotItemId, int $auctionId): string
    {
        $urlConfig = ResponsiveAskQuestionUrlConfig::new()->forWeb($lotItemId, $auctionId);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @param int $auctionId
     * @param int $lotItemId
     * @return string
     */
    public function buildBiddingHistoryUrl(int $lotItemId, int $auctionId): string
    {
        $urlConfig = ResponsiveBiddingHistoryUrlConfig::new()->forWeb($lotItemId, $auctionId);
        return $this->getUrlBuilder()->build($urlConfig);
    }

    /**
     * @param int $auctionId
     * @param int $lotItemId
     * @return string
     */
    public function buildAbsenteeBidsUrl(int $lotItemId, int $auctionId): string
    {
        $urlConfig = ResponsiveAbsenteeBidsUrlConfig::new()->forWeb($lotItemId, $auctionId);
        return $this->getUrlBuilder()->build($urlConfig);
    }
}
