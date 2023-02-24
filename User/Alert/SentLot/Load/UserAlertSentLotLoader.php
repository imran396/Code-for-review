<?php
/**
 *
 * SAM-4744: UserSentLots entity loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-31
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Alert\SentLot\Load;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\UserSentLots\UserSentLotsReadRepositoryCreateTrait;
use UserSentLots;

/**
 * Class UserAlertSentLotLoader
 * @package Sam\User\Alert\SentLot\Load
 */
class UserAlertSentLotLoader extends EntityLoaderBase
{
    use EntityFactoryCreateTrait;
    use UserSentLotsReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load UserSentLots by userId
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return UserSentLots|null
     */
    public function loadByUserId(int $userId, bool $isReadOnlyDb = false): ?UserSentLots
    {
        $userSentLots = $this->createUserSentLotsReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterUserId($userId)
            ->loadEntity();
        return $userSentLots;
    }

    /**
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return UserSentLots
     */
    public function loadByUserIdOrCreate(int $userId, bool $isReadOnlyDb = false): UserSentLots
    {
        $userSentLots = $this->loadByUserId($userId, $isReadOnlyDb);
        if (!$userSentLots) {
            $userSentLots = $this->createEntityFactory()->userSentLots();
            $userSentLots->UserId = $userId;
        }
        return $userSentLots;
    }
}
