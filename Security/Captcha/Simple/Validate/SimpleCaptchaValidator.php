<?php
/**
 * SAM-4713 : Simple captcha validator
 * https://bidpath.atlassian.net/browse/SAM-4713
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/3/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Security\Captcha\Simple\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;

/**
 * Class SimpleCaptchaValidator
 * @package Sam\Security\Captcha\Simple\Validate
 */
class SimpleCaptchaValidator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use TranslatorAwareTrait;

    public const ERR_CAPTCHA_REQUIRED = 1;
    public const ERR_CAPTCHA_INCORRECT = 2;

    public const OK_VALID = 1;
    public const OK_SECRET_TEXT = 2;

    protected ?array $session = null;
    protected ?string $captchaText = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array|null
     */
    public function getSession(): ?array
    {
        return $this->session;
    }

    /**
     * @param array $session
     * @return static
     */
    public function setSession(array $session): static
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCaptchaText(): ?string
    {
        return $this->captchaText;
    }

    /**
     * @param string $captchaText
     * @return static
     */
    public function setCaptchaText(string $captchaText): static
    {
        $this->captchaText = trim($captchaText);    // no space characters in captcha
        return $this;
    }

    /**
     * Returns error message from error codes.
     * @return string|null
     */
    public function errorMessage(): ?string
    {
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(
            [
                self::ERR_CAPTCHA_REQUIRED,
                self::ERR_CAPTCHA_INCORRECT,
            ]
        );
    }

    /**
     * @return string[]
     * @internal
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * Return success messages
     * @return string
     */
    public function successMessage(): string
    {
        return (string)$this->getResultStatusCollector()->findFirstSuccessMessageAmongCodes(
            [
                self::OK_VALID,
                self::OK_SECRET_TEXT,
            ]
        );
    }

    /**
     * @return int[]
     * @internal
     */
    public function successCodes(): array
    {
        return $this->getResultStatusCollector()->getSuccessCodes();
    }

    /**
     * Checks, if any captcha error has or not.
     * @return bool
     */
    public function validate(): bool
    {
        $this->initResultStatusCollector();

        $collector = $this->getResultStatusCollector();
        $collector->clear();
        if (
            $this->cfg()->get('core->captcha->secretText')
            && $this->getCaptchaText() === $this->cfg()->get('core->captcha->secretText')
        ) {
            $collector->addSuccess(self::OK_SECRET_TEXT);
        } elseif ($this->getCaptchaText() === '') {
            $collector->addError(self::ERR_CAPTCHA_REQUIRED);
        } elseif (
            isset($this->session[Constants\Captcha::SESSION_KEY])
            && $this->getCaptchaText() !== $this->session[Constants\Captcha::SESSION_KEY]
        ) {
            $collector->addError(self::ERR_CAPTCHA_INCORRECT);
        } else {
            $collector->addSuccess(self::OK_VALID);
        }
        $isValid = !$collector->hasError();
        return $isValid;
    }

    protected function initResultStatusCollector(): void
    {
        $tr = $this->getTranslator();
        $errorMessages = [
            self::ERR_CAPTCHA_REQUIRED => static fn() => $tr->translate('GENERAL_ERR_SIMPLE_CAPTCHA_TEXTREQUIRED', 'general'),
            self::ERR_CAPTCHA_INCORRECT => static fn() => $tr->translate('GENERAL_ERR_SIMPLE_CAPTCHA_INCORRECT', 'general'),
        ];
        $successMessages = [
            self::OK_VALID => 'Entered captcha is valid',
            self::OK_SECRET_TEXT => 'Entered captcha is valid, because it meet secret text',
        ];
        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
    }
}
