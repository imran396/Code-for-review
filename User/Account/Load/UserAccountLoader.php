<?php
/**
 *
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

namespace Sam\User\Account\Load;

use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\UserAccount\UserAccountReadRepositoryCreateTrait;
use UserAccount;

/**
 * Class UserAccountLoader
 * @package Sam\User\Account\Load
 */
class UserAccountLoader extends EntityLoaderBase
{
    use MemoryCacheManagerAwareTrait;
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
     * Load UserAccount object
     * @param int $userId
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return UserAccount|null
     */
    public function load(int $userId, int $accountId, bool $isReadOnlyDb = false): ?UserAccount
    {
        if (!$userId || !$accountId) {
            return null;
        }

        $fn = function () use ($userId, $accountId, $isReadOnlyDb) {
            $userAccount = $this->createUserAccountReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterAccountId($accountId)
                ->filterUserId($userId)
                ->loadEntity();
            return $userAccount;
        };

        $cacheKey = sprintf(Constants\MemoryCache::USER_ACCOUNT_USER_ID_ACCOUNT_ID, $userId, $accountId);
        $userAccount = $this->getMemoryCacheManager()->load($cacheKey, $fn);
        return $userAccount;
    }
}
