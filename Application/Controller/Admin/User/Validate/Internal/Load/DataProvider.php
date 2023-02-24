<?php
/**
 * SAM-6795: Validations at controller layer for v3.5 - UserControllerValidator at admin site
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           5 Apr 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\User\Validate\Internal\Load;

use Sam\Account\Validate\AccountExistenceChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Access\UserManagement\UserManagementAccessChecker;
use Sam\User\Access\UserManagement\UserManagementAccessCheckResult;
use Sam\User\Load\UserLoader;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;
use User;

/**
 * Class DataProvider
 * @package Sam\Application\Controller\Admin\User\Validate\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $userId null leads to null
     * @param bool $isReadOnlyDb
     * @return User|null
     */
    public function loadTargetUser(?int $userId, bool $isReadOnlyDb = false): ?User
    {
        return UserLoader::new()->load($userId, $isReadOnlyDb);
    }

    /**
     * @param int|null $accountId null leads to false
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isTargetUserAccountAvailable(?int $accountId, bool $isReadOnlyDb = false): bool
    {
        return AccountExistenceChecker::new()->existById($accountId, $isReadOnlyDb);
    }

    /**
     * @param int|null $editorUserId null leads to false result
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasPrivilegeForManageUsers(?int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        return AdminPrivilegeChecker::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->initByUserId($editorUserId)
            ->hasPrivilegeForManageUsers();
    }

    /**
     * Check access of editor user to manage target user and return result object
     * @param int $targetUserId
     * @param int $editorUserId
     * @param int $systemAccountId
     * @param bool $isReadOnlyDb
     * @return UserManagementAccessCheckResult
     */
    public function checkUserManagementAccess(int $targetUserId, int $editorUserId, int $systemAccountId, bool $isReadOnlyDb = false): UserManagementAccessCheckResult
    {
        $accessResult = UserManagementAccessChecker::new()
            ->construct([UserManagementAccessChecker::OP_IS_READ_ONLY_DB => $isReadOnlyDb])
            ->check($targetUserId, $editorUserId, $systemAccountId);
        return $accessResult;
    }
}
