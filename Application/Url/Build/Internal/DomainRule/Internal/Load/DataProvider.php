<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\DomainRule\Internal\Load;

use Account;
use Sam\Application\HttpRequest\ServerRequestReader;
use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Internal\Resolve\AccountFromUrlConfigResolver;
use Sam\Application\Url\Domain\AccountDomainDetector;
use Sam\Application\Url\UrlAdvisor;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\Application\Url\Build\Internal\DomainRule\Internal\Load
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

    public function detectAccountByUrlConfig(AbstractUrlConfig $urlConfig): ?Account
    {
        return AccountFromUrlConfigResolver::new()->detectAccount($urlConfig);
    }

    public function detectServerName(): string
    {
        return ServerRequestReader::new()->serverName();
    }

    public function detectScheme(): string
    {
        return UrlAdvisor::new()->detectScheme();
    }

    public function detectDomainByAccount(Account $account): string
    {
        return AccountDomainDetector::new()->detectByAccount($account);
    }
}
