<?php
/**
 * SAM-6767: Responsive Main Menu rendering module adjustments for v3.5
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Jul 27, 2021
 * @copyright     Copyright 2021 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\PublicMainMenu\Render\Internal\MenuItem;

use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionListUrlConfig;
use Sam\Application\Url\Build\Config\Auth\ResponsiveLoginUrlConfig;
use Sam\Application\Url\Build\Config\Auth\SignupUrlConfig;
use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Core\Url\RouteParser;
use Sam\Core\Url\UrlParser;
use Sam\PublicMainMenu\Render\Internal\MenuItem\Internal\Load\DataProviderCreateTrait;
use Sam\PublicMainMenu\Render\MainMenuItem;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;

/**
 * Class MenuItemsBuilder
 *
 * System parameters for service account:
 * SHOW_MEMBER_MENU_ITEMS
 *
 * System parameters for main account:
 * MAX_STORED_SEARCHES
 * STAY_ON_ACCOUNT_DOMAIN
 */
class MenuItemsBuilder extends CustomizableClass
{
    use BackUrlParserAwareTrait;
    use DataProviderCreateTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;

    // Optional values for service fine-tune
    public const OP_DENIED_BACK_PAGE_PARAM_CONTROLLERS = 'deniedBackPageParamControllers'; // string[]

    /**
     * Controllers, which shouldn't be ever used for back link url
     * @var string[]
     */
    protected const DENIED_BACK_PAGE_PARAM_CONTROLLERS = [
        Constants\ResponsiveRoute::C_FORGOT_PASSWORD,
        Constants\ResponsiveRoute::C_FORGOT_USERNAME,
        Constants\ResponsiveRoute::C_LOGIN,
        Constants\ResponsiveRoute::C_SIGNUP,
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Creates Links for Auth User for responsive design
     * @param int $systemAccountId
     * @param int|null $editorUserId
     * @param string $controllerName
     * @param array $optionals
     * @return MainMenuItem[]
     */
    public function build(
        int $systemAccountId,
        ?int $editorUserId,
        string $controllerName,
        array $optionals = []
    ): array {
        $deniedControllers = (array)($optionals[self::OP_DENIED_BACK_PAGE_PARAM_CONTROLLERS] ?? self::DENIED_BACK_PAGE_PARAM_CONTROLLERS);
        $isAuthorized = (bool)$editorUserId;
        $isShowMemberMenuItems = (bool)$this->getSettingsManager()->get(Constants\Setting::SHOW_MEMBER_MENU_ITEMS, $systemAccountId);
        $maxStoredSearches = (int)$this->getSettingsManager()->getForMain(Constants\Setting::MAX_STORED_SEARCHES);
        $dataProvider = $this->createDataProvider();
        $isConsignor = $dataProvider->isConsignor($editorUserId, true);
        $tr = $this->getTranslator();
        $menuItems = [];

        $isAccountPage = $dataProvider->isResponsiveAccountPageAvailable($systemAccountId, $editorUserId, true);
        if ($isAccountPage) {
            $menuItems[] = MainMenuItem::new()->construct(
                MainMenuItemConstants::KEY_ACCOUNTS,
                $tr->translate('MAINMENU_ACCOUNTS', 'mainmenu'),
                $this->createFullUrl(ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_ACCOUNTS), $systemAccountId, $deniedControllers),
                $controllerName === Constants\ResponsiveRoute::C_ACCOUNTS
            );
        }

        $menuItems[] = MainMenuItem::new()->construct(
            MainMenuItemConstants::KEY_AUCTIONS,
            $tr->translate('MAINMENU_AUCTIONS', 'mainmenu'),
            $this->createFullUrl(ResponsiveAuctionListUrlConfig::new()->forWeb(), $systemAccountId, $deniedControllers),
            in_array($controllerName, [Constants\ResponsiveRoute::C_AUCTIONS, Constants\ResponsiveRoute::C_LOT_DETAILS], true)
        );

        if (
            $isShowMemberMenuItems
            || $isAuthorized
        ) {
            $menuItems[] = MainMenuItem::new()->construct(
                MainMenuItemConstants::KEY_MY_ITEMS,
                $tr->translate('MAINMENU_MYITEMS', 'mainmenu'),
                $this->createFullUrl(ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_ITEMS), $systemAccountId, $deniedControllers),
                $controllerName === Constants\ResponsiveRoute::C_MY_ITEMS
            );
        }

