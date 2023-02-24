<?php
/**
 * Account visibility
 *
 * @see https://bidpath.atlassian.net/browse/SAM-3051
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 16, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\DomainAuctionVisibility;

use Account;
use Auction;
use Invoice;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class VisibilityChecker
 * @package Sam\Account\DomainAuctionVisibility
 */
class VisibilityChecker extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Auction $auction
     * @return bool
     */
    public function isAllowedForAuction(Auction $auction): bool
    {
        $visibilityContext = new VisibilityContext();
        $visibilityContext
            ->setAuction($auction)
            ->enableSamPortal($this->cfg()->get('core->portal->enabled'))
            ->setPortalDomainAuctionVisibility($this->cfg()->get('core->portal->domainAuctionVisibility'))
            ->setSystemAccount($this->getSystemAccount());
        $isAllowed = Visibility::new()->isAllowed($visibilityContext);
        return $isAllowed;
    }

    /**
     * @param Account $account
     * @return bool
     */
    public function isAllowedForAccount(Account $account): bool
    {
        $visibilityContext = new VisibilityContext();
        $visibilityContext
            ->setAccount($account)
            ->enableSamPortal($this->cfg()->get('core->portal->enabled'))
            ->setPortalDomainAuctionVisibility($this->cfg()->get('core->portal->domainAuctionVisibility'))
            ->setSystemAccount($this->getSystemAccount());
        $isAllowed = Visibility::new()->isAllowed($visibilityContext);
        return $isAllowed;
    }

    /**
     * @param Invoice $invoice
     * @return bool
     */
    public function isAllowedForInvoice(Invoice $invoice): bool
    {
        $account = $this->getAccountLoader()->load($invoice->AccountId);
        $isAllowed = $account ? $this->isAllowedForAccount($account) : false;
        return $isAllowed;
    }
}
