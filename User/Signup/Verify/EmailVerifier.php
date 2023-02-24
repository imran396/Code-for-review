<?php
/**
 * SAM-4096 : Email verification page at responsive side
 * https://bidpath.atlassian.net/browse/SAM-4096
 *
 * @author        Imran Rahman, Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Feb 22, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 * Usage example:
 *
 * $verifier = EmailVerifier::new()
 *    ->setUserId(..)
 *    ->setVerificationCode(..);
 * $success = $verifier->verify();
 */

namespace Sam\User\Signup\Verify;

use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\Url\Build\Config\Landing\ResponsiveLandingUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\AuditTrail\AuditTrailLoggerAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\WriteRepository\Entity\User\UserWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserAuthentication\UserAuthenticationWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Signup\Verify\Internal\Notify\NotifierCreateTrait;
use User;
use UserAuthentication;

/**
 * Class EmailVerifier
 * @package Sam\User\Signup\Verify
 */
class EmailVerifier extends CustomizableClass
{
    use AuditTrailLoggerAwareTrait;
    use NotifierCreateTrait;
    use ParamFetcherForGetAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use SystemAccountAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;
    use UrlParserAwareTrait;
    use UserAuthenticationWriteRepositoryAwareTrait;
    use UserAwareTrait;
    use UserLoaderAwareTrait;
    use UserWriteRepositoryAwareTrait;

    public const ERR_ALREADY_VERIFIED = 1;
    public const ERR_USER_ABSENT = 2;
    public const ERR_WRONG_CODE = 3;
    public const OK_VERIFIED = 11;

    protected string $verificationCode = '';
    protected ?UserAuthentication $updatedUserAuthentication = null;
    protected ?User $updatedUser = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $editorUserId null for anonymous user
     * @return bool
     */
    public function verify(?int $editorUserId): bool
    {
        $this->initResultStatusCollector();
        if ($this->validate()) {
            $userAuthentication = $this->getUserLoader()->loadUserAuthenticationOrCreate($this->getUserId(), true);
            $userAuthentication->EmailVerified = true;
            $this->getUserAuthenticationWriteRepository()->saveWithSelfModifier($userAuthentication);
            $this->updatedUserAuthentication = $userAuthentication;

            /** @var User $user existence checked in validation */
            $user = $this->getUser();
            $user->toActive();
            $this->getUserWriteRepository()->saveWithSelfModifier($user);
            $this->updatedUser = $user;

            $editorUserId = $editorUserId ?? $this->getUserLoader()->loadSystemUserId();
            $notifier = $this->createNotifier();
            $notifier->logAuditTrail($user, $editorUserId);
            $notifier->noticeAccountReg($user, $editorUserId);
            $this->getResultStatusCollector()->addSuccess(self::OK_VERIFIED);
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function validate(): bool
    {
        if (!$this->getUser()) {
            $this->getResultStatusCollector()->addError(self::ERR_USER_ABSENT);
            return false;
        }

        $userAuthentication = $this->getUserLoader()->loadUserAuthentication($this->getUserId(), true);
        if (
            !$userAuthentication
            || !$this->verificationCode
            || $userAuthentication->VerificationCode !== $this->verificationCode
        ) {
            $this->getResultStatusCollector()->addError(self::ERR_WRONG_CODE);
            return false;
        }

        if ($userAuthentication->EmailVerified) {
            $this->getResultStatusCollector()->addError(self::ERR_ALREADY_VERIFIED);
            return false;
        }

        return true;
    }

    /**
     * @param string $verificationCode
     * @return static
     */
    public function setVerificationCode(string $verificationCode): static
    {
        $this->verificationCode = trim($verificationCode);
        return $this;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    /**
     * @return string
     */
    public function invalidErrorMessage(): string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes([self::ERR_USER_ABSENT, self::ERR_WRONG_CODE]);
    }

    /**
     * @return bool
     */
    public function hasAlreadyVerifiedError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_ALREADY_VERIFIED]);
    }

    /**
     * @return string|null
     */
    public function alreadyVerifiedErrorMessage(): ?string
    {
        return $this->getResultStatusCollector()->findFirstErrorMessageAmongCodes([self::ERR_ALREADY_VERIFIED]);
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    /**
     * @return array
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        $url = $this->getParamFetcherForGet()->getBackUrl()
            ?: $this->getUrlBuilder()->build(ResponsiveLandingUrlConfig::new()->forRedirect());
        $url = $this->getUrlParser()->removeQueryString($url);
        return $url;
    }

    /**
     * @return UserAuthentication|null
     */
    public function updatedUserAuthentication(): ?UserAuthentication
    {
        return $this->updatedUserAuthentication;
    }

    /**
     * @return User|null
     */
    public function updatedUser(): ?User
    {
        return $this->updatedUser;
    }

    protected function initResultStatusCollector(): void
    {
        $tr = $this->getTranslator();
        $errorMessages = [
            self::ERR_ALREADY_VERIFIED => $tr->translate('LOGIN_ERR_EMAIL_VERIFIED', 'login'),
            self::ERR_USER_ABSENT => $tr->translate('LOGIN_ERR_UNKNOWN', 'login'),
            self::ERR_WRONG_CODE => $tr->translate('LOGIN_ERR_MISMATCH_CODE', 'login'),
        ];
        $successMessage = [
            self::OK_VERIFIED => $tr->translate('LOGIN_EMAIL_VERIFIED', 'login'),
        ];
        $this->getResultStatusCollector()->construct($errorMessages, $successMessage);
    }
}