        if ((
                $isShowMemberMenuItems
                || $isAuthorized
            )
            && $maxStoredSearches
        ) {
            $menuItems[] = MainMenuItem::new()->construct(
                MainMenuItemConstants::KEY_MY_ALERTS,
                $tr->translate('MAINMENU_MYALERTS', 'mainmenu'),
                $this->createFullUrl(ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_ALERTS), $systemAccountId, $deniedControllers),
                $controllerName === Constants\ResponsiveRoute::C_MY_ALERTS
            );
        }

        if ($isAuthorized) {
            $menuItems[] = MainMenuItem::new()->construct(
                MainMenuItemConstants::KEY_INVOICES,
                $tr->translate('MAINMENU_INVOICES', 'mainmenu'),
                $this->createFullUrl(ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_INVOICES_LIST), $systemAccountId, $deniedControllers),
                $controllerName === Constants\ResponsiveRoute::C_MY_INVOICES
            );
        }

        if (
            $isAuthorized
            && $isConsignor
        ) {
            $menuItems[] = MainMenuItem::new()->construct(
                MainMenuItemConstants::KEY_SETTLEMENTS,
                $tr->translate('MAINMENU_SETTLEMENTS', 'mainmenu'),
                $this->createFullUrl(ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_SETTLEMENTS), $systemAccountId, $deniedControllers),
                $controllerName === Constants\ResponsiveRoute::C_MY_SETTLEMENTS
            );
        }

        if ($isAuthorized) {
            $menuItems[] = MainMenuItem::new()->construct(
                MainMenuItemConstants::KEY_PROFILE,
                $tr->translate('MAINMENU_PROFILE', 'mainmenu'),
                $this->createFullUrl(ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_PROFILE), $systemAccountId, $deniedControllers),
                $controllerName === Constants\ResponsiveRoute::C_PROFILE
            );
        }

        if (!$isAuthorized) {
            $menuItems[] = MainMenuItem::new()->construct(
                MainMenuItemConstants::KEY_SIGNUP,
                $tr->translate('MAINMENU_SIGNUP', 'mainmenu'),
                $this->createFullUrl(SignupUrlConfig::new()->forWeb(), $systemAccountId, $deniedControllers),
                $controllerName === Constants\ResponsiveRoute::C_SIGNUP
            );
        }

        if (!$isAuthorized) {
            $menuItems[] = MainMenuItem::new()->construct(
                MainMenuItemConstants::KEY_LOGIN,
                $tr->translate('MAINMENU_LOGIN', 'mainmenu'),
                $this->createFullUrl(ResponsiveLoginUrlConfig::new()->forWeb(), $systemAccountId, $deniedControllers),
                $controllerName === Constants\ResponsiveRoute::C_LOGIN
            );
        }

        if ($isAuthorized) {
            $menuItems[] = MainMenuItem::new()->construct(
                MainMenuItemConstants::KEY_LOGOUT,
                $tr->translate('MAINMENU_LOGOUT', 'mainmenu'),
                $this->createFullUrl(ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_LOGOUT), $systemAccountId, $deniedControllers),
                $controllerName === Constants\ResponsiveRoute::C_LOGOUT
            );
        }

        return $menuItems;
    }

    /**
     * @param AbstractUrlConfig $urlConfig
     * @param int $serviceAccountId
     * @param string[] $deniedControllers
     * @return string
     */
    protected function createFullUrl(AbstractUrlConfig $urlConfig, int $serviceAccountId, array $deniedControllers): string
    {
        /**
         * Replace "Auctions" point link to url defined in system settings.
         * This replacement is required only for main menu link.
         * SAM-3522, SAM-6632, SAM-7628
         */
        if ($urlConfig instanceof ResponsiveAuctionListUrlConfig) {
            $auctionListUrl = $this->createDataProvider()->detectMainMenuAuctionUrl($serviceAccountId);
            if ($auctionListUrl) {
                return $auctionListUrl;
            }
        }

        /**
         * Special handling for "Sign Up" page link based on "Stay on account domain" system parameter (main account only).
         * When this option is disabled and you go to portal account public responsive site,
         * then Sign Up page link should follow to main account domain (SAM-2639).
         */
        if ($urlConfig instanceof SignupUrlConfig) {
            $isStayOnAccountDomain = (bool)$this->getSettingsManager()->getForMain(Constants\Setting::STAY_ON_ACCOUNT_DOMAIN);
            if (!$isStayOnAccountDomain) {
                $urlConfig = $urlConfig->withForceMainDomain();
            }
        }

        $url = $this->getUrlBuilder()->build($urlConfig);

        /**
         * We want to add back-page parameter only for Signup and Login pages
         */
        if (
            $urlConfig instanceof ResponsiveLoginUrlConfig
            || $urlConfig instanceof SignupUrlConfig
        ) {
            $url = $this->addBackPageParam($url, $deniedControllers);
        }

        return $url;
    }

    /**
     * Add or don't add back-page param url according to restriction defined in this service.
     * These restrictions are based on controller of back-page param
     * (this is target controller of visiting route when there is no back-page param in query-string).
     * @param string $targetUrl
     * @param string[] $deniedControllers
     * @return string
     */
    protected function addBackPageParam(string $targetUrl, array $deniedControllers): string
    {
        $backPageParamUrl = $this->createDataProvider()->fetchBackPageParamUrl();
        $isDeniedBackPageParam = $this->isDeniedBackPageParam($backPageParamUrl, $deniedControllers);
        $withoutQueryUrl = UrlParser::new()->removeQueryString($targetUrl);
        $url = $isDeniedBackPageParam
            ? $withoutQueryUrl
            : $this->getBackUrlParser()->replace($withoutQueryUrl, $backPageParamUrl);
        return $url;
    }

    /**
     * Check if url could not be used as back-page url param
     * @param string $url - url that intended to be used as back-page param
     * @param string[] $deniedControllers
     * @return bool
     */
    protected function isDeniedBackPageParam(string $url, array $deniedControllers): bool
    {
        $routeParser = RouteParser::new()->construct();
        // IK, 2020-11. There could be used $routeParser->extractControllerNameConsideringZendDefault(),
        // but we don't need it right now, because default-index controller route absent in service settings.
        // I did it simple way, so we could easier remove zend MVC things in further refactoring.
        // But if we would need to consider /, /admin index routes, then we should call it.
        $controllerName = $routeParser->extractControllerName($url);
        $isDenied = in_array($controllerName, $deniedControllers, true);
        return $isDenied;
    }
}
