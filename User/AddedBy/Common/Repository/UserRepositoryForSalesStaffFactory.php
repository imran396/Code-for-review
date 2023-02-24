<?php
/**
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

namespace Sam\User\AddedBy\Common\Repository;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;

/**
 * Class UserRepositoryForSalesStaff
 * @package Sam\User\AddedBy\Common\Repository
 */
class UserRepositoryForSalesStaffFactory extends CustomizableClass
{
    use UserReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return UserReadRepository
     */
    public function create(bool $isReadOnlyDb = false): UserReadRepository
    {
        return $this->createUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterSubquerySalesStaffGreater(0)
            ->filterUserStatusId(Constants\User::US_ACTIVE);
    }
}
