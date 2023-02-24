<?php
/**
 * SAM-9520: Important Security user privilege issue
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Privilege\Access\Admin;

use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Privilege\Access\Admin\Internal\Load\DataProviderCreateTrait;

/**
 * Class AdminPrivilegeAccessChecker
 * @package Sam\User\Privilege\Access
 */
class AdminPrivilegeAccessChecker extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function canEditAdminPrivilege(int $editorUserId, int $privilege): bool
    {
        if (!in_array($privilege, Constants\AdminPrivilege::$privileges, true)) {
            throw new InvalidArgumentException("Invalid Admin privilege '$privilege'");
        }
        $admin = $this->createDataProvider()->loadAdmin($editorUserId);
        return $admin
            && ($admin->AdminPrivileges & $privilege);
    }

    public function canEditManageAuctionSubPrivilege(int $editorUserId, string $subPrivilege): bool
    {
        if (!in_array($subPrivilege, Constants\AdminPrivilege::$manageAuctionSubPrivileges, true)) {
            throw new InvalidArgumentException("Invalid ManageAuction subPrivilege '$subPrivilege'");
        }

        $admin = $this->createDataProvider()->loadAdmin($editorUserId);
        if (!$admin) {
            return false;
        }

        return $admin->hasPrivilegeForManageAuctions() && $admin->{$subPrivilege};
    }

    public function canEditManageUserSubPrivilege(int $editorUserId, string $subPrivilege): bool
    {
        if (!in_array($subPrivilege, Constants\AdminPrivilege::$manageUserSubPrivileges, true)) {
            throw new InvalidArgumentException("Invalid ManageUser subPrivilege '$subPrivilege'");
        }

        $admin = $this->createDataProvider()->loadAdmin($editorUserId);
        if (!$admin) {
            return false;
        }

        return $admin->hasPrivilegeForManageUsers() && $admin->{$subPrivilege};
    }
}
