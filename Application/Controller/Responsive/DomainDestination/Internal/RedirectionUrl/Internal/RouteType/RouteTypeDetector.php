<?php
/**
 * Route type detection logic here. It will provide route type based on controller and action and url param value.
 * Supported route type:
 * 1.AUCTION - If fetching url parameter id/catalog's value is auction id and controller name is auction/lot-details/register and it's allowed actions.
 * 2.INVOICE - if fetching url parameter id's value is invoice id and controller name is my-invoices and action name is view.
 * 3.SETTLEMENTS - if fetching url parameter id's value is settlement id and controller name is my-settlements and action name is view.
 * 4.NONE - when portal is not enabled and controller and action is unmatched.
 *
 *
 *
 * SAM-9355 : Refactor Domain Detector and Domain Redirector for unit testing
 * https://bidpath.atlassian.net/browse/SAM-9355
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\RouteType;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Web\ControllerAction\ControllerActionCollection;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class RouteTypeDetector
 * @package Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\RouteType
 */
class RouteTypeDetector extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use OptionalsTrait;

    public const OP_AUCTION_ROUTES = 'auctionRoutes';
    public const OP_INVOICE_ROUTES = 'invoiceRoutes';
    public const OP_LOT_DETAILS_ROUTES = 'lotDetailsRoutes';
    public const OP_REGISTER_ROUTES = 'registerRoutes';
    public const OP_SETTLEMENT_ROUTES = 'settlementRoutes';

    public const AUCTION_ROUTES_DEF = [
        Constants\ResponsiveRoute::C_AUCTIONS => [
            Constants\ResponsiveRoute::AA_CATALOG => Constants\ResponsiveRoute::AA_CATALOG,
            Constants\ResponsiveRoute::AA_INFO => Constants\ResponsiveRoute::AA_INFO,
            Constants\ResponsiveRoute::AA_LIVE_SALE => Constants\ResponsiveRoute::AA_LIVE_SALE,
            Constants\ResponsiveRoute::AA_FIRST_LOT => Constants\ResponsiveRoute::AA_FIRST_LOT,
            Constants\ResponsiveRoute::AA_REGISTER => Constants\ResponsiveRoute::AA_REGISTER,
        ],
    ];

    public const INVOICE_ROUTES_DEF = [
        Constants\ResponsiveRoute::C_MY_INVOICES => [
            Constants\ResponsiveRoute::AINV_VIEW => Constants\ResponsiveRoute::AINV_VIEW,
        ],
    ];

    public const SETTLEMENT_ROUTES_DEF = [
        Constants\ResponsiveRoute::C_MY_SETTLEMENTS => [
            Constants\ResponsiveRoute::ASTL_VIEW => Constants\ResponsiveRoute::ASTL_VIEW,
        ],
    ];

    public const REGISTER_ROUTES_DEF = [
        Constants\ResponsiveRoute::C_REGISTER => [
            Constants\ResponsiveRoute::AR_CONFIRM_SHIPPING => Constants\ResponsiveRoute::AR_CONFIRM_SHIPPING,
        ],
    ];

    public const LOT_DETAILS_ROUTES_DEF = [
        Constants\ResponsiveRoute::C_LOT_DETAILS => [
            Constants\ResponsiveRoute::ALD_INDEX => Constants\ResponsiveRoute::ALD_INDEX,
        ],
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Detect route type
     *
     * @param string $controller
     * @param string $action
     * @param array $optionals
     * @return RouteType
     */
    public function detect(string $controller, string $action, array $optionals = []): RouteType
    {
        $this->initOptionals($optionals);
        $none = RouteType::new()->constructNone();
        if (!$this->cfg()->get('core->portal->enabled')) {
            return $none;
        }

        $caCollection = ControllerActionCollection::new();
        if ($controller === Constants\ResponsiveRoute::C_AUCTIONS) {
            $caCollection = $caCollection->construct($this->fetchOptional(self::OP_AUCTION_ROUTES));
            return $caCollection->has($controller, $action) ? RouteType::new()->constructAuction() : $none;
        }
        if ($controller === Constants\ResponsiveRoute::C_REGISTER) {
            $caCollection = $caCollection->construct($this->fetchOptional(self::OP_REGISTER_ROUTES));
            return $caCollection->has($controller, $action) ? RouteType::new()->constructAuction() : $none;
        }
        if ($controller === Constants\ResponsiveRoute::C_LOT_DETAILS) {
            $caCollection = $caCollection->construct($this->fetchOptional(self::OP_LOT_DETAILS_ROUTES));
            return $caCollection->has($controller, $action) ? RouteType::new()->constructAuction() : $none;
        }
        if ($controller === Constants\ResponsiveRoute::C_MY_SETTLEMENTS) {
            $caCollection = $caCollection->construct($this->fetchOptional(self::OP_SETTLEMENT_ROUTES));
            return $caCollection->has($controller, $action) ? RouteType::new()->constructSettlement() : $none;
        }
        if ($controller === Constants\ResponsiveRoute::C_MY_INVOICES) {
            $caCollection = $caCollection->construct($this->fetchOptional(self::OP_INVOICE_ROUTES));
            return $caCollection->has($controller, $action) ? RouteType::new()->constructInvoice() : $none;
        }
        return $none;
    }

    /**
     * @param array $optionals
     */
    public function initOptionals(array $optionals): void
    {
        $optionals[self::OP_AUCTION_ROUTES] = $optionals[self::OP_AUCTION_ROUTES] ?? self::AUCTION_ROUTES_DEF;
        $optionals[self::OP_INVOICE_ROUTES] = $optionals[self::OP_INVOICE_ROUTES] ?? self::INVOICE_ROUTES_DEF;
        $optionals[self::OP_SETTLEMENT_ROUTES] = $optionals[self::OP_SETTLEMENT_ROUTES] ?? self::SETTLEMENT_ROUTES_DEF;
        $optionals[self::OP_LOT_DETAILS_ROUTES] = $optionals[self::OP_LOT_DETAILS_ROUTES] ?? self::LOT_DETAILS_ROUTES_DEF;
        $optionals[self::OP_REGISTER_ROUTES] = $optionals[self::OP_REGISTER_ROUTES] ?? self::REGISTER_ROUTES_DEF;
        $this->setOptionals($optionals);
    }
}
