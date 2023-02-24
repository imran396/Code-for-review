<?php
/**
 * SAM-9355: Refactor Domain Detector and Domain Redirector for unit testing
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\Load;

use Account;
use Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\DetectAccount\AccountDetector;
use Sam\Application\Url\DomainDestination\DomainDestinationDetector;
use Sam\Core\Service\CustomizableClass;

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

    public function detectAccount(int $systemAccountId): ?Account
    {
        return AccountDetector::new()->detect($systemAccountId);
    }

    public function detectDomainDestinationHost(Account $account): string
    {
        return DomainDestinationDetector::new()->detect($account);
    }
}
