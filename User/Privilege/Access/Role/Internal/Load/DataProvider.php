<?php
/**
 * SAM-9413: Make possible for portal admin to create bidder and consignor users linked with main account
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

namespace Sam\User\Privilege\Access\Role\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserLoader;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;
use Sam\User\Validate\UserExistenceChecker;

/**
 * Class UserRoleAccessCheckerDataProvider
 * @package Sam\User\Privilege\Access\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    protected ?AdminPrivilegeChecker $editorUserAdminPrivilegeChecker = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function existSystemUserId(?int $userId): bool
    {
        return UserExistenceChecker::new()->existSystemUserId($userId);
    }

    public function loadUserDirectAccountId(int $userId, bool $isReadOnlyDb = false): ?int
    {
        $accountId = UserLoader::new()->load($userId, $isReadOnlyDb)->AccountId ?? null;
        return $accountId;
    }

    public function isEditorUserAdmin(int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        return $this->getEditorUserAdminPrivilegeChecker($editorUserId, $isReadOnlyDb)->isAdmin();
    }

    public function isEditorUserCrossDomainAdmin(int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        return $this->getEditorUserAdminPrivilegeChecker($editorUserId, $isReadOnlyDb)->hasPrivilegeForSuperadmin();
    }

    protected function getEditorUserAdminPrivilegeChecker(int $editorUserId, bool $isReadOnlyDb = false): AdminPrivilegeChecker
    {
        if ($this->editorUserAdminPrivilegeChecker === null) {
            $this->editorUserAdminPrivilegeChecker = AdminPrivilegeChecker::new()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->initByUserId($editorUserId);
        }
        return $this->editorUserAdminPrivilegeChecker;
    }
}
