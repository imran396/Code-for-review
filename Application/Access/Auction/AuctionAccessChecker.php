<?php
/**
 * SAM-7764: Refactor \Auction_Access class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Access\Auction;

use Auction;
use InvalidArgumentException;
use Sam\Application\Access\Auction\Internal\ResourceTypeColumnNameProviderCreateTrait;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\Auth\ResponsiveLoginUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderCheckerAwareTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderExistenceCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;
use User;

/**
 * Class AuctionAccessChecker
 * @package Sam\Application\Access\Auction
 */
class AuctionAccessChecker extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use AuctionBidderCheckerAwareTrait;
    use AuctionBidderExistenceCheckerCreateTrait;
    use AuthIdentityManagerCreateTrait;
    use BackUrlParserAwareTrait;
    use ResourceTypeColumnNameProviderCreateTrait;
    use RoleCheckerAwareTrait;
    use ServerRequestReaderAwareTrait;
    use UrlBuilderAwareTrait;
    use UserLoaderAwareTrait;

    protected const STATUS_ACCESS_GRANTED = 'accessGranted';
    protected const STATUS_LOGIN_REQUIRED = 'loginRequired';
    protected const STATUS_PERMISSION_REQUIRED = 'permissionRequired';
    protected const STATUS_REGISTRATION_PENDING = 'registrationPending';
    protected const STATUS_REGISTRATION_REQUIRED = 'registrationRequired';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Checks access permissions of current user to resource
     *
     * @param int $resourceType
     * @param Auction $auction
     * @return bool
     */
    public function hasPermission(int $resourceType, Auction $auction): bool
    {
        $currentUser = $this->loadCurrentUser();
        $permissionStatus = $this->detectPermissionStatus($resourceType, $auction, $currentUser);
        return $permissionStatus === self::STATUS_ACCESS_GRANTED;
    }

    /**
     * Checks access permissions of current user to resource and route to corresponding url
     * If user is not logged in, he has VISITOR access
     * If permissions are ok, no redirection performed
     * If this is called in responsive, redirect to related responsive pages
     *
     * @param int $resourceType
     * @param Auction $auction
     */
    public function protectAndRedirect(int $resourceType, Auction $auction): void
    {
        $currentUser = $this->loadCurrentUser();
        $permissionStatus = $this->detectPermissionStatus($resourceType, $auction, $currentUser);
        if ($permissionStatus === self::STATUS_ACCESS_GRANTED) {
            return;
        }

        $backUrl = $this->getServerRequestReader()->currentUrl();
        $url = $this->detectRedirectUrl($permissionStatus, $auction->Id);
        $url = $this->getBackUrlParser()->replace($url, $backUrl);
        $this->createApplicationRedirector()->redirect($url);
    }

    /**
     * @param string $permissionStatus
     * @param int $auctionId
     * @return string
     */
    protected function detectRedirectUrl(string $permissionStatus, int $auctionId): string
    {
        $url = match ($permissionStatus) {
            self::STATUS_LOGIN_REQUIRED => $this->getUrlBuilder()->build(
                ResponsiveLoginUrlConfig::new()->forRedirect()
            ),
            self::STATUS_REGISTRATION_REQUIRED => $this->getUrlBuilder()->build(
                AnySingleAuctionUrlConfig::new()->forRedirect(
                    Constants\Url::P_AUCTIONS_REGISTRATION_REQUIRED,
                    $auctionId
                )
            ),
            self::STATUS_REGISTRATION_PENDING => $this->getUrlBuilder()->build(
                AnySingleAuctionUrlConfig::new()->forRedirect(
                    Constants\Url::P_AUCTIONS_REGISTRATION_PENDING,
                    $auctionId
                )
            ),
            self::STATUS_PERMISSION_REQUIRED => $this->getUrlBuilder()->build(
                AnySingleAuctionUrlConfig::new()->forRedirect(
                    Constants\Url::P_AUCTIONS_PERMISSION_REQUIRED,
                    $auctionId
                )
            ),
            default => throw new InvalidArgumentException("Undefined permission status {$permissionStatus}"),
        };
        return $url;
    }

    /**
     * Get access permission status by resource and user role
     *
     * @param int $resourceType
     * @param Auction $auction
     * @param User|null $user - Null means user is not logged in, he is VISITOR
     * @return string
     */
    protected function detectPermissionStatus(int $resourceType, Auction $auction, ?User $user): string
    {
        $restrictionPropertyName = $this->createResourceTypeColumnNameProvider()->getPropertyName($resourceType);
        $restriction = $auction->{$restrictionPropertyName};

        if ($restriction === Constants\Role::ADMIN) {
            return $this->detectPermissionStatusForAdminRoleRestriction($auction, $user);
        }

        if ($restriction === Constants\Role::BIDDER) {
            return $this->detectPermissionStatusForBidderRoleRestriction($auction, $user);
        }

        if (
            $restriction === Constants\Role::USER
            && !$user
        ) {
            return self::STATUS_LOGIN_REQUIRED;
        }

        return self::STATUS_ACCESS_GRANTED;
    }

    /**
     * @param Auction $auction
     * @param User|null $user
     * @return string
     */
    protected function detectPermissionStatusForAdminRoleRestriction(Auction $auction, ?User $user): string
    {
        if (
            $user
            && $this->createAuctionBidderExistenceChecker()->existApprovedBidder($user->Id, $auction->Id)
            && $this->getRoleChecker()->isAdmin($user->Id)
        ) {
            return self::STATUS_ACCESS_GRANTED;
        }
        return self::STATUS_PERMISSION_REQUIRED;
    }

    /**
     * @param Auction $auction
     * @param User|null $user
     * @return string
     */
    protected function detectPermissionStatusForBidderRoleRestriction(Auction $auction, ?User $user): string
    {
        if (!$user) {
            return self::STATUS_LOGIN_REQUIRED;
        }

        if (
            $this->createAuctionBidderExistenceChecker()->existApprovedBidder($user->Id, $auction->Id)
            || $this->getRoleChecker()->isAdmin($user->Id)
        ) {
            return self::STATUS_ACCESS_GRANTED;
        }

        $isAuctionRegistered = $this->getAuctionBidderChecker()->isAuctionRegistered($user->Id, $auction->Id);
        return $isAuctionRegistered
            ? self::STATUS_REGISTRATION_PENDING
            : self::STATUS_REGISTRATION_REQUIRED;
    }

    /**
     * @return User|null
     */
    protected function loadCurrentUser(): ?User
    {
        $userId = $this->createAuthIdentityManager()->getUserId();
        $currentUser = $this->getUserLoader()->load($userId, true);
        return $currentUser;
    }
}
