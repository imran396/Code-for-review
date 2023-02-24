<?php
/**
 * SAM-10584: SAM SSO
 * SAM-10724: Login through SSO
 *
 * Project        sam
 * @author        Georgi Nikolov
 * @version       SVN: $Id: $
 * @since         Jun 15, 2022
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Sso\OpenId\Jwt\Extract;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

class IdTokenDataExtractionResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_JWT_DECODE_FAILED = 1;
    public const ERR_JWT_PARSE_KEY_SET_FAILED = 2;
    public const ERR_ID_TOKEN_EXP_MUST_BE_GREATER_THAN_CURRENT_TIME = 3;
    public const ERR_ID_TOKEN_SUB_MUST_NOT_BE_EMPTY = 4;
    public const ERR_ID_TOKEN_IIS_MUST_MATCH = 5;
    public const ERR_ID_TOKEN_AUD_CLIENT_ID_MUST_MATCH = 6;
    public const ERR_ID_TOKEN_MISSES_IMPORTANT_DATA = 7;

    protected const ERROR_MESSAGES = [
        self::ERR_JWT_DECODE_FAILED => 'JWT::decode() failed.',
        self::ERR_JWT_PARSE_KEY_SET_FAILED => 'JWT::decode() failed.',
        self::ERR_ID_TOKEN_EXP_MUST_BE_GREATER_THAN_CURRENT_TIME => 'IdToken exp (%d) must be lower than current time (%d)',
        self::ERR_ID_TOKEN_SUB_MUST_NOT_BE_EMPTY => 'IdToken sub (%s) must not be empty',
        self::ERR_ID_TOKEN_IIS_MUST_MATCH => 'IdToken iss (%s) must be the same as %s',
        self::ERR_ID_TOKEN_AUD_CLIENT_ID_MUST_MATCH => 'IdToken aud (%s) must be the same as %s',
        self::ERR_ID_TOKEN_MISSES_IMPORTANT_DATA => 'Missing important data in %s',
    ];

    public IdTokenDataDto $idTokenData;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        return $this;
    }

    // --- Mutation logic ---

    public function addErrorWithInjectedInMessageArguments(int $code, array $arguments = []): static
    {
        $this->getResultStatusCollector()->addErrorWithInjectedInMessageArguments($code, $arguments);
        return $this;
    }

    public function addErrorWithAppendedMessage(int $code, string $append): static
    {
        $this->getResultStatusCollector()->addErrorWithAppendedMessage($code, $append);
        return $this;
    }

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function setIdTokenDataDto(IdTokenDataDto $idTokenData): static
    {
        $this->idTokenData = $idTokenData;
        return $this;
    }

    // --- Query logic ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstErrorCode();
    }

    public function errorMessage(string $glue = '. '): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    public function getUuid(): string
    {
        return $this->idTokenData->uuid ?? '';
    }

    public function getEmail(): string
    {
        return $this->idTokenData->email ?? '';
    }

    public function toUuidAndEmail(): array
    {
        return [
            $this->getUuid(),
            $this->getEmail()
        ];
    }
}
