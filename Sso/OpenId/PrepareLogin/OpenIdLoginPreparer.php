<?php
/**
 * This service performs initial preparation before user would be redirected to SSO Login page.
 * SSO Login page url can be acquired by \Sam\Sso\OpenId\Url\OpenIdUrlProvider::buildIdpLoginUrl()
 *
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

namespace Sam\Sso\OpenId\PrepareLogin;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\Sso\OpenId\Common\Storage\OpenIdNativeSessionStorageCreateTrait;

/**
 * Prepare and store url to be redirected back to, when we successfully log in trough OpenIdAuthenticator.
 */
class OpenIdLoginPreparer extends CustomizableClass
{
    use OpenIdNativeSessionStorageCreateTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Prepare SAM state before redirecting user to SSO OpenId Login page.
     * @param string $backPageUrlFromRequest
     * @return void
     */
    public function prepareForLogin(string $backPageUrlFromRequest): void
    {
        $isUrl = UrlParser::new()->isUrl($backPageUrlFromRequest);
        if (!$isUrl) {
            return;
        }

        $sessionStorage = $this->createOpenIdNativeSessionStorage();
        $sessionStorage->setBackUrl($backPageUrlFromRequest);
    }

    /**
     * Return back-page url expected to use after login via SSO OpenId provider.
     * @return string
     */
    public function getStoredBackUrl(): string
    {
        return $this->createOpenIdNativeSessionStorage()->getBackUrl();
    }
}
