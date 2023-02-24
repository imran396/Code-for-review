<?php
/**
 * Contains hybrid auction availability checking methods
 *
 * SAM-4379: Create namespace for Auction Availability Checker functionality
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 5, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Available;

use Account;
use Auction;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class AuctionHybridAvailabilityChecker
 * @package Sam\Auction\Available
 */
class AuctionHybridAvailabilityChecker extends AuctionAvailabilityCheckerBase
{
    use AccountLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check, if Hybrid Auction feature is available for passed account
     * @param Account $account
     * @return bool
     */
    public function isAvailable(Account $account): bool
    {
        $isEnabledGlobally = $this->cfg()->get('core->auction->hybrid->enabled');
        $isEnabledForAccount = $this->cfg()->get('core->portal->enabled') ? $account->HybridAuctionEnabled : true;
        $isAvailable = $isEnabledGlobally
            && $isEnabledForAccount;
        return $isAvailable;
    }

    /**
     * Check, if Hybrid Auction feature is available for passed account
     * @param int $accountId
     * @return bool
     */
    public function isAvailableForAccountId(int $accountId): bool
    {
        $account = $this->getAccountLoader()->load($accountId);
        if (!$account) {
            log_debug("Account not found" . composeSuffix(['acc' => $accountId]));
        }
        $isAvailable = $account
            && $this->isAvailable($account);
        return $isAvailable;
    }

    /**
     * Check, if bidding console available
     * @param Auction $auction
     * @return bool
     */
    public function isBiddingConsoleAvailable(Auction $auction): bool
    {
        $account = $this->getAccountLoader()->load($auction->AccountId);
        $isAvailableForAccount = $account ? $this->isAvailable($account) : false;
        $currentDateUtc = $this->getCurrentDateUtc();
        $isAvailable = $isAvailableForAccount
            && (
                $auction->isStartedOrPaused()
                || ($auction->isActive()
                    && $auction->BiddingConsoleAccessDate < $currentDateUtc
                )
            );
        return $isAvailable;
    }
}
