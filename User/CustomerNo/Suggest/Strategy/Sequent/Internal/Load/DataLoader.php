<?php
/**
 * SAM-4666: User customer no adviser
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\CustomerNo\Suggest\Strategy\Sequent\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\User\CustomerNo\Suggest\Strategy\Sequent\Internal\Load
 * @internal
 */
class DataLoader extends CustomizableClass
{
    use UserReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function loadHighestCustomerNo(bool $isReadOnlyDb = false): int
    {
        $row = $this->prepareRepository($isReadOnlyDb)->loadRow();
        return (int)($row['num'] ?? 0);
    }


    /**
     * @param bool $isReadOnlyDb
     * @return UserReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb = false): UserReadRepository
    {
        $repository = $this->createUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->select(['MAX(customer_no) as num'])
            ->filterUserStatusId(Constants\User::US_ACTIVE);
        return $repository;
    }
}
