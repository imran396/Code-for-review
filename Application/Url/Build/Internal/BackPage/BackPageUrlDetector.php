<?php
/**
 * Detect "back-page url" parameter that should be added to result url.
 * This parameter is expected at particular pages.
 * Current request URI is used for back-page url.
 *
 * This service analyses running route (visiting source controller)
 * and target url to which back-page url intended to be added ($urlConfig->urlType).
 * These source/target restrictions are declared by constants in service now.
 *
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\BackPage;

use Sam\Application\HttpRequest\ServerRequestReader;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRoute;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Core\Constants;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;

/**
 * Class BackPageUrlDetector
 */
class BackPageUrlDetector extends CustomizableClass
{
    use OptionalsTrait;
    use ParamFetcherForRouteAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use ServerRequestReaderAwareTrait;

    // allowed source controllers (string[])
    public const OP_ALLOWED_SOURCE_CONTROLLERS = 'allowedSourceControllers';
    // allowed target url types (string[])
    public const OP_ALLOWED_TARGET_URL_TYPES = 'allowedTargetUrlTypes';
    // running source controller name (string)
    public const OP_CONTROLLER_NAME = OptionalKeyConstants::KEY_CONTROLLER_NAME;
    // running source request uri (string)
    public const OP_REQUEST_URI = OptionalKeyConstants::KEY_REQUEST_URI;

    /**
     * Allowed url types, to which we can add back-page parameter '?url=',
     * These are TARGET url types that we want to adjust.
     * @var int[]
     */
    private const ALLOWED_TARGET_URL_TYPES = [
        Constants\Url::P_AUCTIONS_ABSENTEE_BIDS,
        Constants\Url::P_AUCTIONS_ASK_QUESTION,
        Constants\Url::P_AUCTIONS_BIDDING_HISTORY,
        Constants\Url::P_AUCTIONS_CONFIRM_BUY,
        Constants\Url::P_AUCTIONS_REGISTER,
        Constants\Url::P_AUCTIONS_TELL_FRIEND,
        Constants\Url::P_LANGUAGE,
        Constants\Url::P_LOT_DETAILS_CATALOG_LOT,
        Constants\Url::P_REGISTER_AUCTION_LOT_ITEM_CHANGES,
        Constants\Url::P_REGISTER_SPECIAL_TERMS_AND_CONDITIONS,
        Constants\Url::P_REPORT_PROBLEMS_LIVE_SALE,
    ];

    /**
     * These are SOURCE controllers from where we can initiate back-page url building.
     * In other words back-page url param can refer to this controller route only.
     * I.e. we don't allow back-page url to signup, login, logout controllers and to other technical data provider controllers.
     * These are allowed controllers by default, if this configuration is not re-defined by respective optional value of construct().
     * @var string[]
     */
    private const ALLOWED_SOURCE_CONTROLLERS = [
        Constants\ResponsiveRoute::C_ACCOUNTS,
        Constants\ResponsiveRoute::C_AUCTIONS,
        Constants\ResponsiveRoute::C_LOGIN,
        Constants\ResponsiveRoute::C_LOT_DETAILS,
        Constants\ResponsiveRoute::C_MY_ALERTS,
        Constants\ResponsiveRoute::C_MY_INVOICES,
        Constants\ResponsiveRoute::C_MY_ITEMS,
        Constants\ResponsiveRoute::C_REGISTER,
        Constants\ResponsiveRoute::C_SEARCH,
        Constants\ResponsiveRoute::C_SIGNUP,
    ];

    public const INFO_SHOULD_NOT_SEARCH_BACK_PAGE_PARAM = 1;
    public const INFO_IS_NOT_AMONG_ALLOWED_TARGET_URL_TYPES = 2;
    public const INFO_IS_NOT_AMONG_ALLOWED_SOURCE_CONTROLLERS = 3;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals = [
     *     self::OP_ALLOWED_SOURCE_CONTROLLERS => array,
     *     self::OP_ALLOWED_TARGET_URL_TYPES => array,
     *     self::OP_CONTROLLER_NAME => string,
     *     self::OP_REQUEST_URI => string
     * ]
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        $this->getResultStatusCollector()->initAllInfos(
            [
                self::INFO_SHOULD_NOT_SEARCH_BACK_PAGE_PARAM => 'Should not search back-page url param',
                self::INFO_IS_NOT_AMONG_ALLOWED_TARGET_URL_TYPES => 'Url-config type is not among allowed target url types',
                self::INFO_IS_NOT_AMONG_ALLOWED_SOURCE_CONTROLLERS => 'Source controller is not among allowed source controllers',
            ]
        );
        return $this;
    }

    /**
     * @param AbstractUrlConfig $urlConfig
     * @return string
     */
    public function detect(AbstractUrlConfig $urlConfig): string
    {
        if (!$urlConfig->shouldSearchBackPageParam()) {
            $this->getResultStatusCollector()->addInfo(self::INFO_SHOULD_NOT_SEARCH_BACK_PAGE_PARAM);
            return '';
        }

        if (!$this->isBackPageUrlParamExpected($urlConfig)) {
            return '';
        }

        $requestUri = $this->fetchOptional(self::OP_REQUEST_URI);
        return $requestUri;
    }

    /**
     * @return array
     * @internal for unit testing
     */
    public function infoCodes(): array
    {
        return $this->getResultStatusCollector()->getInfoCodes();
    }

    /**
     * @return string
     * @internal for unit testing
     */
    public function infoMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedInfoMessage();
    }

    /**
     * Check, if we expect to see back-page url parameter in result url
     * @param AbstractUrlConfig $urlConfig
     * @return bool
     */
    protected function isBackPageUrlParamExpected(AbstractUrlConfig $urlConfig): bool
    {
        $collector = $this->getResultStatusCollector();
        $allowedUrlTypes = $this->fetchOptional(self::OP_ALLOWED_TARGET_URL_TYPES);
        if (!in_array($urlConfig->urlType(), $allowedUrlTypes, true)) {
            $collector->addInfo(self::INFO_IS_NOT_AMONG_ALLOWED_TARGET_URL_TYPES);
            return false;
        }

        $allowedControllers = $this->fetchOptional(self::OP_ALLOWED_SOURCE_CONTROLLERS);
        $controllerName = $this->fetchOptional(self::OP_CONTROLLER_NAME);
        if (!in_array($controllerName, $allowedControllers, true)) {
            $collector->addInfo(self::INFO_IS_NOT_AMONG_ALLOWED_SOURCE_CONTROLLERS);
            return false;
        }

        return true;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_ALLOWED_SOURCE_CONTROLLERS] = (array)($optionals[self::OP_ALLOWED_SOURCE_CONTROLLERS] ?? self::ALLOWED_SOURCE_CONTROLLERS);
        $optionals[self::OP_ALLOWED_TARGET_URL_TYPES] = (array)($optionals[self::OP_ALLOWED_TARGET_URL_TYPES] ?? self::ALLOWED_TARGET_URL_TYPES);
        $optionals[self::OP_CONTROLLER_NAME] = $optionals[self::OP_CONTROLLER_NAME]
            ?? static function (): string {
                return ParamFetcherForRoute::new()->construct()->getControllerName();
            };
        $optionals[self::OP_REQUEST_URI] = $optionals[self::OP_REQUEST_URI]
            ?? static function (): string {
                return ServerRequestReader::new()->requestUri();
            };
        $this->setOptionals($optionals);
    }
}
