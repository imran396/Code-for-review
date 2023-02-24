<?php
/**
 * This service checks access rights of user for operation of lot category deleting.
 *
 * SAM-9370: Access checker for lot category delete operation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Access\Management;

use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Category\Access\Management\Internal\Load\DataProviderCreateTrait;
use Sam\Lot\Category\Access\Management\LotCategoryManagementAccessCheckingResult as Result;

/**
 * Class LotCategoryManagementAccessChecker
 * @package Sam\Lot\Category\Access\Management
 */
class LotCategoryManagementAccessChecker extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function hasAccess(?int $editorUserId, int $systemAccountId, bool $isReadOnlyDb): bool
    {
        return $this
            ->checkAccess($editorUserId, $systemAccountId, $isReadOnlyDb)
            ->hasSuccess();
    }

    /**
     * Check access rights of editor user for deletion of target lot category passed by id.
     * @param int $targetLotCategoryId
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param bool $isReadOnlyDb
     * @return Result
     */
    public function checkAccessForDelete(
        int $targetLotCategoryId,
        ?int $editorUserId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): Result {
        $isLotCategory = $this->createDataProvider()->existLotCategory($targetLotCategoryId, $isReadOnlyDb);
        if (!$isLotCategory) {
            return Result::new()->construct()
                ->addError(Result::ERR_TARGET_LOT_CATEGORY_NOT_FOUND);
        }

        return $this->checkAccess($editorUserId, $systemAccountId, $isReadOnlyDb);
    }

    /**
     * @param int|null $editorUserId
     * @param int $systemAccountId
     * @param bool $isReadOnlyDb
     * @return Result
     */
    public function checkAccess(
        ?int $editorUserId,
        int $systemAccountId,
        bool $isReadOnlyDb
    ): Result {
        $result = Result::new()->construct();

        $dataProvider = $this->createDataProvider();
        if (!$editorUserId) {
            return $result->addError(Result::ERR_DENIED_FOR_ANONYMOUS);
        }

        $editorUser = $dataProvider->loadEditorUser($editorUserId, $isReadOnlyDb);
        if (!$editorUser) {
            return $result->addError(Result::ERR_EDITOR_USER_NOT_FOUND);
        }

        $hasCrossDomainAccess = $dataProvider->isCrossDomainAdminOnMainAccountForMultipleTenantOrAdminForSingleTenant($editorUserId, $systemAccountId, $isReadOnlyDb);
        if (!$hasCrossDomainAccess) {
            return $result->addError(Result::ERR_DENIED_FOR_NOT_CROSS_DOMAIN_ADMIN_ON_MAIN_ACCOUNT);
        }

        $hasPrivilege = $dataProvider->hasPrivilegeForManageSettings($editorUserId, $isReadOnlyDb);
        if (!$hasPrivilege) {
            return $result->addError(Result::ERR_ABSENT_MANAGE_SETTINGS_PRIVILEGE);
        }

        return $result;
    }
}
