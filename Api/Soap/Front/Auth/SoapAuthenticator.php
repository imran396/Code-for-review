<?php

namespace Sam\Api\Soap\Front\Auth;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\User\Auth\AdminAccessCheckerAwareTrait;
use Sam\User\Auth\Credentials\Validate\CredentialsCheckerCreateTrait;
use Sam\User\Auth\FailedLogin\Delay\FailedLoginDelayerCreateTrait;
use Sam\User\Auth\FailedLogin\FailedLoginLockoutManager;
use Sam\User\Auth\IpBlockerAwareTrait;
use Sam\User\Auth\LoginServiceAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class Auth
 * @package Sam\Soap
 */
class SoapAuthenticator
{
    use AdminAccessCheckerAwareTrait;
    use CredentialsCheckerCreateTrait;
    use FailedLoginDelayerCreateTrait;
    use IpBlockerAwareTrait;
    use LoginServiceAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use ServerRequestReaderAwareTrait;
    use SystemAccountAwareTrait;
    use UserAwareTrait;
    use UserLoaderAwareTrait;

    protected ?string $ip = null;

    public const ERR_IP_BLOCKED = 1;
    public const ERR_LOGIN_FAILED = 2;

    /** @var string[] */
    protected array $errorMessages = [
        self::ERR_IP_BLOCKED => 'Login failed because ip address is blocked',
        self::ERR_LOGIN_FAILED => 'Login failed',
    ];

    /**
     * Authenticate a user in Soap12:Header
     *
     * @param string $login Login
     * @param string $password Password
     * @return string|false Session ID
     */
    public function authenticate(string $login, string $password): string|false
    {
        $this->getResultStatusCollector()->construct($this->errorMessages);
        $errorMessage = '';
        $userAuth = null;
        $credentialsChecker = $this->createCredentialsChecker()->construct($login, $password);
        if ($credentialsChecker->verify()) {
            $userId = $credentialsChecker->getUserId();
            $adminAccessChecker = $this->getAdminAccessChecker()
                ->setUserId($userId)
                ->setSystemAccountId($this->getSystemAccountId());
            if ($adminAccessChecker->validate()) {
                $userAuth = $this->getLoginService()
                    ->setCredentialsChecker($credentialsChecker)
                    ->login();
            } else {
                $errorMessage = $adminAccessChecker->concatenatedErrorMessage();
            }

            $isBlockedByIp = $this->getIpBlocker()
                ->setIp($this->getIp())
                ->setUserId($userId)
                ->isBlocked();
            if ($isBlockedByIp) {
                $this->processIpBlock();
                $this->getResultStatusCollector()->addError(self::ERR_IP_BLOCKED);
                return false;
            }
        } else {
            $errorMessage = $credentialsChecker->errorMessageForUser();
        }

        if (!$userAuth) {
            $userId = $credentialsChecker->getUserId();
            $this->createFailedLoginDelayer()->delay();
            if ($userId) {
                FailedLoginLockoutManager::new()
                    ->construct($userId)
                    ->lockout();
            }
            $this->getResultStatusCollector()->addError(
                self::ERR_LOGIN_FAILED,
                'Authentication failed for ' . $login . '. ' . $errorMessage
            );
            return false;
        }

        $this->setUser($credentialsChecker->getUser());
        return session_id();
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
     * @noinspection PhpUnused
     */
    public function setIp(string $ip): static
    {
        $this->ip = trim($ip);
        return $this;
    }

    /**
     * Getting error messages as single string
     * @return string
     */
    public function concatenatedErrorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * Perform actions for blocking user by ip
     */
    private function processIpBlock(): void
    {
        $this->getIpBlocker()->block();

        $logInfo = composeSuffix(
            [
                'u' => $this->getIpBlocker()->getUserId(),
                'ip' => $this->getIpBlocker()->getIp()
            ]
        );
        log_info("Login via SOAP auth failed, because ip address is blocked{$logInfo}");
    }
}
