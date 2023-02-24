<?php
/**
 * SAM-4393: Increased Password security - Failed attempt lockout https://bidpath.atlassian.net/projects/SAM/issues/SAM-4393
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           29 Aug, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth\FailedLogin;

use DateInterval;
use DateTime;
use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserAuthentication\UserAuthenticationWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use UserAuthentication;

/**
 * Class FailedLoginLockoutManager
 */
class FailedLoginLockoutManager extends CustomizableClass
{
    use CurrentDateTrait;
    use SettingsManagerAwareTrait;
    use UserAuthenticationWriteRepositoryAwareTrait;
    use UserLoaderAwareTrait;

    protected int $userId;
    protected ?UserAuthentication $userAuthentication = null;
    protected ?FailedLoginLockoutResetter $failedLoginLockoutResetter = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $userId
     * @return static
     */
    public function construct(int $userId): static
    {
        $this->setUserId($userId);
        return $this;
    }

    /**
     * Lock out, if user is un-locked
     * @return static
     */
    public function lockout(): static
    {
        if (!$this->isLocked()) {
            $this->lockoutUser();
        }
        return $this;
    }

    /**
     * Lock out user's account, because of failed login attempt
     * @return static
     */
    public function lockoutUser(): static
    {
        $userAuthentication = $this->getUserAuthentication();
        $userAuthentication->FailedLoginAttempt++;
        $userAuthentication->LoginLockout = $this->calculateNextLockout($userAuthentication->FailedLoginAttempt);
        $this->getUserAuthenticationWriteRepository()->saveWithSystemModifier($userAuthentication);
        return $this;
    }

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        $lockoutDate = $this->getLockoutDate();
        $currentDateUtc = $this->getCurrentDateUtc();
        return $lockoutDate
            && $lockoutDate > $currentDateUtc;
    }

    /**
     * @return DateTime|null
     */
    public function getLockoutDate(): ?DateTime
    {
        return $this->getUserAuthentication()->LoginLockout;
    }

    /**
     * Drop lockout date
     * @param int $editorUserId
     * @return static
     */
    public function reset(int $editorUserId): static
    {
        $failedLoginLockoutResetter = $this->getFailedLoginLockoutResetter();
        $failedLoginLockoutResetter->reset($this->getUserId(), $editorUserId);
        return $this;
    }

    /**
     * Drop lockout date for all users
     * @param int $editorUserId
     * @return static
     * @internal
     */
    public function resetAll(int $editorUserId): static
    {
        $failedLoginLockoutResetter = $this->getFailedLoginLockoutResetter();
        $failedLoginLockoutResetter->resetAll($editorUserId);
        return $this;
    }

    /**
     * Find next lockout date according to formula and settings
     * @param int $attemptCount
     * @return DateTime
     */
    private function calculateNextLockout(int $attemptCount): DateTime
    {
        $lockoutTimeout = $this->getSettingsManager()->getForMain(Constants\Setting::FAILED_LOGIN_ATTEMPT_LOCKOUT_TIMEOUT);
        $timeIncrement = $this->getSettingsManager()->getForMain(Constants\Setting::FAILED_LOGIN_ATTEMPT_TIME_INCREMENT);
        $secondsToAdd = $lockoutTimeout + ($timeIncrement * $attemptCount);
        $nextLockout = $this->getCurrentDateUtc();
        $nextLockout->add(new DateInterval('PT' . $secondsToAdd . 'S'));
        return $nextLockout;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        if (!$this->userId) {
            throw new InvalidArgumentException('User Id is unknown');
        }
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return static
     */
    public function setUserId(int $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return UserAuthentication
     */
    public function getUserAuthentication(): UserAuthentication
    {
        if ($this->userAuthentication === null) {
            $this->userAuthentication = $this->getUserLoader()->loadUserAuthenticationOrCreate($this->getUserId());
        }
        return $this->userAuthentication;
    }

    /**
     * @param UserAuthentication $userAuthentication
     * @return static
     * @internal
     */
    public function setUserAuthentication(UserAuthentication $userAuthentication): static
    {
        $this->userAuthentication = $userAuthentication;
        return $this;
    }

    /**
     * @return FailedLoginLockoutResetter
     */
    public function getFailedLoginLockoutResetter(): FailedLoginLockoutResetter
    {
        if ($this->failedLoginLockoutResetter === null) {
            $this->failedLoginLockoutResetter = FailedLoginLockoutResetter::new();
        }
        return $this->failedLoginLockoutResetter;
    }

    /**
     * @param FailedLoginLockoutResetter $failedLoginLockoutResetter
     * @return static
     * @internal
     */
    public function setFailedLoginLockoutResetter(FailedLoginLockoutResetter $failedLoginLockoutResetter): static
    {
        $this->failedLoginLockoutResetter = $failedLoginLockoutResetter;
        return $this;
    }
}
