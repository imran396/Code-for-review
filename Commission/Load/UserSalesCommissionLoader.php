<?php
/**
 * SAM-7985: Move User related data loader methods from global to Sam namespace
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Commission\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\UserSalesCommission\UserSalesCommissionReadRepositoryCreateTrait;
use UserSalesCommission;

/**
 * Class UserSalesCommissionLoader
 * @package Sam\Commission\Load
 */
class UserSalesCommissionLoader extends CustomizableClass
{
    use UserSalesCommissionReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load list of UserSalesCommission entities for user
     *
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return UserSalesCommission[]
     */
    public function loadByUserId(int $userId, bool $isReadOnlyDb = false): array
    {
        $userSalesCommissions = $this->createUserSalesCommissionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterUserId($userId)
            ->orderByAmount()
            ->loadEntities();
        return $userSalesCommissions;
    }
}
