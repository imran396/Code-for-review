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

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;

/**
 * Class CrumbBuilder
 * @package Sam\View\Responsive\ViewHelper\Breadcrumb\Build\Internal
 */
class CrumbBuilder extends CustomizableClass
{
    use TranslatorAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $serviceAccountId
     * @param int $languageId
     * @return $this
     */
    public function construct(int $serviceAccountId, int $languageId): static
    {
        $this->getTranslator()->construct($serviceAccountId, $languageId);
        return $this;
    }

    //Builder methods

    /**
     * @return Crumb
     */
    public function buildHomeCrumb(): Crumb
    {
        $url = UrlProvider::new()->buildLandingPageUrl();
        $title = $this->getTranslator()->translate('GENERAL_HOME', 'general');
        return Crumb::new()->construct(
            $title,
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_INDEX),
            $url
        );
    }

    /**
     * @param int $auctionId
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildCatalogCrumb(int $auctionId, bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildAuctionCatalogUrl($auctionId) : null;
        $title = $this->getTranslator()->translate('BIDDERCLIENT_CATALOG', 'bidderclient');
        return Crumb::new()->construct(
            $title,
            CssClassProvider::new()->get(Constants\ResponsiveRoute::AA_CATALOG),
            $url
        );
    }

    /**
     * @param int $auctionId
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildAuctionLiveSaleCrumb(int $auctionId, bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildAuctionLiveSaleUrl($auctionId) : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('AUCTIONS_LIVESALETAB', 'auctions'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::ARPO_LIVE_SALE),
            $url
        );
    }

    /**
     * @param int $auctionId
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildRegistrationCrumb(int $auctionId, bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildRegistrationUrl($auctionId) : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('GENERAL_REGISTRATION', 'general'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::AA_REGISTER),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildAuctionListCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildAuctionListUrl() : null;
        $title = $this->getTranslator()->translate('MAINMENU_AUCTIONS', 'mainmenu');
        return Crumb::new()->construct(
            $title,
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_AUCTIONS),
            $url
        );
    }

    /**
     * @param int $auctionId
     * @param int|null $urlType
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildAuctionCrumb(int $auctionId, ?int $urlType, bool $isUrl = true): Crumb
    {
        $url = null;
        if ($isUrl && $urlType) {
            $url = ($urlType === Constants\SettingAuction::AUCTION_LINK_TO_INFO)
                ? UrlProvider::new()->buildAuctionInfoUrl($auctionId)
                : UrlProvider::new()->buildAuctionCatalogUrl($auctionId);
        }
        $title = TitleMaker::new()
            ->construct($this->getTranslator())
            ->makeAuctionTitle($auctionId);
        return Crumb::new()->construct(
            $title,
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_AUCTIONS),
            $url
        );
    }

    /**
     * @param int $lotItemId
     * @param int|null $auctionId
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildLotCrumb(int $lotItemId, ?int $auctionId, bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildLotUrl($lotItemId, $auctionId) : null;
        $title = TitleMaker::new()
            ->construct($this->getTranslator())
            ->makeLotTitle($lotItemId, $auctionId);
        return Crumb::new()->construct(
            $title,
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_LOT_ITEM),
            $url
        );
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildTellAFriendCrumb(int $lotItemId, int $auctionId, bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildTellFriendUrl($lotItemId, $auctionId) : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('CATALOG_TELLFRIEND', 'catalog'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::AA_TELL_FRIEND),
            $url
        );
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildAskAQuestionCrumb(int $lotItemId, int $auctionId, bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildAskQuestionUrl($lotItemId, $auctionId) : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('CATALOG_ASKQUESTION', 'catalog'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::AA_ASK_QUESTION),
            $url
        );
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildBiddingHistoryCrumb(int $lotItemId, int $auctionId, bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildBiddingHistoryUrl($lotItemId, $auctionId) : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('MYITEMS_BIDDINGHISTORY', 'myitems'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::AA_BIDDING_HISTORY),
            $url
        );
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildAbsenteeBidsCrumb(int $lotItemId, int $auctionId, bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildAbsenteeBidsUrl($lotItemId, $auctionId) : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('ITEM_ABSENTEE_BIDS_TITLE', 'item'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::AA_ABSENTEE_BIDS),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildAccessErrorCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildAccessErrorUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('GENERAL_ACCESS_ERROR', 'general'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_ACCESS_ERROR),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildAccountCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildAccountUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('MAINMENU_ACCOUNTS', 'mainmenu'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_ACCOUNTS),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildChangePasswordCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildChangePasswordUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('GENERAL_LOGIN_CHANGEPWD', 'general'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_CHANGE_PASSWORD),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildForgotPasswordCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildForgotPasswordUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('GENERAL_FORGOT_PASSWORD', 'general'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_FORGOT_PASSWORD),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildForgotUsernameCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildForgotUsernameUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('GENERAL_FORGOT_USERNAME', 'general'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_FORGOT_USERNAME),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildLoginCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildLoginUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('MAINMENU_LOGIN', 'mainmenu'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_LOGIN),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildMyItemIndexCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildMyItemsUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('MAINMENU_MYITEMS', 'mainmenu'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_MY_ITEMS),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildItemAllCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildItemAllUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('MYITEMS_All', 'myitems'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::AIT_ALL),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildItemBiddingCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildItemBiddingUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('MYITEMS_BIDDING', 'myitems'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::AIT_BIDDING),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildItemConsignedCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildItemConsignedUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('MYITEMS_CONSIGNED', 'myitems'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::AIT_CONSIGNED),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildItemNotWonCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildItemNotWonUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('MYITEMS_NOT_WON', 'myitems'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::AIT_NOT_WON),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildItemWonCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildItemWonUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('MYITEMS_WON', 'myitems'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::AIT_WON),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildWatchListCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildWatchListUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('MYITEMS_WATCHLISTTITLE', 'myitems'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::AIT_WATCHLIST),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildAlertCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildAlertUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('MAINMENU_MYALERTS', 'mainmenu'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_MY_ALERTS),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildProfileCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildProfileUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('MAINMENU_PROFILE', 'mainmenu'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_PROFILE),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildResetPasswordCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildResetPasswordUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('GENERAL_RESET_PASSWORD', 'general'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_RESET_PASSWORD),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildSearchCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildSearchUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('GENERAL_SEARCH', 'general'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_SEARCH),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildSignUpCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildSignUpUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('USER_SIGNUP_TITLE', 'user'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_SIGNUP),
            $url
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildMyInvoiceIndexCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildInvoicesUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('MAINMENU_INVOICES', 'mainmenu'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_MY_INVOICES),
            $url
        );
    }

    /**
     * @param int $invoiceId
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildMyInvoiceViewCrumb(int $invoiceId, bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildMyInvoiceViewUrl($invoiceId) : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('MYINVOICES_VIEW', 'myinvoices'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::AINV_VIEW),
            $url
        );
    }

    /**
     * @return Crumb
     */
    public function buildStackedTaxInvoiceViewCrumb(): Crumb
    {
        return Crumb::new()->construct(
            $this->getTranslator()->translate('VIEW_BREADCRUMB', 'stacked_tax_invoices'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::AINV_VIEW),
            null
        );
    }

    /**
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildMySettlementsIndexCrumb(bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildSettlementsUrl() : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('MYSETTLEMENTS_TITLE', 'mysettlements'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::C_MY_SETTLEMENTS),
            $url
        );
    }

    /**
     * @param int $settlementId
     * @param bool $isUrl
     * @return Crumb
     */
    public function buildMySettlementsViewCrumb(int $settlementId, bool $isUrl = true): Crumb
    {
        $url = $isUrl ? UrlProvider::new()->buildMySettlementsViewUrl($settlementId) : null;
        return Crumb::new()->construct(
            $this->getTranslator()->translate('MYSETTLEMENTS_VIEW', 'mysettlements'),
            CssClassProvider::new()->get(Constants\ResponsiveRoute::ASTL_VIEW),
            $url
        );
    }
}
