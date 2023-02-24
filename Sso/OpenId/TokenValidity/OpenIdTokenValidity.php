<?php
/**
 * This is command service, that checks access token and in case of its expiration time is finished,
 * then it requests IdP for extending of access token with help of refresh token.
 *
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

use Sam\Core\Service\CustomizableClass;
use Sam\Sso\OpenId\Client\OpenIdTokenData;
use Sam\Sso\OpenId\Common\Storage\OpenIdNativeSessionStorageCreateTrait;
use Sam\Sso\OpenId\TokenValidity\Internal\TokenValidityExtend\TokenValidityExtenderCreateTrait;
use Sam\Sso\OpenId\TokenValidity\Internal\Validate\TokenExpirationValidatorCreateTrait;
use Sam\Sso\OpenId\TokenValidity\OpenIdTokenValidityResult as Result;

/**
 * Check access_token validity (check expiry), and extends it if it is possible.
 */
class OpenIdTokenValidity extends CustomizableClass
{
    use OpenIdNativeSessionStorageCreateTrait;
    use TokenExpirationValidatorCreateTrait;
    use TokenValidityExtenderCreateTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * verify token validity
     */
    public function verify(): Result
    {
        $result = OpenIdTokenValidityResult::new()->construct();
        $sessionStorage = $this->createOpenIdNativeSessionStorage();

        if (!$sessionStorage->isSessionAvailable()) {
            return $result->addInfo(Result::INFO_SESSION_NOT_AVAILABLE);
        }

        $validatorResult = $this->createTokenExpirationValidator()->validate();

        if ($validatorResult->isRefreshTokenAbsentInSession()) {
            return $result->addInfo(Result::INFO_REFRESH_TOKEN_ABSENT_IN_SESSION);
        }

        if ($validatorResult->isTokenExpired()) {
            // let`s try to extend validity
            $tokenExtendingResult = $this->createTokenValidityExtender()->extend($sessionStorage->getRefreshToken());
            if ($tokenExtendingResult->hasError()) {
                return $result->addErrorWithInjectedInMessageArguments(
                    Result::ERR_CANT_EXTEND_TOKEN,
                    [$tokenExtendingResult->getConcatenatedErrorMessage()]
                );
            }
            $this->saveTokenData($tokenExtendingResult->getTokenData());
            return $result->addSuccess(Result::OK_TOKEN_EXTENDED);
        }

        return $result->addSuccess(Result::OK_TOKEN_ACTUAL);
    }

    protected function saveTokenData(OpenIdTokenData $tokenData): void
    {
        $this->createOpenIdNativeSessionStorage()
            ->setTokenExpirationTs($tokenData->calcExpiresInTs())
            ->setAccessToken($tokenData->accessToken);
    }
}
