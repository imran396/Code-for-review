<?php
/**
 * Log info after user authorization actions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           02 Feb, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\ReadRepository\Entity\UserLogin\UserLoginReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\User\UserWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserLogin\UserLoginWriteRepositoryAwareTrait;
use Sam\User\Account\Statistic\Save\UserAccountStatisticProducerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use UserLogin;

/**
 * Class Logger
 * @package Sam\User\Auth
 */
class Logger extends CustomizableClass
{
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use UserAccountStatisticProducerAwareTrait;
    use UserLoaderAwareTrait;
    use UserLoginReadRepositoryCreateTrait;
    use UserLoginWriteRepositoryAwareTrait;
    use UserWriteRepositoryAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Store authorized user's ip address, session_id and date of login in `user_login` table
     * @param int $userId
     * @param string $ip
     * @return void
     */
    public function logSuccessLogin(int $userId, string $ip): void
    {
        $user = $this->getUserLoader()->load($userId, true);
        if (!$user) {
            log_error("Available user not found, when logging success login" . composeSuffix(['u' => $userId]));
            return;
        }

        $userLogin = $this->loadByUserAndIp($userId, $ip);
        if (!$userLogin) {
            $userLogin = $this->createEntityFactory()->userLogin();
            $userLogin->UserId = $userId;
            $userLogin->IpAddress = $ip;
        }
        $userLogin->SessId = session_id();
        $userLogin->LoggedDate = $this->getCurrentDateUtc();
        $this->getUserLoginWriteRepository()->saveWithSelfModifier($userLogin);

        $user->LogDate = $this->getCurrentDateUtc();
        $this->getUserWriteRepository()->saveWithSelfModifier($user);

        $this->getUserAccountStatisticProducer()->markExpired($user->Id, $user->AccountId);
    }

    /**
     * Store user info after failed authorization, because of blocked ip
     * @param int $userId user.id
     * @param string $ip
     * @return void
     */
    public function logBlockedByIp(int $userId, string $ip): void
    {
        $userLogin = $this->loadByUserAndIp($userId, $ip);
        if (!$userLogin) {
            $userLogin = $this->createEntityFactory()->userLogin();
            $userLogin->UserId = $userId;
            $userLogin->IpAddress = $ip;
        }
        $userLogin->Blocked = true;
        $userLogin->SessId = session_id();
        $userLogin->LoggedDate = $this->getCurrentDateUtc();
        $this->getUserLoginWriteRepository()->saveWithSelfModifier($userLogin);
    }

    /**
     * Returns single user_login object for passed user and ip
     * @param int $userId user.id
     * @param string $ip
     * @return UserLogin|null
     */
    private function loadByUserAndIp(int $userId, string $ip): ?UserLogin
    {
        $userLogin = $this->createUserLoginReadRepository()
            ->filterUserId($userId)
            ->filterIpAddress($ip)
            ->loadEntity();
        return $userLogin;
    }
}
