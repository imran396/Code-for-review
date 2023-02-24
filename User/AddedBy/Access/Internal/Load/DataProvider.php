<?php
/**
 * SAM-6928: Sales staff user assignment and filtering control adjustments at the "User Edit" and the "Sales Report" pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\AddedBy\Access\Internal\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;
use Sam\User\Access\UserManagement\UserManagementAccessChecker;
use Sam\User\Access\UserManagement\UserManagementAccessCheckResult;
use Sam\User\AddedBy\Common\AccountRestriction\SalesStaffFilteringAccountDetector;
use Sam\User\AddedBy\Common\Repository\UserRepositoryForSalesStaffFactory;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;
use Sam\User\Privilege\Validate\RoleChecker;

/**
 * Class DataProvider
 * @package Sam\User\AddedBy\Access\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    protected ?UserManagementAccessCheckResult $userManagementAccessCheckResult = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isTargetUserConsignor(?int $targetUserId, bool $isReadOnlyDb = false): bool
    {
        return RoleChecker::new()->isConsignor($targetUserId, $isReadOnlyDb);
    }

    /**
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isEditorUserCrossDomainAdmin(int $userId, bool $isReadOnlyDb = false): bool
    {
        return AdminPrivilegeChecker::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->initByUserId($userId)
            ->hasPrivilegeForSuperadmin();
    }

    public function loadActualAddedByUserId(?int $targetUserId, bool $isReadOnlyDb = false): ?int
    {
        if (!$targetUserId) {
            return null;
        }

        $row = UserReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($targetUserId)
            ->select(['added_by'])
            ->loadRow();
        return Cast::toInt($row['added_by'] ?? null);
    }

    /**
     * Check, that $addedByUserId user has Sales Staff privilege and his status is Active.
     * @param int $addedByUserId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isLegitimateSalesStaff(int $addedByUserId, bool $isReadOnlyDb = false): bool
    {
        return UserRepositoryForSalesStaffFactory::new()
            ->create($isReadOnlyDb)
            ->filterId($addedByUserId)
            ->exist();
    }

    /**
     * If currently assigned "Sales staff" (u.added_by) user has direct account
     * that is among accounts available for editing by editor user in context of "Sales staff" assignment.
     *
     * This function does not verify, if assigned user is Active, or if he has Sales Staff privilege.
     * If it was revoked, it shouldn't affect decision of allowed access to this property management.
     *
     * @param int $actualAddedByUserId
     * @param int $targetUserId
     * @param int $editorUserId
     * @param int $systemAccountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isActualAddedByAmongAccessibleUsersForEditor(
        int $actualAddedByUserId,
        int $targetUserId,
        int $editorUserId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): bool {
        $filterAccountIds = SalesStaffFilteringAccountDetector::new()
            ->construct([SalesStaffFilteringAccountDetector::OP_IS_READ_ONLY_DB => $isReadOnlyDb])
            ->detect($targetUserId, $editorUserId, $systemAccountId);
        $isFound = UserReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($filterAccountIds)
            ->filterId($actualAddedByUserId)
            ->exist();
        return $isFound;
    }

    public function isActualAddedByViewable(
        int $actualAddedByUserId,
        int $editorUserId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): bool {
        $result = UserManagementAccessChecker::new()
            ->construct([UserManagementAccessChecker::OP_IS_READ_ONLY_DB => $isReadOnlyDb])
            ->check($actualAddedByUserId, $editorUserId, $systemAccountId);
        return $result->isViewable();
    }

    public function isTargetUserEditable(?int $targetUserId, ?int $editorUserId, int $systemAccountId, bool $isReadOnlyDb = false): bool
    {
        return $this->checkUserManagementAccess($targetUserId, $editorUserId, $systemAccountId, $isReadOnlyDb)
            ->isEditable();
    }

    public function isTargetUserViewable(?int $targetUserId, ?int $editorUserId, int $systemAccountId, bool $isReadOnlyDb = false): bool
    {
        return $this->checkUserManagementAccess($targetUserId, $editorUserId, $systemAccountId, $isReadOnlyDb)
            ->isViewable();
    }

    protected function checkUserManagementAccess(?int $targetUserId, ?int $editorUserId, int $systemAccountId, bool $isReadOnlyDb = false): UserManagementAccessCheckResult
    {
        if ($this->userManagementAccessCheckResult === null) {
            $this->userManagementAccessCheckResult = UserManagementAccessChecker::new()
                ->construct([UserManagementAccessChecker::OP_IS_READ_ONLY_DB => $isReadOnlyDb])
                ->check($targetUserId, $editorUserId, $systemAccountId);
        }
        return $this->userManagementAccessCheckResult;
    }
}
