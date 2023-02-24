<?php
/**
 * Correct the current url according domain mode setting.
 *
 * SAM-9355: Refactor Domain Detector and Domain Redirector for unit testing
 * https://bidpath.atlassian.net/browse/SAM-9355
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\DomainDestination;

use Sam\Application\ApplicationAwareTrait;
use Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\RedirectionUrlDetectorCreateTrait;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ResponsiveDomainDestinationRedirector
 * @package Sam\Application\Controller\Responsive\DomainDestination
 */
class ResponsiveDomainDestinationRedirector extends CustomizableClass
{
    use ApplicationAwareTrait;
    use ApplicationRedirectorCreateTrait;
    use RedirectionUrlDetectorCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Redirecting to url based on domain mode settings.
     */
    public function redirect(): void
    {
        $application = $this->getApplication();
        $ui = $application->ui();
        if (!$ui->isWebResponsive()) {
            // This url correction service is for responsive site only
            return;
        }

        $systemAccountId = $application->getSystemAccountId();
        $url = $this->createRedirectionUrlDetector()->detect($systemAccountId);
        if (!$url) {
            return;
        }

        log_debug("Correct domain destination url and redirect to $url");
        $this->createApplicationRedirector()->redirect($url);
    }
}
