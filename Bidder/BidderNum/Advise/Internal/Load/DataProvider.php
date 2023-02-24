<?php
/**
 * SAM-8662: Adjustments for Bidder Number Padding and Adviser services and apply unit tests
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\BidderNum\Advise\Internal\Load;

use Auction;
use Sam\Auction\Load\AuctionLoader;
use Sam\Bidder\AuctionBidder\Validate\AuctionBidderChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepository;
use Sam\User\Validate\UserExistenceChecker;

/**
 * Class DataProvider
 * @package Sam\Bidder\BidderNum\Advise\Internal\Load
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

    public function existByCustomerNoAmongPermanent(int $customerNo, bool $isReadOnlyDb = false): bool
    {
        return UserExistenceChecker::new()->existByCustomerNoAmongPermanent($customerNo, [], $isReadOnlyDb);
    }

    public function existBidderNo(int $bidderNum, int $auctionId, bool $isReadOnlyDb = false): bool
    {
        return AuctionBidderChecker::new()->existBidderNo($bidderNum, $auctionId, [], [], $isReadOnlyDb);
    }

    public function loadAuction(int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return AuctionLoader::new()->load($auctionId, $isReadOnlyDb);
    }

    public function findHighestAvailableBidderNumInAuction(int $auctionId, bool $isReadOnlyDb = false): int
    {
        $row = AuctionBidderReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->select(['MAX(cast(bidder_num AS UNSIGNED)) + 1 AS highest'])
            ->loadRow();
        $bidderNum = (int)$row['highest'] ?: 1;
        return $bidderNum;
    }
}
