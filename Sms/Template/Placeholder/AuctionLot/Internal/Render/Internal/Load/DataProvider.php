<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\Internal\Load;

use Auction;
use LotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotCacheLoaderAwareTrait;
use Sam\Bidding\BidTransaction\Validate\BidTransactionExistenceCheckerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Class DataProvider
 * @package Sam\Sms\Template\Placeholder\AuctionLot\Internal\Render\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotCacheLoaderAwareTrait;
    use BidTransactionExistenceCheckerAwareTrait;
    use LotItemLoaderAwareTrait;
    use TimezoneLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadAuction(int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb);
    }

    public function loadLotItem(int $lotItemId, bool $isReadOnlyDb = false): ?LotItem
    {
        return $this->getLotItemLoader()->load($lotItemId, $isReadOnlyDb);
    }

    public function loadTimezoneLocation(int $timezoneId, bool $isReadOnlyDb = false): ?string
    {
        $timezone = $this->getTimezoneLoader()->load($timezoneId, $isReadOnlyDb);
        return $timezone->Location ?? null;
    }

    public function loadCurrentBidAmount(int $auctionLotId, bool $isReadOnlyDb = false): float
    {
        $auctionLotCache = $this->getAuctionLotCacheLoader()->loadById($auctionLotId, $isReadOnlyDb);
        return (float)($auctionLotCache->CurrentBid ?? 0);
    }

    public function loadAskingBidAmount(int $auctionLotId, bool $isReadOnlyDb = false): float
    {
        $auctionLotCache = $this->getAuctionLotCacheLoader()->loadById($auctionLotId, $isReadOnlyDb);
        return (float)($auctionLotCache->AskingBid ?? 0);
    }

    public function countBids(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): int
    {
        $qty = $this->createBidTransactionExistenceChecker()->count($lotItemId, $auctionId, false, $isReadOnlyDb);
        return $qty;
    }
}
