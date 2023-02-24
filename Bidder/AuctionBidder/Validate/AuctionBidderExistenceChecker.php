<?php
/**
 * SAM-7842: Refactor \User_Access
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepositoryCreateTrait;

/**
 * Class BidderExistenceChecker
 * @package Sam\Bidder\AuctionBidder\Validate
 */
class AuctionBidderExistenceChecker extends CustomizableClass
{
    use AuctionBidderReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Checks if user is an approved bidder of the given auction
     *
     * @param int $userId
     * @param int $auctionId
     * @return bool
     */
    public function existApprovedBidder(int $userId, int $auctionId): bool
    {
        $isFound = $this->createAuctionBidderReadRepository()
            ->filterApproved(true)
            ->filterAuctionId($auctionId)
            ->filterUserId($userId)
            ->innerJoinBidder() // check bidder role
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)   // check auction availability
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE) // check user availability by status
            ->joinUserSkipFlag([Constants\User::FLAG_BLOCK, Constants\User::FLAG_NOAUCTIONAPPROVAL]) // check user by flag
            ->skipBidderNum(null) // approved bidder should have bidder#
            ->exist();
        return $isFound;
    }
}
