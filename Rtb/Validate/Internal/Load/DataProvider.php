<?php
/**
 * SAM-6438: Refactor auction state validation on rtb console load
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

namespace Sam\Rtb\Validate\Internal\Load;

use Auction;
use AuctionCache;
use Sam\Auction\Load\AuctionCacheLoader;
use Sam\Auction\Load\AuctionLoader;
use Sam\AuctionLot\LotNo\Duplicate\DuplicatedLotNoLoader;
use Sam\AuctionLot\LotNo\EmptyNo\EmptyLotNoLoader;
use Sam\Bidding\BidIncrement\Validate\BidIncrementExistenceChecker;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\Rtb\Validate\Internal\Load
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

    public function existAccountBidIncrementForZeroAmount(int $accountId, string $auctionType, bool $isReadOnlyDb = false): bool
    {
        return BidIncrementExistenceChecker::new()->existByAmountForAccount(0., $accountId, $auctionType, $isReadOnlyDb);
    }

    public function existAuctionBidIncrementForZeroAmount(int $auctionId, bool $isReadOnlyDb = false): bool
    {
        return BidIncrementExistenceChecker::new()->existByAmountForAuction(0., $auctionId, $isReadOnlyDb);
    }

    public function existAuctionBidIncrements(int $auctionId, bool $isReadOnlyDb = false): bool
    {
        return BidIncrementExistenceChecker::new()->existForAuction($auctionId, $isReadOnlyDb);
    }

    public function loadAuction(int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return AuctionLoader::new()->load($auctionId, $isReadOnlyDb);
    }

    public function loadAuctionCache(int $auctionId, bool $isReadOnlyDb = false): ?AuctionCache
    {
        return AuctionCacheLoader::new()->load($auctionId, $isReadOnlyDb);
    }

    public function loadDuplicatedLotNoInfos(int $auctionId, bool $isReadOnlyDb = false): array
    {
        return DuplicatedLotNoLoader::new()->loadInfos($auctionId, $isReadOnlyDb);
    }

    public function loadEmptyLotNoInfos(int $auctionId, bool $isReadOnlyDb = false): array
    {
        return EmptyLotNoLoader::new()->loadInfos($auctionId, $isReadOnlyDb);
    }
}
