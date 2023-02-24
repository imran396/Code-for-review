<?php
/**
 * Contains timed auction availability checking methods
 *
 * SAM-4379: Create namespace for Auction Availability Checker functionality
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 14, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Available;

use Account;
use Auction;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionAvailabilityCheckerBase
 * @package Sam\Auction\Available
 */
abstract class AuctionAvailabilityCheckerBase extends CustomizableClass
{
    /**
     * Timed auction is always available
     * @param Account $account
     * @return bool
     */
    public function isAvailable(Account $account): bool
    {
        return true;
    }

    /**
     * Timed auction is always available
     * @param int $accountId
     * @return bool
     */
    public function isAvailableForAccountId(int $accountId): bool
    {
        return true;
    }

    /**
     * We don't have bidding console for timed auction
     * @param Auction $auction
     * @return bool
     */
    public function isBiddingConsoleAvailable(Auction $auction): bool
    {
        return true;
    }
}
