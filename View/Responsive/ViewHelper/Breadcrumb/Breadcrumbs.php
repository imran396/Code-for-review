<?php
/**
 * SAM-4500: Front-end breadcrumb
 * https://bidpath.atlassian.net/browse/SAM-4500
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\ViewHelper\Breadcrumb;

use Sam\Application\Language\Detect\ApplicationLanguageDetectorCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Constants;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\View\Responsive\ViewHelper\Breadcrumb\Internal\Build\CollectionBuilder;
use Sam\View\Responsive\ViewHelper\Breadcrumb\Internal\Render\Renderer;

/**
 * Class Breadcrumbs
 *
 * @package Sam\View\Responsive\ViewHelper\Breadcrumb
 */
class Breadcrumbs extends CustomizableClass
{
    use ApplicationLanguageDetectorCreateTrait;
    use ParamFetcherForGetAwareTrait;
    use ParamFetcherForRouteAwareTrait;
    use SystemAccountAwareTrait;

    // --Constructor-- //

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        return $this;
    }

    // --- Main  method ---//

    /**
     * @return string
     */
    public function output(): string
    {
        $paramFetcherForRoute = $this->getParamFetcherForRoute();
        $controller = $paramFetcherForRoute->getControllerName();
        $action = $paramFetcherForRoute->getActionName();
        $auctionIdParamName = $this->detectAuctionIdParamName($controller, $action);
        $auctionId = $paramFetcherForRoute->getIntPositiveOrZero($auctionIdParamName); // "0" for preview of unassigned lot
        $lotItemId = $paramFetcherForRoute->getIntPositive(Constants\UrlParam::R_LOT);
        $id = $paramFetcherForRoute->getIntPositive(Constants\UrlParam::R_ID); //Invoice  or Settlement Id
        $collection = CollectionBuilder::new()->build($controller, $action, $auctionId, $lotItemId, $id);
        return Renderer::new()->render($collection);
    }

    //helper method//

    /**
     * It gives parameter name for fetching auction id
     * @param string $controller
     * @param string $action
     * @return string
     */
    protected function detectAuctionIdParamName(string $controller, string $action): string
    {
        return ($controller === Constants\ResponsiveRoute::C_LOT_DETAILS
            && $action === Constants\ResponsiveRoute::ALD_INDEX)
            ? Constants\UrlParam::R_CATALOG
            : Constants\UrlParam::R_ID;
    }
}
