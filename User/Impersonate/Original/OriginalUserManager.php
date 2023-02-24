<?php
/**
 * Manage of originally impersonated user.
 * Check if exists, register/unregister in system, get User object.
 *
 * SAM-3559: Admin impersonate improvements
 * SAM-6576: File system key-value caching for visitor session data
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           23 Dec, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Impersonate\Original;

use Sam\Core\Service\CustomizableClass;
use Sam\User\Impersonate\Original\Internal\Model\OriginalUserIdentity;
use Sam\User\Impersonate\Original\Internal\Store\CacherAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * Class OriginalUserManager
 * @package Sam\User\Impersonate
 */
class OriginalUserManager extends CustomizableClass
{
    use CacherAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get initial original user
     * @return User|null
     */
    public function loadUser(): ?User
    {
        $user = null;
        if ($this->exist()) {
            $user = $this->getUserLoader()->load($this->getUserId());
        }
        return $user;
    }

    /**
     * Check, if original user registered in session
     * @return bool
     */
    public function exist(): bool
    {
        $has = $this->getCacher()->has();
        return $has;
    }

    /**
     * Store info about original user, who made the first impersonation
     * @param User $user
     */
    public function register(User $user): void
    {
        $originalUserIdentity = OriginalUserIdentity::new()
            ->construct($user->Id, $user->Username);
        $this->getCacher()->set($originalUserIdentity);
    }

    /**
     * Remove info who was original user.
     * Should be used, when we revert user back or when we login
     */
    public function unregister(): void
    {
        $this->getCacher()->delete();
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->getIdentity()->username ?? '';
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->getIdentity()->userId ?? null;
    }

    /**
     * @return OriginalUserIdentity|null null when record is absent in cache
     */
    protected function getIdentity(): ?OriginalUserIdentity
    {
        return $this->getCacher()->get();
    }
}
