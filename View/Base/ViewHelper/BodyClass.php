<?php
/**
 * Render body classes
 *
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 26, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base\ViewHelper;

use Sam\Core\Service\CustomizableClass;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\User\Watchlist\WatchlistManagerAwareTrait;

/**
 * Class BodyClass
 * @package Sam\View\Base\ViewHelper
 */
class BodyClass extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use EditorUserAwareTrait;
    use ParamFetcherForRouteAwareTrait;
    use WatchlistManagerAwareTrait;

    private const CLASS_AUCTION = 'auction';
    private const CLASS_AUCTION_HYBRID_SALE = '%s-hybrid-sale';
    private const CLASS_AUCTION_LIVE_SALE = '%s-live-sale';
    private const CLASS_AUCTION_TIMED_SALE = '%s-timed-sale';
    private const CLASS_LISTING_ONLY = 'listing-only';
    private const CLASS_WATCHLIST = 'in-watchlist';

    /**
     * @var string[]
     */
    protected array $adminAuctionActions = [
        Constants\AdminRoute::AMA_BID_INCREMENTS,
        Constants\AdminRoute::AMA_BIDDERS,
        Constants\AdminRoute::AMA_BUYERS_PREMIUM,
        Constants\AdminRoute::AMA_EDIT,
        Constants\AdminRoute::AMA_LOTS,
        Constants\AdminRoute::AMA_PERMISSIONS,
        Constants\AdminRoute::AMA_PROJECTOR,
        Constants\AdminRoute::AMA_RUN,
        Constants\AdminRoute::AMA_SHOW_IMPORT,
        Constants\AdminRoute::AMA_SMS,
    ];

    /** @var string[] */
    protected array $auctionTypeToCssMap = [
        Constants\Auction::HYBRID => self::CLASS_AUCTION_HYBRID_SALE,
        Constants\Auction::LIVE => self::CLASS_AUCTION_LIVE_SALE,
        Constants\Auction::TIMED => self::CLASS_AUCTION_TIMED_SALE,
    ];

    /** @var string[] */
    protected array $frontendAuctionControllers = [
        Constants\ResponsiveRoute::C_AUCTIONS,
        Constants\ResponsiveRoute::C_LOT_DETAILS,
        Constants\ResponsiveRoute::C_REGISTER,
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
     * @param bool $isAdminUi Module
     * @return string
     */
    public function render(bool $isAdminUi = false): string
    {
        $paramFetcherForRoute = $this->getParamFetcherForRoute();
        $controller = $paramFetcherForRoute->getControllerName();
        $action = $paramFetcherForRoute->getActionName();
        $auctionId = $paramFetcherForRoute->getIntPositiveOrZero(
            $controller === Constants\ResponsiveRoute::C_LOT_DETAILS
                ? Constants\UrlParam::R_CATALOG
                : Constants\UrlParam::R_ID
        );
        $lotItemId = $paramFetcherForRoute->getIntPositive(Constants\UrlParam::R_LOT);
        $renderData = array_filter(
            [
                $controller,
                $controller . '-' . $action,
                $isAdminUi ? $paramFetcherForRoute->getString(Constants\UrlParam::R_SUBTAB) : null,
                $isAdminUi ? $this->getAdminAuctionSaleClass($controller, $action, $auctionId, $lotItemId) : null,
                !$isAdminUi ? $this->getAuctionClass($controller, $auctionId) : null,
                !$isAdminUi ? $this->getAuctionTypeClass($controller, $auctionId) : null,
                !$isAdminUi ? $this->getWatchlistClass($auctionId, $lotItemId, $controller) : null,
                !$isAdminUi ? $this->getListingOnlyClass($auctionId, $lotItemId) : null,
            ]
        );
        return implode(' ', $renderData);
    }

    /**
     * @param string $controller
     * @param string $action
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @return null|string
     */
    protected function getAdminAuctionSaleClass(string $controller, string $action, ?int $auctionId, ?int $lotItemId): ?string
    {
        // Auctions lots overview only and not auction lot item detail
        if (
            $lotItemId
            || !$auctionId
            || $controller !== Constants\AdminRoute::C_MANAGE_AUCTIONS
            || !in_array($action, $this->adminAuctionActions, true)
        ) {
            return null;
        }
        $auction = $this->getAuctionLoader()->load($auctionId);
        return $auction ? sprintf($this->auctionTypeToCssMap[$auction->AuctionType], $controller) : null;
    }

    /**
     * @param string $controller
     * @param int|null $auctionId
     * @return string|null
     */
    protected function getAuctionClass(string $controller, ?int $auctionId): ?string
    {
        return (in_array($controller, $this->frontendAuctionControllers, true) && $auctionId)
            ? self::CLASS_AUCTION . $auctionId
            : null;
    }

    /**
     * @param string $controller
     * @param int|null $auctionId
     * @return string|null
     */
    protected function getAuctionTypeClass(string $controller, ?int $auctionId): ?string
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        return ($auction && in_array($controller, $this->frontendAuctionControllers, true))
            ? sprintf($this->auctionTypeToCssMap[$auction->AuctionType], 'type')
            : null;
    }

    /**
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @return string|null
     */
    protected function getListingOnlyClass(?int $auctionId, ?int $lotItemId): ?string
    {
        if (!$auctionId) {
            return null;
        }
        $entity = $lotItemId
            ? $this->getAuctionLotLoader()->load($lotItemId, $auctionId)
            : $this->getAuctionLoader()->load($auctionId);
        return ($entity && $entity->ListingOnly) ? self::CLASS_LISTING_ONLY : null;
    }

    /**
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @param string $controller
     * @return string|null
     */
    protected function getWatchlistClass(?int $auctionId, ?int $lotItemId, string $controller): ?string
    {
        if ($controller !== 'lot-details') {
            return null;
        }
        return ($auctionId
            && $lotItemId
            && $this->getEditorUserId()
            && $this->getWatchlistManager()->exist($this->getEditorUserId(), $lotItemId, $auctionId))
            ? self::CLASS_WATCHLIST
            : null;
    }
}
