<?php
/**
 * SAM-6928: Sales staff user assignment and filtering control adjustments at the "User Edit" and the "Sales Report" pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\AddedBy\Common\AccountRestriction\Internal\Load;

use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;
use Sam\User\Privilege\Validate\AdminPrivilegeChecker;

/**
 * Class DataProvider
 * @package Sam\User\AddedBy\Internal\Detect\Internal\Load
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

    /**
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function hasPrivilegeForSuperadmin(int $userId, bool $isReadOnlyDb = false): bool
    {
        return AdminPrivilegeChecker::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->initByUserId($userId)
            ->hasPrivilegeForSuperadmin();
    }

    /**
     * Return user's direct and collateral account ids
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadAllAccountIdsOfUser(int $userId, bool $isReadOnlyDb = false): array
    {
        $accountIds = [];
        $select = [
            'u.account_id AS direct_account_id',
            'ua.account_id AS collateral_account_id',
        ];
        $rows = UserReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($userId)
            ->joinUserAccount()
            ->select($select)
            ->loadRows();
        if ($rows) {
            $accountIds = ArrayCast::arrayColumnInt($rows, 'collateral_account_id');
            $accountIds[] = (int)$rows[0]['direct_account_id'];
            $accountIds = array_filter(array_unique($accountIds));
        }
        return $accountIds;
    }
}
