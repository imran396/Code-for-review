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

namespace Sam\Sso\OpenId\Authenticate\Internal\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Sso\OpenId\Client\OpenIdTokenData;
use Sam\Sso\OpenId\Jwt\Extract\IdTokenDataExtractionResult;

class AuthValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_QUERY_STATE_DIFF_FROM_SESSION_STATE = 1;
    public const ERR_BAD_RESPONSE_RETURNED_BY_IDP_SERVER = 2;
    public const ERR_NO_ACCESS_TOKEN_IN_RESPONSE = 3;
    public const ERR_NO_REFRESH_TOKEN_IN_RESPONSE = 4;
    public const ERR_NO_ID_TOKEN_IN_RESPONSE = 5;
    public const ERR_ID_TOKEN_EXTRACT_DATA_FAIL = 6;
    public const ERR_FROM_IDP = 7;
    public const ERR_SESSION_STATE_EMPTY = 8;
    public const ERR_EXPIRES_IN_EMPTY = 9;

    public const INFO_EMPTY_STATE = 21;

    protected const INFO_MESSAGES = [
        self::INFO_EMPTY_STATE => 'Query state is empty',
    ];

    protected const ERROR_MESSAGES = [
        self::ERR_QUERY_STATE_DIFF_FROM_SESSION_STATE => 'Query state (%s) is not the same as session state (%s)',
        self::ERR_BAD_RESPONSE_RETURNED_BY_IDP_SERVER => 'Bad response from IdP server (%s)',
        self::ERR_NO_ACCESS_TOKEN_IN_RESPONSE => 'No access token found in response: %s',
        self::ERR_NO_REFRESH_TOKEN_IN_RESPONSE => 'No refresh token found in response: %s',
        self::ERR_NO_ID_TOKEN_IN_RESPONSE => 'No id token found in response: %s',
        self::ERR_ID_TOKEN_EXTRACT_DATA_FAIL => 'There was problems during Id Token Extract Data. %s',
        self::ERR_FROM_IDP => 'Identity provider error: %s',
        self::ERR_SESSION_STATE_EMPTY => 'Session state is empty',
        self::ERR_EXPIRES_IN_EMPTY => 'Tokens expires_in is empty: %s',
    ];

    public ?IdTokenDataExtractionResult $tokenDataExtractionResult = null;
    protected OpenIdTokenData $tokenData;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(
            self::ERROR_MESSAGES,
            [],
            [],
            self::INFO_MESSAGES
        );
        return $this;
    }

    // --- Mutation logic ---
    public function setTokenData(OpenIdTokenData $tokenData): static
    {
        $this->tokenData = $tokenData;
        return $this;
    }

    public function addError(int $code, ?string $message = null, array $payload = []): static
    {
        $this->getResultStatusCollector()->addError($code, $message, $payload);
        return $this;
    }

    public function addInfo(int $code, ?string $message = null, array $payload = []): static
    {
        $this->getResultStatusCollector()->addInfo($code, $message, $payload);
        return $this;
    }

    public function addErrorWithInjectedInMessageArguments(int $code, array $arguments = [], array $payload = []): static
    {
        $this->getResultStatusCollector()->addErrorWithInjectedInMessageArguments($code, $arguments, $payload);
        return $this;
    }

    public function setTokenDataExtractionResult(?IdTokenDataExtractionResult $tokenDataExtractionResult): static
    {
        $this->tokenDataExtractionResult = $tokenDataExtractionResult;
        return $this;
    }

    // --- Query logic ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }

    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    public function concatenatedErrorMessage(string $glue = null): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    public function hasEmptyState(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteInfo(self::INFO_EMPTY_STATE);
    }

    public function infoCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstInfoCode();
    }

    public function getTokenData(): OpenIdTokenData
    {
        return $this->tokenData;
    }

    public function isSuccess(): bool
    {
        return !$this->hasError() && !$this->hasEmptyState();
    }
}
