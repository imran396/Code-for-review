<?php
/**
 * Login manager registers authenticated user in session.
 *
 * SAM-3566: Refactoring for user authorization logic
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis, Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           29 Mar, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\User\Auth;

use InvalidArgumentException;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Auth\Credentials\Validate\CredentialsChecker;
use Sam\User\Auth\Identity\AuthIdentityManager;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;
use Sam\User\Impersonate\Original\OriginalUserManagerCreateTrait;

/**
 * Class LoginService
 */
class LoginService extends CustomizableClass
{
    use AuthIdentityManagerCreateTrait;
    use OriginalUserManagerCreateTrait;

    protected ?CredentialsChecker $credentialsChecker = null;

    /**
     * Get instance of LoginManager
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Verify and Log user in
     * @return AuthIdentityManager
     */
    public function login(): AuthIdentityManager
    {
        $authIdentityManager = $this->createAuthIdentityManager();
        if ($this->getCredentialsChecker()->needRenewPassword()) {
            $authIdentityManager->requirePasswordChange(true);
            log_info('Password change required, because main password should be renewed');
        }
        $this->createOriginalUserManager()->unregister();
        $userId = $this->getCredentialsChecker()->getUserId();
        $authIdentityManager->applyUser($userId);
        return $authIdentityManager;
    }

    /**
     * @return CredentialsChecker
     */
    public function getCredentialsChecker(): CredentialsChecker
    {
        if ($this->credentialsChecker === null) {
            throw new InvalidArgumentException('CredentialsChecker not defined');
        }
        return $this->credentialsChecker;
    }

    /**
     * @param CredentialsChecker $credentialsChecker
     * @return static
     */
    public function setCredentialsChecker(CredentialsChecker $credentialsChecker): static
    {
        $this->credentialsChecker = $credentialsChecker;
        return $this;
    }
}
