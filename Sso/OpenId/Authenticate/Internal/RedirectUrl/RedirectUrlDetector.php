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

namespace Sam\Sso\OpenId\Authenticate\Internal\RedirectUrl;

use Sam\Application\Landing\Responsive\ResponsiveLandingPageDetectorCreateTrait;
use Sam\Application\Url\Build\Config\Auth\SsoLoginUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Sso\OpenId\Url\OpenIdUrlProviderCreateTrait;

class RedirectUrlDetector extends CustomizableClass
{
    use OpenIdUrlProviderCreateTrait;
    use ResponsiveLandingPageDetectorCreateTrait;
    use UrlBuilderAwareTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function detect(
        int $systemAccountId,
        bool $hasEmptyState,
        ?int $userId,
        string $backPageUrlFromRequest,
        string $backPageUrlFromSession
    ): string {
        $urlProvider = $this->createOpenIdUrlProvider();

        if ($hasEmptyState) {
            return $this->getUrlBuilder()->build(SsoLoginUrlConfig::new()->forWeb());
        }

        if ($userId) {
            $urlAfterLogin = $this->detectUrlAfterLogin(
                $systemAccountId,
                $backPageUrlFromRequest,
                $backPageUrlFromSession
            );
            return $urlProvider->buildReferralUrl($userId, $systemAccountId, $urlAfterLogin);
        }

        return $urlProvider->buildRegularLogoutUrl();
    }

    /**
     * Search for redirect url after successful login
     */
    protected function detectUrlAfterLogin(
        int $systemAccountId,
        string $backPageUrlFromRequest,
        string $backPageUrlFromSession
    ): string {
        if ($backPageUrlFromSession) {
            return $backPageUrlFromSession;
        }

        if ($backPageUrlFromRequest) {
            return $backPageUrlFromRequest;
        }

        $landingPageDetector = $this->createResponsiveLandingPageDetector()->construct($systemAccountId);
        $landingPageDetector->detect();
        return $landingPageDetector->getRedirectUrl();
    }
}
