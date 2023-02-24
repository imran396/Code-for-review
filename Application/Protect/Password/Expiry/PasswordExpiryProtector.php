<?php
/**
 * Analyze request and user session state, and redirect to Change Password page or do nothing.
 *
 * SAM-7615: Unit test for password expiry protector
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           2/25/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Password\Expiry;

use Sam\Application\ApplicationAwareTrait;
use Sam\Application\Protect\Password\Expiry\Internal\Detect\ChangePasswordUrlDetector;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;

/**
 * Class PasswordExpiryProtector
 * @package
 */
class PasswordExpiryProtector extends CustomizableClass
{
    use ApplicationAwareTrait;
    use ApplicationRedirectorCreateTrait;
    use AuthIdentityManagerCreateTrait;
    use ParamFetcherForRouteAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * When user must change password, do not allow user to navigate through the app
     */
    public function protect(): void
    {
        $ui = $this->getApplication()->ui();
        $controllerName = $this->getParamFetcherForRoute()->getControllerName();
        $isPasswordChangeRequired = $this->createAuthIdentityManager()->isPasswordChangeRequired();
        $urlDetector = ChangePasswordUrlDetector::new()->construct();
        $urlConfig = $urlDetector->detectUrlConfig($ui, $controllerName, $isPasswordChangeRequired);
        if (!$urlConfig) {
            return;
        }
        $redirectUrl = $this->getUrlBuilder()->build($urlConfig);
        $this->createApplicationRedirector()->redirect($redirectUrl);
    }
}
