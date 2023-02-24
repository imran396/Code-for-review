<?php
/**
 * This 'Authenticated' cookie feature is for public side only (SAM-1196).
 * It operates with cookies, auth identity stored in session and GET request data.
 * If php session is not started, then we should skip this logic, otherwise cookie will be dropped and we will loose user session.
 * It enables 'Authenticated' cookie for authorized user and google analytics related request.
 *
 * SAM-6523: Request to No-session route leads to logout
 * SAM-5171: Application layer
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 12, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Index\Base\Concrete\NativeSession\Internal\AuthenticatedFlag;

use Sam\Core\Service\CustomizableClass;
use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;
use Sam\Core\Constants;

/**
 * Class AuthenticatedCookieInitializator
 * @package Sam\Application\Index\Base\Concrete
 */
class AuthenticatedCookieInitializator extends CustomizableClass
{
    use AuthIdentityManagerCreateTrait;
    use CookieHelperCreateTrait;
    use ParamFetcherForGetAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Restore authenticated cookie, if something happened with regular session (SAM-1196)
     * We shouldn't run this when session is expected to be not started (no-session routes),
     * because otherwise we would drop 'Authenticated' cookie for all user tabs, that are served by current native php session.
     */
    public function initialize(): void
    {
        $cookieHelper = $this->createCookieHelper();
        if (
            $cookieHelper->isAuthenticated()
            && !$this->createAuthIdentityManager()->isAuthorized()
        ) {
            $cookieHelper->enableAuthenticated(false);
        }
        $this->initAuthenticatedForGoogleAnalytics();
    }

    /**
     * Add authenticated cookie for google analytics request
     */
    protected function initAuthenticatedForGoogleAnalytics(): void
    {
        $googleAnalytics = $this->getParamFetcherForGet()->getString('ga');
        if (
            $googleAnalytics
            && str_contains($googleAnalytics, Constants\PageTracking::SIGNUP)
            && $this->createAuthIdentityManager()->isAuthorized()
        ) {
            $this->createCookieHelper()->enableAuthenticated(true);
        }
    }
}
