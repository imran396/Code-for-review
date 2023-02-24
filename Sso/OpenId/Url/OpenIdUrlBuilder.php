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

use Sam\Application\Url\Build\Config\Auth\LogoutUrlConfig;
use Sam\Application\Url\Build\Config\Auth\SsoAuthUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Build OpenId/IdentityProvider/ based URLs for login/logout/register
 */
class OpenIdUrlBuilder extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use UrlBuilderAwareTrait;

    protected const OPENID_SCOPE = 'openid profile email';
    protected readonly string $authEndpoint;
    protected readonly string $systemLoginUrl;
    protected readonly string $systemLogOutUrl;
    protected readonly string $clientId;
    protected readonly string $clientSecret;
    protected readonly string $logoutEndpoint;
    protected readonly string $registerEndpoint;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $authEndpoint,
        string $systemLoginUrl,
        string $systemLogOutUrl,
        string $clientId,
        string $clientSecret,
        string $logoutEndpoint,
        string $registerEndpoint
    ): static {
        $this->authEndpoint = $authEndpoint;
        $this->systemLoginUrl = $systemLoginUrl;
        $this->systemLogOutUrl = $systemLogOutUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->logoutEndpoint = $logoutEndpoint;
        $this->registerEndpoint = $registerEndpoint;
        return $this;
    }

    public function constructWithDefaults(): static
    {
        return $this->construct(
            (string)$this->cfg()->get('core->sso->openId->url->authEndpoint'),
            $this->getUrlBuilder()->build(SsoAuthUrlConfig::new()->forWeb()),
            $this->getUrlBuilder()->build(LogoutUrlConfig::new()->forWeb()),
            (string)$this->cfg()->get('core->sso->openId->appClient->clientId'),
            (string)$this->cfg()->get('core->sso->openId->appClient->clientSecret'),
            (string)$this->cfg()->get('core->sso->openId->url->logoutEndpoint'),
            (string)$this->cfg()->get('core->sso->openId->url->registerEndpoint'),
        );
    }

    public function makeLoginPageUrl(string $state): string
    {
        return UrlParser::new()->replaceParams(
            $this->authEndpoint,
            [
                'client_id' => $this->clientId,
                'response_type' => 'code',
                'state' => $state,
                'scope' => self::OPENID_SCOPE,
                // Force http since working on localhost
                'redirect_uri' => $this->systemLoginUrl,
            ]
        );
    }

    public function makeLogoutUrl(string $refreshToken): string
    {
        return UrlParser::new()->replaceParams(
            $this->logoutEndpoint,
            [
                'client_id' => $this->clientId,
                'refresh_token' => $refreshToken,
                'response_type' => 'code',
                'logout_uri' => $this->systemLogOutUrl,
                'redirect_uri' => $this->systemLogOutUrl,
            ]
        );
    }

    public function makeRegisterUrl(): string
    {
        return UrlParser::new()->replaceParams(
            $this->registerEndpoint,
            [
                'client_id' => $this->clientId,
                'response_type' => 'code',
                'scope' => self::OPENID_SCOPE,
                'redirect_uri' => $this->systemLoginUrl,
            ]
        );
    }

}
