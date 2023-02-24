<?php
/**
 * Verify entered password, can check admin access to account.
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

namespace Sam\User\Auth\Credentials\Validate;

use DateInterval;
use DateTimeZone;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\User\Auth\Credentials\Load\DataProviderAwareTrait;
use Sam\User\Auth\FailedLogin\FailedLoginLockoutManager;
use Sam\User\Password;

/**
 * Class CredentialsChecker
 */
class CredentialsChecker extends CustomizableClass
{
    use CurrentDateTrait;
    use DataProviderAwareTrait;
    use DateHelperAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use SystemAccountAwareTrait;
    use UserAwareTrait;

    public const ERR_ACTIVE_USER_NOT_FOUND = 1;
    public const ERR_PASSWORD_EMPTY = 2;
    public const ERR_PASSWORD_NOT_MATCH = 3;
    public const ERR_TEMP_PASSWORD_EXPIRED = 6;
    public const ERR_FAILED_LOGIN_LOCKOUT = 8;

    /** @var string[] */
    protected array $errorMessages = [
        self::ERR_ACTIVE_USER_NOT_FOUND => 'Active user not found',
        self::ERR_PASSWORD_EMPTY => 'Password cannot be empty',
        self::ERR_PASSWORD_NOT_MATCH => 'Username and password do not match',
        self::ERR_TEMP_PASSWORD_EXPIRED => 'Temporary password expired',
        self::ERR_FAILED_LOGIN_LOCKOUT => 'Account locked until %s',
    ];

    /**
     * We don't want to specialize these cases by output to user,
     * so he shouldn't be able to identify if entered user exists or not.
     * @var int[]
     */
    protected const HIDE_ERROR_CODES = [
        self::ERR_ACTIVE_USER_NOT_FOUND,
        self::ERR_PASSWORD_NOT_MATCH
    ];

    protected const ERROR_MESSAGE_INVALID_USERNAME_OR_PASSWORD = 'Invalid username or password';

    protected string $username;
    protected string $password;
    protected bool $isMainPasswordMatched = false;
    protected bool $isTemporaryPasswordMatched = false;
    protected ?Password\Strength\Validator $strengthValidator = null;
    protected ?Password\HashHelper $hashHelper = null;
    protected ?FailedLoginLockoutManager $failedLoginLockoutManager = null;

    /**
     * Get instance of LoginManager
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }


    /**
     * @param string $username
     * @param string $password
     * @return $this
     */
    public function construct(string $username, string $password): CredentialsChecker
    {
        $this->username = trim($username);
        $this->password = trim($password);
        return $this;
    }

    /**
     * @return bool
     */
    public function verify(): bool
    {
        $collector = $this->getResultStatusCollector()->construct($this->errorMessages);

        $user = $this->getDataProvider()->loadUserByUsername($this->username);
        $this->setUser($user);

        $userId = $user->Id ?? null;
        $logSuffix = composeSuffix(
            [
                'u' => $userId,
                'username' => $this->username,
            ]
        );

        if (!$user) {
            $collector->addError(self::ERR_ACTIVE_USER_NOT_FOUND);
            log_info($collector->lastAddedErrorMessage() . $logSuffix);
            return false;
        }

        if (empty($this->password)) {
            $collector->addError(self::ERR_PASSWORD_EMPTY);
            log_info($collector->lastAddedErrorMessage() . $logSuffix);
            return false;
        }

        $failedLoginLockoutManager = $this->getFailedLoginLockoutManager()->construct($userId);
        $isLoginLocked = $failedLoginLockoutManager->isLocked();
        if ($isLoginLocked) {
            $lockoutDate = $failedLoginLockoutManager->getLockoutDate();
            $lockoutDateFormatted = $this->getDateHelper()
                ->formattedDate($lockoutDate, null, null, 'AUCTIONS_DATE_LONG_SECS');
            $collector->addErrorWithInjectedInMessageArguments(self::ERR_FAILED_LOGIN_LOCKOUT, [$lockoutDateFormatted]);
            log_info($collector->lastAddedErrorMessage() . $logSuffix);
            return false;
        }

        $userAuthentication = $this->getUserAuthenticationOrCreate();
        $this->isMainPasswordMatched = $this->getHashHelper()->verify($this->password, $user->Pword);
        $this->isTemporaryPasswordMatched = $this->verifyTemporaryPassword(
            $this->password,
            $userAuthentication->TmpPword
        );
        if (
            !$this->isMainPasswordMatched
            && !$this->isTemporaryPasswordMatched
        ) {
            $collector->addError(self::ERR_PASSWORD_NOT_MATCH);
            log_info($collector->lastAddedErrorMessage() . $logSuffix);
            return false;
        }

        if (
            !$this->isMainPasswordMatched
            && $this->isTemporaryPasswordMatched
            && $this->isExpiredTemporaryPassword()
        ) {
            $collector->addError(self::ERR_TEMP_PASSWORD_EXPIRED);
            log_info($collector->lastAddedErrorMessage() . $logSuffix);
            return false;
        }

        return true;
    }

