<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Handle\Internal\Build\Internal\Load;

use Auction;
use AuctionBidder;
use Sam\Auction\Load\AuctionLoader;
use Sam\Bidder\AuctionBidder\Helper;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoader;
use Sam\Billing\Gate\Opayo\Common\Url\UrlProvider;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManager;
use Sam\User\Load\UserLoader;
use User;


class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadAuction(int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return AuctionLoader::new()->load($auctionId, $isReadOnlyDb);
    }

    public function loadUser(int $userId, bool $isReadOnlyDb = false): ?User
    {
        return UserLoader::new()->load($userId, $isReadOnlyDb);
    }

    public function isAutoAssign(int $accountId): bool
    {
        $isAutoAssign = (bool)SettingsManager::new()->get(
            Constants\Setting::REG_CONFIRM_AUTO_APPROVE,
            $accountId
        );
        return $isAutoAssign;
    }

    public function loadAuctionBidder(int $userId, int $auctionId, $isReadOnlyDb): ?AuctionBidder
    {
        return AuctionBidderLoader::new()->load($userId, $auctionId, $isReadOnlyDb);
    }

    public function isBidderApproved(AuctionBidder $auctionBidder): bool
    {
        return Helper::new()->isApproved($auctionBidder);
    }

    public function isAdminRoute(string $url): bool
    {
        return UrlProvider::new()->isAdminRoute($url);
    }

    public function isRegConfirmPageEnabled(int $accountId): bool
    {
        return (bool)SettingsManager::new()->get(Constants\Setting::REG_CONFIRM_PAGE, $accountId);
    }

    public function getRegConfirmPageUrl(int $auctionId, string $backUrl): string
    {
        return UrlProvider::new()->buildRegistrationConfirmUrl($auctionId, $backUrl);
    }

    public function isGoogleAnalyticsEnabled(int $auctionAccountId): bool
    {
        return SettingsManager::new()->get(Constants\Setting::GA_BID_TRACKING, $auctionAccountId);
    }

    public function getAuctionsLandingUrl(
        int $auctionId,
        int $accountId,
        string $auctionInfoLink,
        array $urlParams
    ): string {
        return UrlProvider::new()->buildAuctionsLandingUrl(
            $auctionId,
            $accountId,
            $auctionInfoLink,
            $urlParams
        );
    }
}
