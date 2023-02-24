<?php
/**
 * SAM-6791: Validations at controller layer for v3.5 - InventoryControllerValidator at admin site
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           02 Apr 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Inventory\Validate\Internal\Load;

use LotItem;
use Sam\Account\Validate\AccountExistenceChecker;
use Sam\Application\Access\ApplicationAccessChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoader;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;

/**
 * Class DataProvider
 * @package Sam\Application\Controller\Admin\Inventory\Validate\Internal\Load
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
     * @param int|null $lotItemId null leads to null result
     * @param bool $isReadOnlyDb
     * @return LotItem|null
     */
    public function loadLotItem(?int $lotItemId, bool $isReadOnlyDb = false): ?LotItem
    {
        return LotItemLoader::new()->load($lotItemId, $isReadOnlyDb);
    }

    /**
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isLotItemAccountAvailable(int $accountId, bool $isReadOnlyDb = false): bool
    {
        return AccountExistenceChecker::new()->existById($accountId, $isReadOnlyDb);
    }

    /**
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasPrivilegeForManageInventory(int $userId, bool $isReadOnlyDb = false): bool
    {
        return AdminPrivilegeChecker::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->initByUserId($userId)
            ->hasPrivilegeForManageInventory();
    }

    /**
     * @param int $userId
     * @param int $systemAccountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isCrossDomainAdminOnMainAccountForMultipleTenant(int $userId, int $systemAccountId, bool $isReadOnlyDb = false): bool
    {
        return ApplicationAccessChecker::new()->isCrossDomainAdminOnMainAccountForMultipleTenant($userId, $systemAccountId, $isReadOnlyDb);
    }
}
