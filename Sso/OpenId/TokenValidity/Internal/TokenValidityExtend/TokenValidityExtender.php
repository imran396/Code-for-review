<?php
/**
 * This service makes request to IdP with help of refresh token.
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

namespace Sam\Sso\OpenId\TokenValidity\Internal\TokenValidityExtend;

use Sam\Core\Service\CustomizableClass;
use Sam\Sso\OpenId\Client\OpenIdClientCreateTrait;
use Sam\Sso\OpenId\TokenValidity\Internal\TokenValidityExtend\TokenValidityExtendingResult as Result;

class TokenValidityExtender extends CustomizableClass
{
    use OpenIdClientCreateTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function extend(string $refreshTokenFromSession): Result
    {
        $result = Result::new()->construct();

        $result = $this->generateNewAccessToken($refreshTokenFromSession, $result);
        return $result;
    }

    protected function generateNewAccessToken(string $refreshToken, Result $result): Result
    {
        $openIdClient = $this->createOpenIdClient()->constructWithDefaults();
        $openIdClientResult = $openIdClient->requestTokenFromRefreshToken($refreshToken);
        if ($openIdClientResult->hasError()) {
            return $result->addErrorWithInjectedInMessageArguments(
                Result::ERR_BAD_RESPONSE_RETURNED_BY_IDP_SERVER,
                [$openIdClientResult->errorMessage()]
            );
        }
        $result->setTokenData($openIdClientResult->getTokenData());

        return $result;
    }

}
