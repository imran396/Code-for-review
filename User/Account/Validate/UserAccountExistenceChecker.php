<?php
/**
 * SAM-4738: UserAccount management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-27
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Account\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\UserAccount\UserAccountReadRepositoryCreateTrait;

/**
 * Class UserAccountExistenceChecker
 * @package Sam\User\Account\Validate
 */
class UserAccountExistenceChecker extends CustomizableClass
{
    use UserAccountReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if user was added on UserAccount table
     *
     * @param int $userId user.id
     * @param int $accountId account.id
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function exist(int $userId, int $accountId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->createUserAccountReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterUserId($userId)
            ->exist();
        return $isFound;
    }
}
