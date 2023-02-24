<?php
/**
 * This validation service is used, when we want to check user id intended to be assigned as Sales Staff agent of target user.
 * It doesn't verify access rights for assignment operation by editor user. This is made by AddedByUserManagementAccessChecker.
 *
 * SAM-6928: Sales staff user assignment and filtering control adjustments at the "User Edit" and the "Sales Report" pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\AddedBy\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\User\AddedBy\Common\AccountRestriction\SalesStaffFilteringAccountDetector;
use Sam\User\AddedBy\Common\Repository\UserRepositoryForSalesStaffFactory;

/**
 * Class AddedByUserAssignmentValidator
 * @package Sam\User\AddedBy\Validate
 */
class AddedByUserAssignmentValidator extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check, if $addedByUserId allowed for assignment to $targetUserId,
     * when this operation is performed by $editorUserId, who visits domain account $systemAccountId.
     *
     * This function does not check access of editor user for this operation over target user in context of system account.
     *
     * @param int|null $addedByUserId
     * @param int $targetUserId
     * @param int $editorUserId
     * @param int $systemAccountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isUserAllowedForAssignment(
        ?int $addedByUserId,
        int $targetUserId,
        int $editorUserId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): bool {
        if (!$addedByUserId) {
            // Empty input user means drop of existing assignment
            return true;
        }

        $filterAccountIds = SalesStaffFilteringAccountDetector::new()
            ->construct([SalesStaffFilteringAccountDetector::OP_IS_READ_ONLY_DB => $isReadOnlyDb])
            ->detect($targetUserId, $editorUserId, $systemAccountId);
        $isFound = UserRepositoryForSalesStaffFactory::new()
            ->create($isReadOnlyDb)
            ->filterAccountId($filterAccountIds)
            ->filterId($addedByUserId)
            ->exist();
        return $isFound;
    }
}
