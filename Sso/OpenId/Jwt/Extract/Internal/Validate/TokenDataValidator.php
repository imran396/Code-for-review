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

namespace Sam\Sso\OpenId\Jwt\Extract\Internal\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Sso\OpenId\Jwt\Extract\IdTokenDataDto;
use Sam\Sso\OpenId\Jwt\Extract\IdTokenDataExtractionResult as Result;

class TokenDataValidator extends CustomizableClass
{
    use CurrentDateTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(
        array $decodedJwtData,
        string $idpBaseAddress,
        string $clientId,
        Result $result
    ): Result {
        [$exp, $result] = $this->verifyExp($decodedJwtData, $result);
        [$sub, $result] = $this->verifySub($decodedJwtData, $result);

        $result = $this->validateIss($decodedJwtData, $idpBaseAddress, $result);
        $result = $this->validateAud($decodedJwtData, $clientId, $result);

        if ($result->hasError()) {
            return $result;
        }

        $decodedJwtData = $this->remapDecodedJwtData($decodedJwtData);

        if (!$this->validateDecodedData($decodedJwtData)) {
            return $result->addErrorWithInjectedInMessageArguments(
                Result::ERR_ID_TOKEN_MISSES_IMPORTANT_DATA,
                [json_encode($decodedJwtData)]
            );
        }

        $idTokenDataDto = IdTokenDataDto::new()->construct(
            $exp,
            $sub,
            $decodedJwtData['email'],
            $decodedJwtData['preferred_username'],
            $decodedJwtData['name'],
            $decodedJwtData['sub']
        );
        $result->setIdTokenDataDto($idTokenDataDto);
        return $result;
    }

    protected function verifyExp(array $decodedJwtData, Result $result): array
    {
        $time = $this->getCurrentDateUtc();
        $exp = $decodedJwtData['exp'] ?? 0;
        if ($exp < $time->getTimestamp()) {
            $result->addErrorWithInjectedInMessageArguments(
                Result::ERR_ID_TOKEN_EXP_MUST_BE_GREATER_THAN_CURRENT_TIME,
                [$exp, $time->getTimestamp()]
            );
        }
        return [$exp, $result];
    }

    protected function verifySub(array $decodedJwtData, Result $result): array
    {
        $sub = $decodedJwtData['sub'] ?? '';
        if (!$sub) {
            $result->addErrorWithInjectedInMessageArguments(
                Result::ERR_ID_TOKEN_SUB_MUST_NOT_BE_EMPTY,
                [$sub]
            );
        }
        return [$sub, $result];
    }

    protected function validateIss(array $decodedJwtData, string $idpBaseAddress, Result $result): Result
    {
        $iss = $decodedJwtData['iss'] ?? null;
        if ($idpBaseAddress !== $iss) {
            $result->addErrorWithInjectedInMessageArguments(
                Result::ERR_ID_TOKEN_IIS_MUST_MATCH,
                [$iss, $idpBaseAddress]
            );
        }
        return $result;
    }

    protected function validateAud(array $decodedJwtData, string $clientId, Result $result): Result
    {
        $aud = $decodedJwtData['aud'] ?? null;
        if ($clientId !== $aud) {
            $result->addErrorWithInjectedInMessageArguments(
                Result::ERR_ID_TOKEN_AUD_CLIENT_ID_MUST_MATCH,
                [$aud, $clientId]
            );
        }
        return $result;
    }

    protected function remapDecodedJwtData(array $decodedJwtData): array
    {
        // in cognito username = 'cognito:username' , and the name is missing, so let's try to fill
        if (
            !isset($decodedJwtData['preferred_username'])
            && isset($decodedJwtData['cognito:username'])
        ) {
            $decodedJwtData['preferred_username'] = $decodedJwtData['cognito:username'];
        }

        if (
            !isset($decodedJwtData['name'])
            && isset($decodedJwtData['cognito:username'])
        ) {
            $decodedJwtData['name'] = $decodedJwtData['cognito:username'];
        }
        return $decodedJwtData;
    }

    protected function validateDecodedData(array $decodedJwtData): bool
    {
        return (
            isset($decodedJwtData['email'])
            && $decodedJwtData['email'] !== ''
            && isset($decodedJwtData['preferred_username'])
            && $decodedJwtData['preferred_username'] !== ''
            && isset($decodedJwtData['name'])
            && $decodedJwtData['name'] !== '');
    }
}
