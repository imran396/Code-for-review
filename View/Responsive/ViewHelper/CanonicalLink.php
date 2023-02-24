<?php
/**
 *
 * SAM-4585: Refactor CanonicalLink view helper to customized class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-23
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\ViewHelper;

use Sam\Application\Url\Build\Config\Auction\AbstractResponsiveSingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveAuctionInfoUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveCatalogUrlConfig;
use Sam\Application\Url\Build\Config\Auction\ResponsiveLiveSaleUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Core\Service\CustomizableClass;
use Exception;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Url\BackPage\BackUrlPureParser;
use Sam\Core\Web\ControllerAction\ControllerActionCollection;

/**
 * Class CanonicalLink
 * @package Sam\View\Responsive\ViewHelper
 */
class CanonicalLink extends CustomizableClass
{
    use BackUrlParserAwareTrait;
    use ParamFetcherForRouteAwareTrait;
    use UrlBuilderAwareTrait;

    /** @var string[][] */
    protected const SUPPORTED_PATHS = [
        Constants\ResponsiveRoute::C_AUCTIONS => [
            Constants\ResponsiveRoute::AA_CATALOG => ResponsiveCatalogUrlConfig::class,
            Constants\ResponsiveRoute::AA_INFO => ResponsiveAuctionInfoUrlConfig::class,
            Constants\ResponsiveRoute::AA_LIVE_SALE => ResponsiveLiveSaleUrlConfig::class,
        ],
        Constants\ResponsiveRoute::C_LOT_DETAILS => [
            Constants\ResponsiveRoute::ALD_INDEX => ResponsiveLotDetailsUrlConfig::class,
        ]
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        try {
            $output = $this->GetRender();
            return $output;
        } catch (Exception $e) {
            return $e->getMessage() . nl2br($e->getTraceAsString());
        }
    }

    /**
     * @return string
     */
    protected function GetRender(): string
    {
        $output = '';
        $caCollection = ControllerActionCollection::new()->construct(self::SUPPORTED_PATHS);
        $controller = $this->controller();
        $action = $this->action();
        if ($caCollection->has($controller, $action)) {
            $paramFetcherForRoute = $this->getParamFetcherForRoute();
            $urlConfigClass = $caCollection->get($controller, $action);
            /** @var AbstractUrlConfig $urlConfig */
            $urlConfig = call_user_func([$urlConfigClass, 'new']);
            if (
                $controller === Constants\ResponsiveRoute::C_LOT_DETAILS
                && $action === Constants\ResponsiveRoute::ALD_INDEX
            ) {
                $auctionId = $paramFetcherForRoute->getIntPositiveOrZero(Constants\UrlParam::R_CATALOG); // "0" for preview of unassigned lot
                $lotItemId = $paramFetcherForRoute->getIntPositive(Constants\UrlParam::R_LOT);
                /** @var ResponsiveLotDetailsUrlConfig $urlConfig */
                $url = $this->getUrlBuilder()->build($urlConfig->forWeb($lotItemId, $auctionId));
            } else {
                $auctionId = $paramFetcherForRoute->getIntPositive(Constants\UrlParam::R_ID);
                /** @var AbstractResponsiveSingleAuctionUrlConfig $urlConfig */
                $url = $this->getUrlBuilder()->build($urlConfig->forWeb($auctionId));
            }
            $url = BackUrlPureParser::new()->remove($url);
            $output = '<link rel="canonical" href="' . $url . '"/>';
        }

        return $output;
    }

    /**
     * @return string
     */
    private function controller(): string
    {
        return $this->getParamFetcherForRoute()->getControllerName();
    }

    /**
     * @return string
     */
    private function action(): string
    {
        return $this->getParamFetcherForRoute()->getActionName();
    }

}
