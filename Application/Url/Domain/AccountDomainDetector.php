<?php
/**
 * SAM-9477: Extend JSON API GET configuration information endpoint to include installation and authorized account information
 * SAM-5139: Domain Detector class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 23, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Domain;

use Account;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\Domain\AccountDomainPureDetector;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Account domain detection methods.
 * Domain depends on the main account http host and url handling method
 *
 * Class AccountDomainDetector
 * @package Sam\Application\Url\Domain
 */
class AccountDomainDetector extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Detect account domain by account entity object
     *
     * @param Account $account
     * @return string
     */
    public function detectByAccount(Account $account): string
    {
        return AccountDomainPureDetector::new()->detectByAccount(
            $account,
            $this->cfg()->get('core->portal->mainAccountId'),
            $this->cfg()->get('core->app->httpHost'),
            $this->cfg()->get('core->portal->urlHandling')
        );
    }

    /**
     * Detect account domain by account data
     *
     * @param int $accountId
     * @param string $urlDomain
     * @param string $name
     * @return string
     */
    public function detectByValues(
        int $accountId,
        string $urlDomain,
        string $name
    ): string {
        return AccountDomainPureDetector::new()->detectByValues(
            $accountId,
            $urlDomain,
            $name,
            $this->cfg()->get('core->portal->mainAccountId'),
            $this->cfg()->get('core->app->httpHost'),
            $this->cfg()->get('core->portal->urlHandling')
        );
    }
}