    /**
     * @param Password\Strength\Validator $strengthValidator
     * @return static
     */
    public function setStrengthValidator(Password\Strength\Validator $strengthValidator): static
    {
        $this->strengthValidator = $strengthValidator;
        return $this;
    }

    /**
     * @return Password\Strength\Validator
     */
    protected function getStrengthValidator(): Password\Strength\Validator
    {
        if ($this->strengthValidator === null) {
            $this->strengthValidator = Password\Strength\Validator::new()->initBySystemParametersOfMainAccount();
        }
        return $this->strengthValidator;
    }

    /**
     * @param Password\HashHelper $hashHelper
     * @return static
     */
    public function setHashHelper(Password\HashHelper $hashHelper): static
    {
        $this->hashHelper = $hashHelper;
        return $this;
    }

    /**
     * @return Password\HashHelper
     */
    protected function getHashHelper(): Password\HashHelper
    {
        if ($this->hashHelper === null) {
            $this->hashHelper = Password\HashHelper::new();
        }
        return $this->hashHelper;
    }

    /**
     * Check if password needs to be changed
     *
     * @return bool
     */
    public function needRenewPassword(): bool
    {
        $renewPassword = $this->getStrengthValidator()
            ->getOptions()
            ->getRenewPassword();
        if (
            $this->isMainPasswordMatched
            && $renewPassword > 0
        ) {
            $userAuthentication = $this->getUserAuthenticationOrCreate();
            if (!$userAuthentication->PwordDate) {
                return true;
            }

            $renewalDateTime = clone $userAuthentication->PwordDate;
            $renewalDateTime
                ->setTimezone(new DateTimeZone('UTC'))
                ->add(new DateInterval('P' . $renewPassword . 'D'));
            if ($renewalDateTime <= $this->getCurrentDateUtc()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Verify temporary password
     *
     * @param string $checkingPassword
     * @param string $passwordHash
     * @return bool
     */
    protected function verifyTemporaryPassword(string $checkingPassword, string $passwordHash): bool
    {
        $isMatched = $passwordHash
            && $this->getHashHelper()->verify($checkingPassword, $passwordHash);
        return $isMatched;
    }

    /**
     * Check if temporary password is expired
     * @return bool
     */
    protected function isExpiredTemporaryPassword(): bool
    {
        $tmpTimeout = $this->getStrengthValidator()
            ->getOptions()
            ->getTmpTimeout();
        if ($tmpTimeout > 0) {
            $currentDate = $this->getCurrentDateUtc();
            $userAuthentication = $this->getUserAuthenticationOrCreate();
            if (!$userAuthentication->TmpPwordTs) {
                return true;
            }

            $timeoutDate = clone $userAuthentication->TmpPwordTs;
            $timeoutDate
                ->setTimezone(new DateTimeZone('UTC'))
                ->add(new DateInterval('PT' . $tmpTimeout . 'M'));
            if ($timeoutDate <= $currentDate) {
                return true;
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
            $this->failedLoginLockoutManager = FailedLoginLockoutManager::new();
        }
        return $this->failedLoginLockoutManager;
    }

    /**
     * @param FailedLoginLockoutManager $failedLoginLockoutManager
     * @return static
     */
    public function setFailedLoginLockoutManager(FailedLoginLockoutManager $failedLoginLockoutManager): static
    {
        $this->failedLoginLockoutManager = $failedLoginLockoutManager;
        return $this;
    }

    /**
     * Getting error messages from ResultStatusCollector, generated in $this->verify()
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @return int|null
     */
    public function errorCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }


    /**
     * Get list of error codes
     * @return int[]
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * Return error message that is intended for output to user.
     * Should hide the fact of username existence or absence.
     * @return string
     */
    public function errorMessageForUser(): string
    {
        $collector = $this->getResultStatusCollector();
        if ($collector->hasConcreteError(self::HIDE_ERROR_CODES)) {
            return self::ERROR_MESSAGE_INVALID_USERNAME_OR_PASSWORD;
        }
        return $collector->getConcatenatedErrorMessage();
    }
}
