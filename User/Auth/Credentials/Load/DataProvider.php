<?php
/**
 * SAM-3566: Refactoring for user authorization logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           3/22/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\User\Auth\Credentials\Load;


use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;
use User;

/**
 * @internal
 * Class DataProvider
 * @package Sam\User\Auth\Credentials\Load
 */
class DataProvider extends CustomizableClass
{
    use UserReadRepositoryCreateTrait;

    /**
     * Get instance of DataProvider
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load active user by username (not blocked, from active account)
     * @param string $username
     * @return User|null
     * @internal
     */
    public function loadUserByUsername(string $username): ?User
    {
        $user = $this->createUserReadRepository()
            ->filterUsername($username)
            ->filterUserStatusId(Constants\User::US_ACTIVE)
            ->joinAccountFilterActive(true)
            ->skipFlag(Constants\User::FLAG_BLOCK)
            ->loadEntity();
        return $user;
    }
}
