<?php

namespace Sam\User\Auth\Identity;

use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Application\UserDataStorage\UserDataStorageCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Auth\Identity\Storage\AuthIdentityStoragePoolAwareTrait;
use Sam\User\Impersonate\Original\OriginalUserManagerCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class AuthIdentityManager
 */
class AuthIdentityManager extends CustomizableClass
{
    use AuthIdentityStoragePoolAwareTrait;
    use CookieHelperCreateTrait;
    use OriginalUserManagerCreateTrait;
    use UserDataStorageCreateTrait;
    use UserLoaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check, if running process user session is authorized
     * @return bool
     */
    public function isAuthorized(): bool
    {
        $isAuthenticatedInCookie = $this->createCookieHelper()->isAuthenticated();
        if (!$isAuthenticatedInCookie) {
            return false;
        }

        $pool = $this->getAuthIdentityStoragePool();
        if (!$pool->hasIdentityStored()) {
            return false;
        }

        $dto = $pool->readIdentityOrCreate();
        return $dto->userId > 0;
    }

    /**
     * Return authenticated user id
     * @return int|null
     */
    public function getUserId(): ?int
    {
        if (!$this->isAuthorized()) {
            return null;
        }

        $dto = $this->getAuthIdentityStoragePool()->readIdentityOrCreate();
        return $dto->userId;
    }

    /**
     * Apply user to current authorized session.
     * We store user.id in session.
     * @param int $userId
     * @return static
     */
    public function applyUser(int $userId): static
    {
        $pool = $this->getAuthIdentityStoragePool();
        $dto = $pool->readIdentityOrCreate();
        $dto->userId = $userId;
        $pool->writeIdentity($dto);
        $this->createCookieHelper()->enableAuthenticated(true);
        return $this;
    }

    /**
     * Clear all user session data and identity, and cookie app storage
     */
    public function clearSessionData(): void
    {
        $this->getAuthIdentityStoragePool()->clearIdentity();
        $_SESSION = [];
        $this->createUserDataStorage()->removeAll();
    }

    /**
     * Process general logout actions
     */
    public function logout(): void
    {
        $this->clearSessionData();
        $this->createOriginalUserManager()->unregister();
        $this->createCookieHelper()->enableAuthenticated(false);
        $this->createCookieHelper()->dropPhpSessionId();
    }

    /**
     * @param bool $isRequired
     * @return static
     */
    public function requirePasswordChange(bool $isRequired): static
    {
        $pool = $this->getAuthIdentityStoragePool();
        $dto = $pool->readIdentityOrCreate();
        $dto->passwordChangeRequired = $isRequired;
        $pool->writeIdentity($dto);
        return $this;
    }

    /**
     * @return bool
     */
    public function isPasswordChangeRequired(): bool
    {
        $dto = $this->getAuthIdentityStoragePool()->readIdentityOrCreate();
        return $dto->passwordChangeRequired;
    }
}
