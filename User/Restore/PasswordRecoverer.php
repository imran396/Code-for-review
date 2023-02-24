<?php
/**
 * SAM-4181: Password and Username recoverers https://bidpath.atlassian.net/browse/SAM-4181
 *
 * @author        Imran Rahman
 * @version       SVN: $Id: $
 * @since         April 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\User\Restore;

use Email_Template;
use Sam\Core\Constants;
use Sam\Core\Email\Validate\EmailAddressChecker;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Security\Captcha\Alternative\Validate\AlternativeCaptchaValidator;
use Sam\Security\Captcha\Simple\Validate\SimpleCaptchaValidator;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;
use Sam\User\Account\Validate\UserAccountExistenceCheckerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * Class PasswordRecoverer
 * @package Sam\User\Restore
 */
class PasswordRecoverer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use SystemAccountAwareTrait;
    use TranslatorAwareTrait;
    use UserAccountExistenceCheckerAwareTrait;
    use UserAwareTrait;
    use UserLoaderAwareTrait;
    use UserReadRepositoryCreateTrait;

    public const ERR_EMAIL_REQUIRED = 1;
    public const ERR_EMAIL_INVALID = 2;
    public const ERR_USERNAME_REQUIRED = 3;
    public const ERR_COMBINATION_NOT_FOUND = 4;

    public const OK_SENT = 11;

    protected string $username = '';
    protected string $email = '';
    protected ?AlternativeCaptchaValidator $alternativeCaptchaValidator = null;
    protected ?SimpleCaptchaValidator $simpleCaptchaValidator = null;
    /**
     * @var int[]
     */
    protected array $emailErrors = [self::ERR_EMAIL_REQUIRED, self::ERR_EMAIL_INVALID];
    /**
     * @var int[]
     */
    protected array $usernameErrors = [self::ERR_USERNAME_REQUIRED];
    /**
     * @var int[]
     */
    protected array $userNotFoundErrors = [self::ERR_COMBINATION_NOT_FOUND];
    protected bool $isCaptchaEnabled = true;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Overwrite getUser() method with custom entity loading
     * @return User|null
     */
    public function getUser(): ?User
    {
        $user = $this->getUserAggregate()->getUser();
        if (!$user) {
            $user = $this->loadUserByEmailAndUsername();
            $this->setUser($user);
        }
        return $user;
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        // Init ResultStatusCollector
        $tr = $this->getTranslator();
        $langRequiredCb = static function () use ($tr) {
            return $tr->translate('GENERAL_REQUIRED', 'general');
        };
        $langInvalidFormatCb = static function () use ($tr) {
            return $tr->translate('GENERAL_INVALID_FORMAT', 'general');
        };
        $langForgotPasswordErrorCb = static function () use ($tr) {
            return $tr->translate('FORGOT_ERR_COMBINATION_NOT_FOUND', 'login');
        };
        $langPasswordEmailSentCb = static function () use ($tr) {
            return $tr->translate('FORGOT_PASSWORD_EMAIL_SENT', 'login');
        };
        $errorMessages = [
            self::ERR_EMAIL_REQUIRED => $langRequiredCb,
            self::ERR_EMAIL_INVALID => $langInvalidFormatCb,
            self::ERR_USERNAME_REQUIRED => $langRequiredCb,
            self::ERR_COMBINATION_NOT_FOUND => $langForgotPasswordErrorCb,
        ];
        $successMessages = [
            self::OK_SENT => $langPasswordEmailSentCb,
        ];
        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
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
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $username
     * @return static
     */
    public function setUsername(string $username): static
    {
        $this->username = trim($username);
        return $this;
    }

    /**
     * @param string $email
     * @return static
     */
    public function setEmail(string $email): static
    {
        $this->email = trim($email);
        return $this;
    }

    /**
     * @param AlternativeCaptchaValidator $alternativeCaptchaValidator
     * @return static
     */
    public function setAlternativeCaptchaValidator(AlternativeCaptchaValidator $alternativeCaptchaValidator): static
    {
        $this->alternativeCaptchaValidator = $alternativeCaptchaValidator;
        return $this;
    }

    /**
     * @param SimpleCaptchaValidator $simpleCaptchaValidator
     * @return static
     */
    public function setSimpleCaptchaValidator(SimpleCaptchaValidator $simpleCaptchaValidator): static
    {
        $this->simpleCaptchaValidator = $simpleCaptchaValidator;
        return $this;
    }

    /**
     * @return AlternativeCaptchaValidator|null
     */
    public function getAlternativeCaptchaValidator(): ?AlternativeCaptchaValidator
    {
        return $this->alternativeCaptchaValidator;
    }

    /**
     * @return SimpleCaptchaValidator|null
     */
    public function getSimpleCaptchaValidator(): ?SimpleCaptchaValidator
    {
        return $this->simpleCaptchaValidator;
    }

    /**
     * @param bool $captchaEnabled
     * @return static
     */
    public function enableCaptcha(bool $captchaEnabled): static
    {
        $this->isCaptchaEnabled = $captchaEnabled;
        return $this;
    }

    /**
     * validate Username
     */
    protected function validateUsername(): void
    {
        if ($this->getUsername() === '') {
            $this->getResultStatusCollector()->addError(self::ERR_USERNAME_REQUIRED);
        }
    }

    /**
     * validate email
     */
    protected function validateEmail(): void
    {
        if ($this->getEmail() === '') {
            $this->getResultStatusCollector()->addError(self::ERR_EMAIL_REQUIRED);
        } elseif (!EmailAddressChecker::new()->isEmail($this->getEmail())) {
            $this->getResultStatusCollector()->addError(self::ERR_EMAIL_INVALID);
        }
    }

    /**
     * user validation
     */
    protected function validateUserAbsent(): void
    {
        if (!$this->getUser()) {
            $this->getResultStatusCollector()->addError(self::ERR_COMBINATION_NOT_FOUND);
        }
    }

    /**
     * check username empty or not
     * @return bool
     */
    public function hasUsernameError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->usernameErrors);
    }

    /**
     * check email address valid or not
     * @return bool
     */
    public function hasEmailError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->emailErrors);
    }

    /**
     * check user exists or not
     * @return bool
     */
    public function hasUserAbsentError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($this->userNotFoundErrors);
    }

    /**
     * Check simple captcha error
     * @return bool
     */
    public function hasSimpleCaptchaError(): bool
    {
        $has = true;
        if ($this->getSimpleCaptchaValidator()) {
            $has = $this->getSimpleCaptchaValidator()->validate();
        }
        return !$has;
    }

    /**
     * Check alternative captcha error
     * @return bool
     */
    public function hasAlternativeCaptchaError(): bool
    {
        $has = true;
        if ($this->getAlternativeCaptchaValidator()) {
            $has = $this->getAlternativeCaptchaValidator()->validate();
        }
        return !$has;
    }

    /**
     * @return string
     */
    public function usernameErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($this->usernameErrors);
    }

    /**
     * @return string
     */
    public function emailErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($this->emailErrors);
    }

    /**
     * @return string
     */
    public function simpleCaptchaErrorMessage(): string
    {
        return $this->getSimpleCaptchaValidator() ? $this->getSimpleCaptchaValidator()->errorMessage() : '';
    }

    /**
     * @return string
     */
    public function alternativeCaptchaErrorMessage(): string
    {
        return $this->getAlternativeCaptchaValidator() ? $this->getAlternativeCaptchaValidator()->getErrorMessage() : '';
    }

    /**
     * @return string
     */
    public function userAbsentErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes($this->userNotFoundErrors);
    }

    /**
     * Returns success message
     * @return string
     */
    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    /**
     * Check error
     * @return bool
     */
    public function validate(): bool
    {
        $isValid = true;
        $collector = $this->getResultStatusCollector();
        $collector->clear();
        $this->validateUsername();
        $this->validateEmail();
        if (
            !$this->hasUsernameError()
            && !$this->hasEmailError()
        ) {
            $this->validateUserAbsent();
        }
        if (
            $this->getSimpleCaptchaValidator()
            && !$this->getSimpleCaptchaValidator()->validate()
        ) {
            $isValid = false;
        }
        if (
            $this->getAlternativeCaptchaValidator()
            && !$this->getAlternativeCaptchaValidator()->validate()
        ) {
            $isValid = false;
        }
        if ($collector->hasError()) {
            $isValid = false;
        }
        return $isValid;
    }

    /**
     * notify user in email
     * @param int|null $editorUserId null for anonymous user
     */
    public function notifyUser(?int $editorUserId): void
    {
        $accountId = $this->cfg()->get('core->portal->mainAccountId');
        if ($this->isPortalSystemAccount()) {
            $accountId = $this->getSystemAccountId();
        }
        $editorUserId = $editorUserId ?? $this->getUserLoader()->loadSystemUserId();
        $emailManager = Email_Template::new()->construct(
            $accountId,
            Constants\EmailKey::RESET_PASS,
            $editorUserId,
            [$this->getUser()]
        );
        $emailManager->addToActionQueue();
        $this->getResultStatusCollector()->addSuccess(self::OK_SENT);
    }

    /***
     * Fetch active user with password by email and username
     * @return User|null
     */
    protected function loadUserByEmailAndUsername(): ?User
    {
        $user = $this->createUserReadRepository()
            ->enableReadOnlyDb(true)
            ->filterEmail($this->getEmail())
            ->filterUsername($this->getUsername())
            ->filterUserStatusId(Constants\User::US_ACTIVE)
            ->skipPword(['', null])
            ->loadEntity();
        return $user;
    }
}
