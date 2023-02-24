<?php
/**
 * SAM-11129: (Phase 1) Unit tests for SAM SSO OpenId
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 21, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\OpenId\Authenticate\Internal\Validate\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Sso\OpenId\Client\OpenIdClient;
use Sam\Sso\OpenId\Client\OpenIdClientResult;
use Sam\Sso\OpenId\Jwt\Extract\IdTokenDataExtractionResult;
use Sam\Sso\OpenId\Jwt\Extract\IdTokenDataExtractor;

/**
 * Class DataProvider
 * @package
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function extractJwtResult(string $idToken): IdTokenDataExtractionResult
    {
        return IdTokenDataExtractor::new()
            ->constructWithDefaults()
            ->extract($idToken);
    }

    public function requestTokenByAuthorizationCode(string $authorizationCode): OpenIdClientResult
    {
        return OpenIdClient::new()
            ->constructWithDefaults()
            ->requestTokenByAuthorizationCode($authorizationCode);
    }
}
