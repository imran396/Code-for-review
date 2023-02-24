<?php
/**
 * SAM-8106: Improper validations displayed for invalid inputs
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\UserEditForm\UserSalesCommissionPanel\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\UserSalesCommission\UserSalesCommissionReadRepositoryCreateTrait;

/**
 * Class UserSalesCommissionPanelLoader
 * @package Sam\View\Admin\Form\UserEditForm\UserSalesCommissionPanel\Load
 */
class UserSalesCommissionPanelLoader extends CustomizableClass
{
    use UserSalesCommissionReadRepositoryCreateTrait;

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
     * @return array
     */
    public function loadRowsByUserId(int $userId, bool $isReadOnlyDb = false): array
    {
        $rows = $this->createUserSalesCommissionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterUserId($userId)
            ->orderByAmount()
            ->select(['id', 'amount', 'percent'])
            ->loadRows();
        return $rows;
    }
}
