<?php
/**
 * SAM-6794: Validations at controller layer for v3.5 - SettlementControllerValidator at admin site
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           01 Apr 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Settlement\Validate\Internal\Load;

use Sam\Account\Validate\AccountExistenceChecker;
use Sam\Settlement\Load\SettlementLoader;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;
use Settlement;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\Application\Controller\Admin\Settlement\Validate\Internal\Load
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
     * @param int|null $settlementId null leads to null result
     * @param bool $isReadOnlyDb
     * @return Settlement|null
     */
    public function loadSettlement(?int $settlementId, bool $isReadOnlyDb = false): ?Settlement
    {
        return SettlementLoader::new()->load($settlementId, $isReadOnlyDb);
    }

    /**
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isSettlementAccountAvailable(int $accountId, bool $isReadOnlyDb = false): bool
    {
        return AccountExistenceChecker::new()->existById($accountId, $isReadOnlyDb);
    }

    /**
     * @param int|null $userId null leads to false result
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasPrivilegeForManageSettlements(?int $userId, bool $isReadOnlyDb = false): bool
    {
        return AdminPrivilegeChecker::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->initByUserId($userId)
            ->hasPrivilegeForManageSettlements();
    }
}
