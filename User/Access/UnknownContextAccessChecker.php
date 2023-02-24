<?php
/**
 *   Access checker that doesn't know current context (lot, auction). When we know only user
 * and want to get all possible access info earlier, so it could be used for optimization.
 *   We detect user access roles and separate them to definite and probable.
 * We cannot detect if user has bidder, consignor, admin role,
 * because we don't know in which lot/auction/account context we are checking access permission.
 *   When we definitely know that role absent, we skip it in result arrays.
 *
 * SAM-4389: Problems with role permission check for lot custom field
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           8/29/2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Access;

use Sam\Core\Constants;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;

/**
 * Class UnknownContextAccessChecker
 * @package Sam\User\Access
 */
class UnknownContextAccessChecker extends AccessCheckerBase
{
    use AdminPrivilegeCheckerAwareTrait;
    use RoleCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Detect user access roles, separate them to definite and probable.
     * We cannot detect if user has bidder, consignor, admin role,
     * because we don't know in which lot/auction/account context we are checking access permission.
     * We skip role, when we definitely know it is absent.
     * @param int|null $userId null for anonymous
     * @param bool $isReadOnlyDb
     * @return string[][]
     * $definiteAccessRoles - we are already sure in these roles
     * $probableAccessRoles - require additional checks in context of entity (lot, auction)
     */
    public function detectRoles(?int $userId = null, bool $isReadOnlyDb = false): array
    {
        $definiteAccessRoles = [Constants\Role::VISITOR]; // we are sure in these roles
        $probableAccessRoles = []; // necessary additional check in entity context

        $isAuthorizableUser = $this->isAuthorizableUserId($userId, $isReadOnlyDb);
        if ($isAuthorizableUser) {
            $definiteAccessRoles[] = Constants\Role::USER;

            if ($this->getRoleChecker()->isAdmin($userId, $isReadOnlyDb)) {
                if ($this->getAdminPrivilegeChecker()
                    ->enableReadOnlyDb($isReadOnlyDb)
                    ->initByUserId($userId)
                    ->hasPrivilegeForSuperadmin()
                ) {
                    array_push($definiteAccessRoles, Constants\Role::ADMIN, Constants\Role::CONSIGNOR, Constants\Role::BIDDER);
                } else {
                    array_push($probableAccessRoles, Constants\Role::ADMIN, Constants\Role::CONSIGNOR, Constants\Role::BIDDER);
                }
            }
            if ($this->getRoleChecker()->isConsignor($userId, $isReadOnlyDb)) {
                $probableAccessRoles[] = Constants\Role::CONSIGNOR;
                // He may do not have Bidder role, but if he is consignor, we should allow checking BIDDER access restriction
                $probableAccessRoles[] = Constants\Role::BIDDER;
            } elseif ($this->getRoleChecker()->isBidder($userId, $isReadOnlyDb)) {
                $probableAccessRoles[] = Constants\Role::BIDDER;
            }
        }

        $definiteAccessRoles = array_unique($definiteAccessRoles);
        $probableAccessRoles = array_unique($probableAccessRoles);
        return [$definiteAccessRoles, $probableAccessRoles];
    }
}
