<?php
/**
 * SAM-4403: Refactor password reset feature https://bidpath.atlassian.net/browse/SAM-4403
 *
 * @author        Vahagn Hovsepyan
 * @version       SVN: $Id: $
 * @since         Oct 16, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\User\Restore;

use DateInterval;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Qform\PublicErrorCollectionAwareTrait;
use Sam\Storage\DeleteRepository\Entity\ResetPassword\ResetPasswordDeleteRepositoryCreateTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\ReadRepository\Entity\ResetPassword\ResetPasswordReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\User\UserWriteRepositoryAwareTrait;
use Sam\User\Account\Validate\UserAccountExistenceCheckerAwareTrait;
use Sam\User\Password\HashHelper;
use Sam\User\Password\Strength\PasswordStrengthValidator;

/**
 * Class PasswordResetter
 */
class PasswordResetter extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use PasswordStrengthValidator;
    use PublicErrorCollectionAwareTrait;
    use ResetPasswordDeleteRepositoryCreateTrait;
    use ResetPasswordReadRepositoryCreateTrait;
    use SystemAccountAwareTrait;
    use TranslatorAwareTrait;
    use UserAccountExistenceCheckerAwareTrait;
    use UserAwareTrait;
    use UserWriteRepositoryAwareTrait;

    protected ?string $resetLink = null;
    protected ?string $password = null;
    protected ?string $confirmationPassword = null;
    protected ?string $errorTitle = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $resetLink
     * @return static
     */
    public function setResetLink(string $resetLink): static
    {
        $this->resetLink = trim($resetLink);
        return $this;
    }

    /**
     * @param string $password
     * @return static
     */
    public function setPassword(string $password): static
    {
        $this->password = trim($password);
        return $this;
    }

    /**
     * @param string $password
     * @return static
     */
    public function setConfirmationPassword(string $password): static
    {
        $this->confirmationPassword = trim($password);
        return $this;
    }

    /**
     * @param string $errorTitle
     * @return static
     */
    public function setErrorTitle(string $errorTitle): static
    {
        $this->errorTitle = trim($errorTitle);
        return $this;
    }

    /**
     * check validation for password, password confirmation and user
     *
     * @return bool
     */
    public function validate(): bool
    {
        $isValid = true;
        $langPassword = $this->getTranslator()->translate('USER_PASSWORD', 'user');
        $langConfirmPassword = $this->getTranslator()->translate('USER_CONFPWD', 'user');
        $password = $this->getPassword();
        $confirmPassword = $this->getConfirmationPassword();
        $user = $this->getUser(true);

        if (!$password) {
            $isValid = false;
            $message = $this->getTranslator()->translate('RESET_ERR_PASSWORD_REQUIRED', 'login');
            $this->getPublicErrorCollection()->addError("pass", $message, $langPassword);
        }

        if (!$confirmPassword) {
            $isValid = false;
            $message = $this->getTranslator()->translate('RESET_ERR_PASSWORD_REQUIRED', 'login');
            $this->getPublicErrorCollection()->addError("cpass", $message, $langConfirmPassword);
        }

        if (
            $password
            && $confirmPassword
        ) {
            $this->getPasswordStrengthValidator()->initBySystemParametersOfMainAccount();
            if ($password !== $confirmPassword) {
                $isValid = false;
                $message = $this->getTranslator()->translate('SIGNUP_ERR_INFO_PWDNOTMATCH', 'user');
                $this->getPublicErrorCollection()->addError("cpass", $message, $langPassword);
            } elseif (!$this->getPasswordStrengthValidator()->validate($password)) {
                $isValid = false;
                $message = $this->getPasswordStrengthValidator()->getErrorMessage();
                $this->getPublicErrorCollection()->addError("pass", $message, $langPassword);
            }
        }

        if (!$user) {
            $isValid = false;
            $this->setErrorTitle($this->getTranslator()->translate('FORGOT_ERR_COMBINATION_NOT_FOUND', 'login'));
        }

        return $isValid;
    }

    /**
     * saving password for user and removing record from `reset_password`
     */
    public function apply(): void
    {
        $resetLink = $this->getResetLink();
        $user = $this->getUser(true);
        if (!$user) {
            log_error("Available user not found, when restoring password" . composeSuffix(['u' => $this->getUserId()]));
            return;
        }
        $user->Pword = HashHelper::new()->normalizeAndEncrypt($this->getPassword());
        $this->getUserWriteRepository()->saveWithSelfModifier($user);
        $this->createResetPasswordDeleteRepository()
            ->filterUserId($this->getUserId())
            ->filterResetLink($resetLink)
            ->filterModifiedOnGreaterOrEqual($this->getResetLinkExpirationDateAccordingToCreatedDate())
            ->delete();
    }

    /**
     * @return string|null
     */
    public function getErrorTitle(): ?string
    {
        return $this->errorTitle;
    }

    /**
     * @return string
     */
    public function getResetLink(): string
    {
        return $this->resetLink;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getConfirmationPassword(): string
    {
        return $this->confirmationPassword;
    }

    /**
     * Check if valid random gen. reset_link
     *
     * @param int|null $userId
     * @param string $resetLink
     * @return bool
     */
    public function isPendingPasswordReset(?int $userId, string $resetLink): bool
    {
        if (
            !$userId
            || !$resetLink
        ) {
            return false;
        }

        $isFound = $this->createResetPasswordReadRepository()
            ->filterUserId($userId)
            ->filterResetLink($resetLink)
            ->filterModifiedOnGreaterOrEqual($this->getResetLinkExpirationDateAccordingToCreatedDate())
            ->exist();
        return $isFound;
    }

    /**
     * @return string
     */
    protected function getResetLinkExpirationDateAccordingToCreatedDate(): string
    {
        $subInterval = new DateInterval('PT' . $this->cfg()->get('core->user->credentials->passwordReset->lifeTime') . 'H');
        $expirationDate = $this->getCurrentDateUtc()->sub($subInterval);
        $expirationDateIso = $expirationDate->format(Constants\Date::ISO);
        return $expirationDateIso;
    }
}
