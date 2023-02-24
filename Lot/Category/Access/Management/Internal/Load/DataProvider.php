<?php
/**
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

namespace Sam\Lot\Category\Access\Management\Internal\Load;

use Sam\Application\Access\ApplicationAccessChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Category\Validate\LotCategoryExistenceChecker;
use Sam\User\Load\UserLoader;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;
use User;

/**
 * Class DataProvider
 * @package Sam\Lot\Category\Access\Management\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadEditorUser(int $editorUserId, bool $isReadOnlyDb = false): ?User
    {
        return UserLoader::new()->load($editorUserId, $isReadOnlyDb);
    }

    public function existLotCategory(?int $lotCategoryId, bool $isReadOnlyDb = false): bool
    {
        return LotCategoryExistenceChecker::new()->existById($lotCategoryId, $isReadOnlyDb);
    }

    public function hasPrivilegeForManageSettings(?int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        return AdminPrivilegeChecker::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->initByUserId($editorUserId)
            ->hasPrivilegeForManageSettings();
    }

    public function isCrossDomainAdminOnMainAccountForMultipleTenantOrAdminForSingleTenant(?int $editorUserId, int $systemAccountId, bool $isReadOnlyDb = false): bool
    {
        return ApplicationAccessChecker::new()->isCrossDomainAdminOnMainAccountForMultipleTenantOrAdminForSingleTenant(
            $editorUserId,
            $systemAccountId,
            $isReadOnlyDb
        );
    }
}
