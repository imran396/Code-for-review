<?php
/**
 * General authorization point
 *
 * SAM-3566: Refactoring for user authorization logic
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Pavel Mitkovskiy <pmitkovskiy@samauctionsoftware.com>
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           29 Mar, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\User\Auth;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\User\Auth\Credentials\Validate\CredentialsCheckerCreateTrait;
use Sam\User\Auth\Credentials\Validate\CredentialsChecker;
use Sam\User\Auth\FailedLogin\Delay\FailedLoginDelayerCreateTrait;
use Sam\User\Auth\FailedLogin\FailedLoginLockoutManager;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Auth\Identity\AuthIdentityManager;

/**
 * Class Login
 * @package Sam\User\Auth
 */
class Login extends CustomizableClass
{
    use AdminAccessCheckerAwareTrait;
    use CredentialsCheckerCreateTrait;
    use DateHelperAwareTrait;
    use FailedLoginDelayerCreateTrait;
    use IpBlockerAwareTrait;
    use LoginServiceAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use ServerRequestReaderAwareTrait;
    use SystemAccountAwareTrait;
    use TranslatorAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Login attempt blocked by ip.
     */
    public const ERR_BLOCKED_BY_IP = 1;
    /**
     * Login failed due to unknown reason
     */
    public const ERR_LOGIN_FAILURE = 2;
    /**
     * User login lockout
     */
    public const ERR_LOGIN_LOCKOUT_FAILED = 3;
    /**
     * Admin side access check failed
     */
    public const ERR_ADMIN_ACCESS = 4;
    /**
     * Success login
     */
    public const OK_LOGGED_IN = 1;
    /**
     * Success login and password change request
     */
    public const OK_PASSWORD_CHANGE_REQ = 2;

    protected ?FailedLoginLockoutManager $failedLoginLockoutManager = null;
    protected string $password = '';
    protected string $username = '';
    protected ?string $ip = null;
    protected bool $isAdminCheckRequired = false;
    private ?int $checkingUserId;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function initResultStatusCollector(): void
    {
        $langLoginBlockedByIp = $this->getTranslator()->translate('GENERAL_LOGIN_BLOCKED', 'general');
        $langLoginInvalid = $this->getTranslator()->translate('GENERAL_LOGIN_INVALID', 'general');

        $errorMessages = [
            self::ERR_BLOCKED_BY_IP => $langLoginBlockedByIp,
            self::ERR_LOGIN_FAILURE => $langLoginInvalid,
            self::ERR_LOGIN_LOCKOUT_FAILED => '',
            self::ERR_ADMIN_ACCESS => '',
        ];

        $successMessages = [
            self::OK_LOGGED_IN => 'Logged in successfully',
            self::OK_PASSWORD_CHANGE_REQ => 'Password must be changed'
        ];

        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
    }

    /**
     * Process user login using username and password provided by setUsername() & setPassword()
     * Logs failed and success logins
     *
     * @param int $editorUserId
     * @return bool
     */
    public function login(int $editorUserId): bool
    {
        $this->initResultStatusCollector();
        $credentialsChecker = $this->createCredentialsChecker()->construct($this->getUsername(), $this->getPassword());
        $isVerified = $credentialsChecker->verify();
        // Get user id, that is found by his username in CredentialsChecker
        $this->checkingUserId = $credentialsChecker->getUserId();
        if ($isVerified) {
            if ($this->isAdminCheckRequired()) {
                $adminAccessChecker = $this->getAdminAccessChecker()
                    ->setUserId($this->checkingUserId)
                    ->setSystemAccountId($this->getSystemAccountId());
                if (!$adminAccessChecker->validate()) {
                    $this->processAdminAccessCheckFailed();
                    return false;
                }
            }

            $isBlockedByIp = $this->getIpBlocker()
                ->setIp($this->getIp())
                ->setUserId($this->checkingUserId)
                ->isBlocked();
            if ($isBlockedByIp) {
                $this->processIpBlock();
                return false;
            }

            $userAuthManager = $this->getLoginService()
                ->setCredentialsChecker($credentialsChecker)
                ->login();

            /** success login */
            return $this->processSuccess($userAuthManager, $editorUserId);
        }
        /** failed login */
        $this->processFail();

        if ($this->checkingUserId) { // still logged in after logout?
            $error = $credentialsChecker->errorCode();
            if ($error === CredentialsChecker::ERR_FAILED_LOGIN_LOCKOUT) {
                // we can't use non-translated error message from getCredentialsChecker, so format it again:
                $lockoutDate = $this->getFailedLoginLockoutManager()->getLockoutDate();
                $lockoutDateFormatted = $this->getDateHelper()
                    ->formattedDate($lockoutDate, null, null, 'AUCTIONS_DATE_LONG_SECS');
                $langLoginLockout = $this->getTranslator()->translate('USER_LOGIN_LOCKOUT', 'user');
                $errorMessage = sprintf($langLoginLockout, $lockoutDateFormatted);
                $this->getResultStatusCollector()->addError(self::ERR_LOGIN_LOCKOUT_FAILED, $errorMessage);
            }
        }

        return false;
    }

