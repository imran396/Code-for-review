<?php

namespace Sam\Sso\OpenId\TokenValidity\Internal\TokenValidityExtend;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Sso\OpenId\Client\OpenIdTokenData;

class TokenValidityExtendingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_BAD_RESPONSE_RETURNED_BY_IDP_SERVER = 1;

    protected const ERROR_MESSAGES = [
        self::ERR_BAD_RESPONSE_RETURNED_BY_IDP_SERVER => 'Bad response from IdP server (%s)',
    ];

    public array $errors = [];
    protected OpenIdTokenData $tokenData;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(
            self::ERROR_MESSAGES
        );
        return $this;
    }

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addErrorWithInjectedInMessageArguments(int $code, array $arguments = [], array $payload = []): static
    {
        $this->getResultStatusCollector()->addErrorWithInjectedInMessageArguments($code, $arguments, $payload);
        return $this;
    }

    public function setTokenData(OpenIdTokenData $tokenData): static
    {
        $this->tokenData = $tokenData;
        return $this;
    }

    // --- query

    public function getConcatenatedErrorMessage(string $glue = '.'): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    public function getTokenData(): OpenIdTokenData
    {
        return $this->tokenData;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorCode(): int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }

}
