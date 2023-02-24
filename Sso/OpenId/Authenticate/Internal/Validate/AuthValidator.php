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

namespace Sam\Sso\OpenId\Authenticate\Internal\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Sso\OpenId\Authenticate\Internal\Validate\AuthValidationResult as Result;
use Sam\Sso\OpenId\Authenticate\Internal\Validate\Internal\Load\DataProviderCreateTrait;

class AuthValidator extends CustomizableClass
{
    use DataProviderCreateTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(
        string $code,
        string $queryState,
        string $sessionState,
        string $errorDescriptionFromIdp
    ): Result {
        $result = Result::new()->construct();

        if ($errorDescriptionFromIdp) {
            return $result->addErrorWithInjectedInMessageArguments(Result::ERR_FROM_IDP, [$errorDescriptionFromIdp]);
        }

        $result = $this->validateSessionState($queryState, $sessionState, $result);
        if ($result->hasError()) {
            return $result;
        }

        $result = $this->validateAuthorizationCode($code, $result);
        if ($result->hasError()) {
            return $result;
        }

        $result = $this->validateTokens($result);
        $result = $this->validateTokenExpiration($result);
        if ($result->hasError()) {
            return $result;
        }

        // extract data from id_token
        $idToken = $result->getTokenData()->idToken;
        $extractionResult = $this->createDataProvider()->extractJwtResult($idToken);
        if ($extractionResult->hasError()) {
            return $result->addErrorWithInjectedInMessageArguments(
                Result::ERR_ID_TOKEN_EXTRACT_DATA_FAIL,
                [$extractionResult->errorMessage()]
            );
        }

        return $result->setTokenDataExtractionResult($extractionResult);
    }

    protected function validateAuthorizationCode(string $code, Result $result): Result
    {
        $openIdClientResult = $this->createDataProvider()->requestTokenByAuthorizationCode($code);
        if ($openIdClientResult->hasError()) {
            return $result->addErrorWithInjectedInMessageArguments(
                Result::ERR_BAD_RESPONSE_RETURNED_BY_IDP_SERVER,
                [$openIdClientResult->errorMessage()]
            );
        }
        $result->setTokenData($openIdClientResult->getTokenData());

        return $result;
    }

    protected function validateSessionState(string $queryState, string $sessionState, Result $result): Result
    {
        if ($queryState === '') {
            return $result->addInfo(Result::INFO_EMPTY_STATE);
        }

        if ($sessionState === '') {
            return $result->addError(Result::ERR_SESSION_STATE_EMPTY);
        }

        if ($queryState !== $sessionState) {
            return $result->addErrorWithInjectedInMessageArguments(
                Result::ERR_QUERY_STATE_DIFF_FROM_SESSION_STATE,
                [$queryState, $sessionState]
            );
        }

        return $result;
    }

    protected function validateTokens(Result $result): Result
    {
        $tokenData = $result->getTokenData();
        if (!$tokenData->idToken) {
            return $result->addErrorWithInjectedInMessageArguments(
                Result::ERR_NO_ID_TOKEN_IN_RESPONSE,
                [json_encode($result->concatenatedErrorMessage())]
            );
        }

        if (!$tokenData->accessToken) {
            return $result->addErrorWithInjectedInMessageArguments(
                Result::ERR_NO_ACCESS_TOKEN_IN_RESPONSE,
                [json_encode($result->concatenatedErrorMessage())]
            );
        }

        if (!$tokenData->refreshToken) {
            return $result->addErrorWithInjectedInMessageArguments(
                Result::ERR_NO_REFRESH_TOKEN_IN_RESPONSE,
                [json_encode($result->concatenatedErrorMessage())]
            );
        }

        return $result;
    }

    protected function validateTokenExpiration(Result $result): Result
    {
        if (!$result->getTokenData()->expiresIn) {
            return $result->addErrorWithInjectedInMessageArguments(
                Result::ERR_EXPIRES_IN_EMPTY,
                [json_encode($result)]
            );
        }

        return $result;
    }
}
