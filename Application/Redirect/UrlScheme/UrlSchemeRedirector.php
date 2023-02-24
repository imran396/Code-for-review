<?php
/**
 * Url scheme (http|https) redirect
 *
 * SAM-9507: Move url scheme redirection to controller layer
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 14, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Redirect\UrlScheme;

use Sam\Application\Redirect\UrlScheme\Internal\Url\RedirectUrlDetector;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Security\Ssl\Feature\SslAvailabilityCheckerCreateTrait;

/**
 * Class UrlSchemeRedirector
 */
class UrlSchemeRedirector extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use ConfigRepositoryAwareTrait;
    use ServerRequestReaderAwareTrait;
    use SslAvailabilityCheckerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check running url for correspondence to required scheme and redirect to it, if needed.
     */
    public function redirect(): void
    {
        $redirectUrl = RedirectUrlDetector::new()->detect(
            $this->getServerRequestReader()->currentUrl(),
            $this->createSslAvailabilityChecker()->isEnabled()
        );
        if ($redirectUrl) {
            $this->createApplicationRedirector()->redirect($redirectUrl);
        }
    }
}
