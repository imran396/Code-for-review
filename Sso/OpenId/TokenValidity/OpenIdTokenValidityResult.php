<?php
/**
 * SAM-10724: Login through SSO
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Georgi Nikolov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\OpenId\TokenValidity;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

class OpenIdTokenValidityResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_CANT_EXTEND_TOKEN = 1;

    public const OK_TOKEN_ACTUAL = 11;
    public const OK_TOKEN_EXTENDED = 12;

    public const INFO_REFRESH_TOKEN_ABSENT_IN_SESSION = 21;
    public const INFO_SESSION_NOT_AVAILABLE = 22;

    protected const ERROR_MESSAGES = [
        self::ERR_CANT_EXTEND_TOKEN => 'Can`t extend token (%s)',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_TOKEN_ACTUAL => 'Token is correct and actual',
        self::OK_TOKEN_EXTENDED => 'Token is correct and its expiry time is extended',
    ];

    protected const INFO_MESSAGES = [
        self::INFO_REFRESH_TOKEN_ABSENT_IN_SESSION => 'Refresh token absent in session',
        self::INFO_SESSION_NOT_AVAILABLE => 'PHP Native session not available',
    ];

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(
            self::ERROR_MESSAGES,
            self::SUCCESS_MESSAGES,
            [],
            self::INFO_MESSAGES
        );
        return $this;
    }

    // --- Mutation logic ---

    public function addErrorWithInjectedInMessageArguments(int $code, array $arguments = [], array $payload = []): static
    {
        $this->getResultStatusCollector()->addErrorWithInjectedInMessageArguments($code, $arguments, $payload);
        return $this;
    }

    public function addSuccess(int $code): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
        return $this;
    }

    public function addInfo(int $code, ?string $message = null, array $payload = []): static
    {
        $this->getResultStatusCollector()->addInfo($code, $message, $payload);
        return $this;
    }

    // --- Query logic ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function getConcatenatedErrorMessage(string $glue = null): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    public function errorCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }

    public function infoCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstInfoCode();
    }

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function successCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstSuccessCode();
    }
}
