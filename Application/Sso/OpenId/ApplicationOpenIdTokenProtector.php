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

namespace Sam\Application\Sso\OpenId;

use Sam\Application\Language\Update\ApplicationLanguageUpdaterCreateTrait;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\Url\Build\Config\Auth\ResponsiveLoginUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Sso\OpenId\Feature\OpenIdFeatureAvailabilityCheckerCreateTrait;
use Sam\Sso\OpenId\TokenValidity\OpenIdTokenValidityCreateTrait;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;

class ApplicationOpenIdTokenProtector extends CustomizableClass
{
    use ApplicationLanguageUpdaterCreateTrait;
    use ApplicationRedirectorCreateTrait;
    use AuthIdentityManagerCreateTrait;
    use OpenIdFeatureAvailabilityCheckerCreateTrait;
    use OpenIdTokenValidityCreateTrait;
    use UrlBuilderAwareTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Refresh token if needed
     */
    public function protect(): void
    {
        if (!$this->createOpenIdFeatureAvailabilityChecker()->isEnabled()) {
            return;
        }

        $resultFromTokenVerification = $this->createOpenIdTokenValidity()->verify();
        if ($resultFromTokenVerification->hasError()) {
            log_debug(
                "Could not extend token validity. " . composeSuffix([
                    'error' => $resultFromTokenVerification->getConcatenatedErrorMessage()
                ])
            );
            // logout user, because we did not succeed with token validating
            $this->logoutUser();
        }
    }

    protected function logoutUser(): void
    {
        $this->createAuthIdentityManager()->logout();
        $this->createApplicationLanguageUpdater()->updateOnLogout();
        $url = $this->getUrlBuilder()->build(ResponsiveLoginUrlConfig::new()->forRedirect());
        $this->createApplicationRedirector()->redirect($url);
    }

}
