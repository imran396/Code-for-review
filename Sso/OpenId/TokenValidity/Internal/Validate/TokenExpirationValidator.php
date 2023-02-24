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

namespace Sam\Sso\OpenId\TokenValidity\Internal\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Sso\OpenId\Common\Storage\OpenIdNativeSessionStorageCreateTrait;
use Sam\Sso\OpenId\TokenValidity\Internal\Validate\TokenExpirationValidationResult as Result;

class TokenExpirationValidator extends CustomizableClass
{
    use CurrentDateTrait;
    use OpenIdNativeSessionStorageCreateTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(): Result
    {
        $result = Result::new()->construct();
        $sessionStorage = $this->createOpenIdNativeSessionStorage();
        if (!$sessionStorage->hasRefreshToken()) {
            return $result->addInfo(Result::INFO_REFRESH_TOKEN_ABSENT_IN_SESSION);
        }
        if ($this->isTokenExpired($sessionStorage->getTokenExpirationTs())) {
            return $result->addInfo(Result::INFO_TOKEN_EXPIRED);
        }
        return $result;
    }

    protected function isTokenExpired(int $tokenExpirationTime): bool
    {
        $currentTime = $this->getCurrentDateUtc()->getTimestamp();
        if ($tokenExpirationTime < $currentTime) {
            return true;
        }
        return false;
    }
}
