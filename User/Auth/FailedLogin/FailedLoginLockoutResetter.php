<?php
/**
 * SAM-4393: Increased Password security - Failed attempt lockout https://bidpath.atlassian.net/projects/SAM/issues/SAM-4393
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           05 Sep, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth\FailedLogin;

use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\UserAuthentication\UserAuthenticationReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\UserAuthentication\UserAuthenticationWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use UserAuthentication;

/**
 * Class FailedLoginLockoutResetter
 */
class FailedLoginLockoutResetter extends CustomizableClass
{
    use DbConnectionTrait;
    use UserAuthenticationReadRepositoryCreateTrait;
    use UserAuthenticationWriteRepositoryAwareTrait;
    use UserLoaderAwareTrait;

    protected bool $isEcho = false;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableEcho(bool $enable): static
    {
        $this->isEcho = $enable;
        return $this;
    }

    /**
     * Drop login lockout and failed login attempts for authenticated user
     * @param UserAuthentication $userAuthentication
     * @param int $editorUserId
     * @return static
     */
    public function resetUser(UserAuthentication $userAuthentication, int $editorUserId): static
    {
        $userAuthentication->LoginLockout = null;
        $userAuthentication->FailedLoginAttempt = 0;
        $this->getUserAuthenticationWriteRepository()->saveWithModifier($userAuthentication, $editorUserId);
        return $this;
    }

    /**
     * If user authentication was found then reset locked out user
     * @param int $userId
     * @param int $editorUserId
     */
    public function reset(int $userId, int $editorUserId): void
    {
        $userAuthentication = $this->getUserLoader()->loadUserAuthentication($userId);
        if (!$userAuthentication) {
            log_error("Available UserAuthentication not found, when resetting failed login lockout" . composeSuffix(['uid' => $userId]));
            return;
        }

        $userAuthentication->LoginLockout = null;
        $userAuthentication->FailedLoginAttempt = 0;
        $this->getUserAuthenticationWriteRepository()->saveWithModifier($userAuthentication, $editorUserId);
    }

    /**
     * Reset all locked out users
     * @param int $editorUserId
     * @return static
     */
    public function resetAll(int $editorUserId): static
    {
        $repo = $this->createUserAuthenticationReadRepository()
            ->enableReadOnlyDb(true)
            ->skipFailedLoginAttempt(0)
            ->skipLoginLockout(null)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->setChunkSize(200);
        if ($this->isEcho) {
            $total = $repo->count();
            echo "There are {$total} locked out users \n";
        }
        $lockedOutUsersCount = 0;
        while ($userAuthentications = $repo->loadEntities()) {
            foreach ($userAuthentications as $userAuthentication) {
                $this->resetUser($userAuthentication, $editorUserId);
                $lockedOutUsersCount++;
            }
            if ($this->isEcho) {
                echo "Login lockouts for {$lockedOutUsersCount} users were reset successfully \n";
            }
        }
        return $this;
    }

    /**
     * quick and dirty reset for all locked out users
     * @param int $editorUserId
     */
    public function resetAllAvoidObservers(int $editorUserId): void
    {
        $n = "\n";
        $query = 'UPDATE `user_authentication` ua' . $n .
            "SET ua.`failed_login_attempt` = 0," . $n .
            "ua.`login_lockout` = NULL," . $n .
            "ua.`modified_by` = " . $this->escape($editorUserId) . $n .
            "WHERE ua.`failed_login_attempt` > 0" . $n .
            "OR ua.login_lockout IS NOT NULL";
        try {
            // $db = $this->getDb();
            $this->nonQuery($query);
        } catch (\QMySqliDatabaseException $e) {
            log_error($e->getCode() . ' - ' . $e->getMessage());
            throw new RuntimeException($e->getMessage(), $e->getCode());
        }
    }
}