    /**
     * @return FailedLoginLockoutManager
     */
    public function getFailedLoginLockoutManager(): FailedLoginLockoutManager
    {
        if ($this->failedLoginLockoutManager === null) {
            $this->failedLoginLockoutManager = FailedLoginLockoutManager::new()
                ->construct($this->checkingUserId);
        }
        return $this->failedLoginLockoutManager;
    }

    /**
     * @param FailedLoginLockoutManager $failedLoginLockoutManager
     * @return static
     * @internal
     */
    public function setFailedLoginLockoutManager(FailedLoginLockoutManager $failedLoginLockoutManager): static
    {
        $this->failedLoginLockoutManager = $failedLoginLockoutManager;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return static
     */
    public function setPassword(string $password): static
    {
        // SAM-4884: Spaces should be trim in user details email, username and password values
        $this->password = trim($password);
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return static
     */
    public function setUsername(string $username): static
    {
        // SAM-4884: Spaces should be trim in user details email, username and password values
        $this->username = trim($username);
        return $this;
    }

    /**
     * @return bool if logged in but password should be changed
     */
    public function hasPasswordChangeRequest(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteSuccess(self::OK_PASSWORD_CHANGE_REQ);
    }

    /**
     * Get concatenated error messages
     *
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return bool
     */
    protected function isAdminCheckRequired(): bool
    {
        return $this->isAdminCheckRequired;
    }

    /**
     * @param bool $required
     * @return static
     */
    public function requireAdminCheck(bool $required): static
    {
        $this->isAdminCheckRequired = $required;
        return $this;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        if ($this->ip === null) {
            $this->ip = $this->getServerRequestReader()->remoteAddr();
        }
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return static
     * @internal
     */
    public function setIp(string $ip): static
    {
        $this->ip = trim($ip);
        return $this;
    }

    private function processAdminAccessCheckFailed(): void
    {
        $userId = $this->getAdminAccessChecker()->getUserId();
        $errorMessage = $this->getAdminAccessChecker()->concatenatedErrorMessage();
        $this->getResultStatusCollector()->addError(self::ERR_ADMIN_ACCESS, $errorMessage);
        $logInfo = composeSuffix(
            [
                'u' => $userId,
                'ip' => $this->getIp(),
                'username' => $this->getUsername(),
                'message' => $errorMessage
            ]
        );
        log_info("Admin side access failed{$logInfo}");
    }

    /**
     * Perform actions for blocking user by ip
     */
    private function processIpBlock(): void
    {
        $this->getResultStatusCollector()->addError(self::ERR_BLOCKED_BY_IP);
        $this->getIpBlocker()->block();
        $logInfo = composeSuffix(
            [
                'u' => $this->getIpBlocker()->getUserId(),
                'ip' => $this->getIpBlocker()->getIp(),
                'username' => $this->getUsername(),
                'message' => $this->getResultStatusCollector()->getConcatenatedErrorMessage()
            ]
        );
        log_info("Login failed, because ip address is blocked{$logInfo}");
    }

    /**
     * Perform action for successful login
     * @param AuthIdentityManager $userAuthManager
     * @param int $editorUserId
     * @return bool
     */
    private function processSuccess(AuthIdentityManager $userAuthManager, int $editorUserId): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            // If session is not active, means something goes wrong
            log_errorBackTrace("Session not started on login, when it should");
            return false;
        }

        // SAM-3975: Regenerate session id after successful login to prevent session fixation attacks
        session_regenerate_id();

        $userId = $userAuthManager->getUserId();
        Logger::new()->logSuccessLogin($userId, $this->getIp());
        $logData = ['u' => $userId, 'ip' => $this->getIp(), 'username' => $this->getUsername()];
        log_info("Successful login" . composeSuffix($logData));

        if ($userAuthManager->isPasswordChangeRequired()) {
            $this->getResultStatusCollector()->addSuccess(self::OK_PASSWORD_CHANGE_REQ);
        } else {
            $this->getResultStatusCollector()->addSuccess(self::OK_LOGGED_IN);
        }

        FailedLoginLockoutManager::new()
            ->construct($userId)
            ->reset($editorUserId);

        return true;
    }

    /**
     * Perform actions for failed login:
     * Lockout (if open)
     */
    private function processFail(): void
    {
        $this->createFailedLoginDelayer()->delay();
        $this->getResultStatusCollector()->addError(self::ERR_LOGIN_FAILURE);
        if ($this->checkingUserId) {
            $this->getFailedLoginLockoutManager()->lockout();
        }
    }

}
