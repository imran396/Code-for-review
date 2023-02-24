<?php
/**
 * SAM-6755: Programmatic fix to prevent user from being registered for the same auction twice
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Save;

use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Lock\DbLockerCreateTrait;

/**
 * DB locking manager, which is used in the process of registration of auction bidders
 * to prevent user from being registered for the same auction twice
 *
 * Class AuctionBidderDBLocker
 * @package Sam\Bidder\AuctionBidder\Save
 */
class AuctionBidderDbLocker extends CustomizableClass
{
    use DbLockerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get lock for a specific user and auction
     *
     * @param int $userId
     * @param int $auctionId
     */
    public function lock(int $userId, int $auctionId): void
    {
        $lockName = $this->makeLockName($userId, $auctionId);
        if (!$this->createDbLocker()->getLock($lockName)) {
            throw new RuntimeException("Attempts limit exceeded, when trying to get free lock ({$lockName}).");
        }
    }

    /**
     * Release lock for a specific user and auction
     *
     * @param int $userId
     * @param int $auctionId
     */
    public function release(int $userId, int $auctionId): void
    {
        $lockName = $this->makeLockName($userId, $auctionId);
        $this->createDbLocker()->releaseLock($lockName);
    }

    /**
     * @param int $userId
     * @param int $auctionId
     * @return string
     */
    protected function makeLockName(int $userId, int $auctionId): string
    {
        $lockName = sprintf(Constants\DbLock::SINGLE_AUCTION_BIDDER_REGISTRATION_BY_USER_ID_AND_AUCTION_ID_TPL, $userId, $auctionId);
        return $lockName;
    }
}
