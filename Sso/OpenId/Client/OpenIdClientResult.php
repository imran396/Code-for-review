<?php

namespace Sam\Sso\OpenId\Client;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class Result
 * @package Sam\Sso\OpenId\Client
 */
class OpenIdClientResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_BAD_STATUS_CODE_RETURNED_BY_IDP_SERVER = 1;
    public const ERR_JSON_PARSE = 2;
    public const ERR_RETURNED_BY_IDP = 3;

    protected const ERROR_MESSAGES = [
        self::ERR_BAD_STATUS_CODE_RETURNED_BY_IDP_SERVER => 'bad result from IdP (%s)',
        self::ERR_JSON_PARSE => 'Cannot decode json result from IdP (%s)',
        self::ERR_RETURNED_BY_IDP => 'Error returned from IdP (%s)',
    ];

    protected OpenIdTokenData $tokenData;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(string $glue = "\n"): static
    {
        $this->getResultStatusCollector()->construct(
            self::ERROR_MESSAGES,
            [],
            [],
            [],
            $glue
        );
        return $this;
    }

    // --- Mutate ---

    public function setTokenData(OpenIdTokenData $tokenData): static
    {
        $this->tokenData = $tokenData;
        return $this;
    }

    public function addErrorWithInjectedInMessageArguments(int $code, array $arguments = [], array $payload = []): static
    {
        $this->getResultStatusCollector()->addErrorWithInjectedInMessageArguments($code, $arguments, $payload);
        return $this;
    }

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    // --- Query ---

    public function getTokenData(): OpenIdTokenData
    {
        return $this->tokenData;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorMessage(string $glue = '.'): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    public function errorCode(): int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }

}
