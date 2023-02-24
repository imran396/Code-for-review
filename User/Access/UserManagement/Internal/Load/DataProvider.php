<?php
/**
 * SAM-8049: Admin User Edit - restricted users should not be accessible by direct link
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Access\UserManagement\Internal\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;
use Sam\Storage\ReadRepository\Entity\UserAccount\UserAccountReadRepository;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;
use Sam\User\Validate\UserExistenceChecker;

/**
 * Class DataProvider
 * @package Sam\User\Access
 */
class DataProvider extends CustomizableClass
{
    /**
     * @var AdminPrivilegeChecker[]
     */
    protected array $adminPrivilegeCheckers = [];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function existEditorUser(int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        return UserExistenceChecker::new()->existById($editorUserId, $isReadOnlyDb);
    }

    public function existTargetUser(int $targetUserId, bool $isReadOnlyDb = false): bool
    {
        return UserExistenceChecker::new()->existById($targetUserId, $isReadOnlyDb);
    }

    public function isEditorCrossDomainAdmin(int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        return $this->getAdminPrivilegeChecker($editorUserId, $isReadOnlyDb)->hasPrivilegeForSuperadmin();
    }

    public function hasEditorPrivilegeForManageUsers(int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        return $this->getAdminPrivilegeChecker($editorUserId, $isReadOnlyDb)->hasPrivilegeForManageUsers();
    }

    /**
     * @param int $targetUserId
     * @param bool $isReadOnlyDb
     * @return int|null null when direct account not found
     */
    public function loadTargetUserDirectAccountId(int $targetUserId, bool $isReadOnlyDb = false): ?int
    {
        $row = UserReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($targetUserId)
            ->select(['account_id'])
            ->loadRow();
        return Cast::toInt($row['account_id'] ?? null);
    }

    /**
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return int|null null when direct account not found
     */
    public function loadEditorUserDirectAccountId(int $editorUserId, bool $isReadOnlyDb = false): ?int
    {
        $row = UserReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($editorUserId)
            ->select(['account_id'])
            ->loadRow();
        return Cast::toInt($row['account_id'] ?? null);
    }

    public function isAmongTargetUserCollateralAccounts(int $accountId, int $targetUserId, bool $isReadOnlyDb = false): bool
    {
        return UserAccountReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterUserId($targetUserId)
            ->filterAccountId($accountId)
            ->exist();
    }

    protected function getAdminPrivilegeChecker(int $editorUserId, bool $isReadOnlyDb = false): AdminPrivilegeChecker
    {
        if (!isset($this->adminPrivilegeCheckers[$editorUserId])) {
            $this->adminPrivilegeCheckers[$editorUserId] = AdminPrivilegeChecker::new()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->initByUserId($editorUserId);
        }
        return $this->adminPrivilegeCheckers[$editorUserId];
    }
}
