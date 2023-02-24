<?php
/**
 * SAM-10437: Adjust Referrer/Origin configuration for v3-6, v3-7
 * SAM-5676: Refactor Origin/Referer checking logic and implement unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 13, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Csrf\OriginReferer\Validate\Internal\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;

class OriginRefererCheckValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_NOT_ALLOWED_ORIGIN = 1;
    public const ERR_NOT_ALLOWED_REFERER = 2;
    public const ERR_NO_ORIGIN_REFERER = 3;

    public const WARNING_NO_ORIGIN_REFERER = 11;

    public const INFO_CHECK_DISABLED = 21;
    public const INFO_NOT_POST = 22;
    public const INFO_MATCH_ALLOWED_DOMAINS_ORIGIN = 23;
    public const INFO_MATCH_ALLOWED_DOMAINS_REFERER = 24;
    public const INFO_DISABLED_FOR_PATH = 25;

    protected const INFO_MESSAGES = [
        self::INFO_CHECK_DISABLED => 'CSRF originReferer check not enabled',
        self::INFO_NOT_POST => 'No CSRF synchronizerToken check, not a POST request %s',
        self::INFO_MATCH_ALLOWED_DOMAINS_ORIGIN => 'CSRF Origin check (%s) matching allowed domains',
        self::INFO_MATCH_ALLOWED_DOMAINS_REFERER => 'CSRF Referer domain check(%s) matching allowed domains',
        self::INFO_DISABLED_FOR_PATH => 'CSRF originReferer check is disabled for path "%s"'
    ];

    protected const ERROR_MESSAGES = [
        self::ERR_NOT_ALLOWED_ORIGIN => 'CSRF Origin check origin header domain %s not allowed',
        self::ERR_NOT_ALLOWED_REFERER => 'CSRF referer check referer header domain %s not allowed',
        self::ERR_NO_ORIGIN_REFERER => 'CSRF Origin/Referer check, no Origin and Referer header on POST %s blocking',
    ];

    protected const WARNING_MESSAGES = [
        self::WARNING_NO_ORIGIN_REFERER => 'CSRF Origin/Referer check, no Origin as Referer header on POST %s',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(
            static::ERROR_MESSAGES,
            [],
            static::WARNING_MESSAGES,
            static::INFO_MESSAGES
        );
        return $this;
    }

    // --- Mutate state ---

    public function addErrorWithInjectedInMessageArguments(int $code, array $messageArgs = []): static
    {
        $this->getResultStatusCollector()->addErrorWithInjectedInMessageArguments($code, $messageArgs);
        return $this;
    }

    public function addInfoWithInjectedInMessageArguments(int $code, array $messageArgs = []): static
    {
        $this->getResultStatusCollector()->addInfoWithInjectedInMessageArguments($code, $messageArgs);
        return $this;
    }

    public function addWarningWithInjectedInMessageArguments(int $code, array $messageArgs = []): static
    {
        $this->getResultStatusCollector()->addWarningWithInjectedInMessageArguments($code, $messageArgs);
        return $this;
    }

    // --- Query state ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function isSuccess(): bool
    {
        return !$this->hasError();
    }

    public function lastAddedErrorMessage(): string
    {
        return $this->getResultStatusCollector()->lastAddedErrorMessage();
    }

    public function lastAddedInfoMessage(): string
    {
        return $this->getResultStatusCollector()->lastAddedInfoMessage();
    }

    public function lastAddedWarningMessage(): string
    {
        return $this->getResultStatusCollector()->lastAddedWarningMessage();
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
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return int[][]
     * @internal
     */
    public function allResultCodes(): array
    {
        return $this->getResultStatusCollector()->getAllCodes();
    }
}
