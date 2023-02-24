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

namespace Sam\Sso\OpenId\Url;

use phpseclib3\Crypt\Random;
use Sam\Application\Controller\Responsive\Login\TargetUrl\ResponsiveLoginTargetUrlDetectorCreateTrait;
use Sam\Application\Url\Build\Config\Auth\LogoutUrlConfig;
use Sam\Application\Url\Build\Config\Auth\SsoLoginUrlConfig;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\Sso\OpenId\Common\Storage\OpenIdNativeSessionStorageCreateTrait;

/**
 * decorator for OpenIdUrlBuilder, provides OpenId/IdentityProvider/ based URLs for login/logout/register
 */
class OpenIdUrlProvider extends CustomizableClass
{
    use OpenIdNativeSessionStorageCreateTrait;
    use OpenIdUrlBuilderCreateTrait;
    use ResponsiveLoginTargetUrlDetectorCreateTrait;
    use UrlBuilderAwareTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * build identity provider login url
     */
    public function buildIdpLoginUrl(string $randomStringForStateKey = null): string
    {
        $randomStringForStateKey = $randomStringForStateKey ?? md5(Random::string(20));
        $this->createOpenIdNativeSessionStorage()->setState($randomStringForStateKey);
        $openIdUrlBuilder = $this->createOpenIdUrlBuilder()->constructWithDefaults();
        return $openIdUrlBuilder->makeLoginPageUrl($randomStringForStateKey);
    }

    /**
     * build identity provider logout url
     */
    public function buildIdpLogoutUrl(): string
    {
        $sessionStorage = $this->createOpenIdNativeSessionStorage();
        if ($sessionStorage->hasTokens()) {
            $openIdUrlBuilder = $this->createOpenIdUrlBuilder()->constructWithDefaults();
            return $openIdUrlBuilder->makeLogoutUrl($sessionStorage->getRefreshToken());
        }

        return '';
    }

    /**
     * build identity provider register url
     */
    public function buildIdpRegisterUrl(): string
    {
        $openIdUrlBuilder = $this->createOpenIdUrlBuilder()->constructWithDefaults();
        return $openIdUrlBuilder->makeRegisterUrl();
    }

    public function buildReferralUrl(int $userId, int $systemAccountId, string $backUrl): string
    {
        return $this->createResponsiveLoginTargetUrlDetector()
            ->detectTargetUrl($userId, $systemAccountId, $backUrl, false);
    }

    public function buildRegularLogoutUrl(): string
    {
        return $this->getUrlBuilder()->build(LogoutUrlConfig::new()->forWeb());
    }

    public function buildLoginWithRedirectToProfileUrl(): string
    {
        $authUrl = $this->getUrlBuilder()->build(SsoLoginUrlConfig::new()->forWeb());
        $editProfileUrl = $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forRedirect(Constants\Url::P_PROFILE)
        );
        $authAndRedirectUrl = UrlParser::new()->replaceParams($authUrl, ['url' => $editProfileUrl]);
        return $authAndRedirectUrl;
    }
}
