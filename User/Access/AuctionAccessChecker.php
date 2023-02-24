<?php
/**
 * SAM-4389: Problems with role permission check for lot custom field
 * This checking logic is actual in lot context. We can check, if user has permission for read access to lot related data (incl. lot custom fields)
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           8/23/2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Access;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderExistenceCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;

/**
 * Class AuctionAccessChecker
 * @package Sam\User\Access
 */
class AuctionAccessChecker extends AccessCheckerBase
{
    use AdminPrivilegeCheckerAwareTrait;
    use AuctionBidderExistenceCheckerCreateTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use EditorUserAwareTrait;
    use LotItemLoaderAwareTrait;
    use RoleCheckerAwareTrait;

    /**
     * Temporary caching, while we won't implement general isApprovedBidder() with memory caching
     * @var bool[]
     */
    protected array $isApprovedBidders = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if access permission is met by user according to data passed.
     * It is optimized method, we don't check availability of entities: user, account, auction.
     * We assume their availability was confirmed before.
     * We don't have CONSIGNOR restriction among Auction Permissions.
     * @param string $access lot_item_cust_field.access
     * @param int|null $userId checking user id, null for anonymous
     * @param int $accountId account id of lot
     * @param int $auctionId auction id of lot is assigned to
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isAccess(string $access, ?int $userId, int $accountId, int $auctionId, bool $isReadOnlyDb = false): bool
    {
        $isAvailable = false;
        if (
            $access === Constants\Role::ADMIN
            && $this->isAdminForAuction($userId, $accountId)
        ) {
            $isAvailable = true;
        } elseif (
            $access === Constants\Role::CONSIGNOR
            && (
                $this->isAdminForAuction($userId, $accountId)
                || $this->isConsignorInAuction($userId, $auctionId)
            )
        ) {
            $isAvailable = true;
        } elseif (
            $access === Constants\Role::BIDDER
            && (
                $this->isAdminForAuction($userId, $accountId)
                || $this->isConsignorInAuction($userId, $auctionId)
                || $this->isApprovedBidder($userId, $auctionId)
            )
        ) {
            $isAvailable = true;
        } elseif (
            $access === Constants\Role::USER
            && $this->isAuthorizableUserId($userId, $isReadOnlyDb)
        ) {
            $isAvailable = true;
        } elseif ($access === Constants\Role::VISITOR) {
            $isAvailable = true;
        }
        return $isAvailable;
    }

    /**
     * Detect available for user access roles to lot
     * @param int $auctionId
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return string[]
     */
    public function detectRoles(int $auctionId, ?int $userId = null, bool $isReadOnlyDb = false): array
    {
        $auction = $this->getAuctionLoader()->load($auctionId, true);
        if (!$auction) {
            return [];
        }

        $accessRoles = [Constants\Role::VISITOR]; //load all fields with visitor access
        if ($this->isAuthorizableUserId($userId, $isReadOnlyDb)) {
            $accessRoles[] = Constants\Role::USER;

            if ($this->isAdminForAuction($userId, $auction->AccountId)) {
                // admin users have access to all
                array_push($accessRoles, Constants\Role::ADMIN, Constants\Role::CONSIGNOR, Constants\Role::BIDDER);
                return $accessRoles; // admin short cut
            }

            if ($this->isConsignorInAuction($userId, $auctionId)) {
                array_push($accessRoles, Constants\Role::CONSIGNOR, Constants\Role::BIDDER);
            } elseif ($this->isApprovedBidder($userId, $auctionId)) {
                $accessRoles[] = Constants\Role::BIDDER;
            }
        }

        return $accessRoles;
    }

    /**
     * Check if user has admin rights for this lot (he should be Superadmin or be admin of lot's account)
     * @param int|null $userId
     * @param int $auctionAccountId
     * @return bool
     */
    protected function isAdminForAuction(?int $userId, int $auctionAccountId): bool
    {
        // Should have Admin role, be Superadmin or have the same account with lot
        $is = false;
        $isAdmin = $this->getRoleChecker()->isAdmin($userId, true);
        if ($isAdmin) {
            $user = $this->getUserLoader()->load($userId, true);
            if (
                $user
                && $this->isAuthorizableUser($user)
            ) {
                $hasSameAccount = $user->AccountId === $auctionAccountId;
                if ($hasSameAccount) {
                    $is = true;
                } else {
                    $hasPrivilegeForSuperadmin = $this->getAdminPrivilegeChecker()
                        ->initByUserId($userId)
                        ->hasPrivilegeForSuperadmin();
                    if ($hasPrivilegeForSuperadmin) {
                        $is = true;
                    }
                }
            }
        }
        return $is;
    }

    /**
     * Check if user is a consignor of auction lots
     * @param int|null $userId
     * @param int $auctionId
     * @return bool
     */
    protected function isConsignorInAuction(?int $userId, int $auctionId): bool
    {
        // Should have Consignor role and be consignor of a lot in auction
        $is = false;
        $isConsignor = $this->getRoleChecker()->isConsignor($userId, true);
        $user = $this->getUserLoader()->load($userId);
        if (
            $isConsignor
            && $this->isAuthorizableUser($user)
        ) {
            // TODO: extract to ConsignorExistenceChecker::existInAuction($userId, $auctionId)
            $is = $this->createAuctionLotItemReadRepository()
                ->enableReadOnlyDb(true)
                ->filterAuctionId($auctionId)
                ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
                ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
                ->joinLotItemFilterActive(true)
                ->joinLotItemFilterConsignorId($userId)
                ->exist();
        }
        return $is;
    }

    /**
     * Temporary caching, while we won't implement general isApprovedBidder() with memory caching
     * @param int|null $userId nul for anonymous
     * @param int $auctionId
     * @return bool
     */
    protected function isApprovedBidder(?int $userId, int $auctionId): bool
    {
        if (!$userId || !$auctionId) {
            return false;
        }
        if (!isset($this->isApprovedBidders["{$userId}_{$auctionId}"])) {
            $this->isApprovedBidders["{$userId}_{$auctionId}"] = $this->createAuctionBidderExistenceChecker()->existApprovedBidder($userId, $auctionId);
        }
        return $this->isApprovedBidders["{$userId}_{$auctionId}"];
    }
}
