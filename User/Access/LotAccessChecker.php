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

use Sam\Bidder\AuctionBidder\Validate\AuctionBidderExistenceCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;

/**
 * Class LotAccessChecker
 * @package Sam\User\Access
 */
class LotAccessChecker extends AccessCheckerBase
{
    use AdminPrivilegeCheckerAwareTrait;
    use AuctionBidderExistenceCheckerCreateTrait;
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
     * Check if access permission is met by user according to data passed
     * It is optimized method, we don't check availability of entities: user, account, auction.
     * We assume their availability was confirmed before.
     * @param string $access lot_item_cust_field.access
     * @param int|null $userId checking user id
     * @param int|null $accountId account id of lot
     * @param int|null $auctionId auction id of lot is assigned to
     * @param int|null $consignorUserId consignor id of lot
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isAccess(
        string $access,
        ?int $userId,
        ?int $accountId,
        ?int $auctionId,
        ?int $consignorUserId,
        bool $isReadOnlyDb = false
    ): bool {
        if (
            $access === Constants\Role::ADMIN
            && $this->isAdminForLot($userId, $accountId)
        ) {
            return true;
        }

        if ($access === Constants\Role::CONSIGNOR) {
            if ($this->isAdminForLot($userId, $accountId)) {
                return true;
            }

            if ($this->isConsignorOfLotByConsignorUserId($userId, $consignorUserId)) {
                return true;
            }
        }

        if ($access === Constants\Role::BIDDER) {
            if ($this->isAdminForLot($userId, $accountId)) {
                return true;
            }

            if ($this->isConsignorOfLotByConsignorUserId($userId, $consignorUserId)) {
                return true;
            }

            if ($this->isApprovedBidder($userId, $auctionId)) {
                return true;
            }
        }

        if (
            $access === Constants\Role::USER
            && $this->isAuthorizableUserId($userId, $isReadOnlyDb)
        ) {
            return true;
        }

        if ($access === Constants\Role::VISITOR) {
            return true;
        }

        return false;
    }

    /**
     * Detect available for user access roles to lot
     * @param int $lotItemId
     * @param int|null $auctionId
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return string[]
     */
    public function detectRoles(
        int $lotItemId,
        ?int $auctionId = null,
        ?int $userId = null,
        bool $isReadOnlyDb = false
    ): array {
        $lotItem = $this->getLotItemLoader()->load($lotItemId, true);
        if (!$lotItem) {
            return [];
        }

        $accessRoles = [Constants\Role::VISITOR]; //load all fields with visitor access

        $isAuthorizableUser = $this->isAuthorizableUserId($userId, $isReadOnlyDb);
        if ($isAuthorizableUser) {
            $accessRoles[] = Constants\Role::USER;

            $isAdminForLot = $this->isAdminForLot($userId, $lotItem->AccountId);

            if ($isAdminForLot) {
                // admin users have access to all
                array_push($accessRoles, Constants\Role::ADMIN, Constants\Role::CONSIGNOR, Constants\Role::BIDDER);
                return $accessRoles; // admin short cut
            }

            if ($auctionId) {
                // when a.id and li.id are provided
                if ($this->isConsignorOfLotByConsignorUserId($userId, $lotItem->ConsignorId)) {
                    array_push($accessRoles, Constants\Role::CONSIGNOR, Constants\Role::BIDDER);
                } elseif ($this->isApprovedBidder($userId, $auctionId)) {
                    $accessRoles[] = Constants\Role::BIDDER;
                }
            } else {
                // when only li.id is provided
                if ($this->isConsignorOfLotByConsignorUserId($userId, $lotItem->ConsignorId)) {
                    array_push($accessRoles, Constants\Role::CONSIGNOR, Constants\Role::BIDDER);
                }
            }
        }

        return $accessRoles;
    }

    /**
     * Check if user has admin rights for this lot (he should be Superadmin or be admin of lot's account)
     * @param int|null $userId - checking user
     * @param int|null $lotAccountId - account id of lot item
     * @return bool
     */
    protected function isAdminForLot(?int $userId, ?int $lotAccountId): bool
    {
        // Should have Admin role, be Superadmin or have the same account with lot
        $is = false;
        $isAdmin = $this->getRoleChecker()->isAdmin($userId, true);
        if ($isAdmin) {
            $user = $this->getUserLoader()->load($userId);
            if (!$user) {
                $logInfo = composeSuffix(['u' => $userId, 'acc' => $lotAccountId]);
                log_debug("Available user not found, when checking admin access to lot" . $logInfo);
                return false;
            }
            if ($this->isAuthorizableUser($user)) {
                $hasSameAccount = $user->AccountId === $lotAccountId;
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
     * Check if user is consignor of lot - should have Consignor role and be consignor of lot
     * @param int|null $userId - checking user, null for anonymous results with false
     * @param int|null $lotConsignorUserId - consignor user of lot
     * @return bool
     */
    protected function isConsignorOfLotByConsignorUserId(?int $userId, ?int $lotConsignorUserId): bool
    {
        if (!$userId) {
            return false;
        }

        $is = false;
        $isConsignor = $this->getRoleChecker()->isConsignor($userId, true);
        $user = $this->getUserLoader()->load($userId);
        if (!$user) {
            $logInfo = composeSuffix(['u' => $userId, 'lot consignor u' => $lotConsignorUserId]);
            log_debug("Available user not found, when checking consignor access to lot" . $logInfo);
            return false;
        }
        if (
            $isConsignor
            && $this->isAuthorizableUser($user)
            && $user->Id === $lotConsignorUserId
        ) {
            $is = true;
        }
        return $is;
    }

    /**
     * Temporary caching, while we won't implement general isApprovedBidder() with memory caching
     * @param int|null $userId
     * @param int|null $auctionId
     * @return bool
     */
    protected function isApprovedBidder(?int $userId, ?int $auctionId): bool
    {
        if (!$userId || !$auctionId) {
            return false;
        }
        $key = "{$userId}_{$auctionId}";
        if (!isset($this->isApprovedBidders[$key])) {
            $this->isApprovedBidders[$key] = $this->createAuctionBidderExistenceChecker()->existApprovedBidder($userId, $auctionId);
        }
        return $this->isApprovedBidders[$key];
    }
}
