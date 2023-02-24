<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\AuctionEvent\Notify\Sms\FirstLotUpcoming\Internal\Load;

use AuctionLotItem;
use LotItem;
use Sam\AuctionLot\Load\PositionalAuctionLotLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;

/**
 * Class DataProvider
 * @package Sam\AuctionLot\AuctionEvent\Notify\Sms\FirstLotUpcoming\Internal\Load
 * @internal
 */
class DataProvider extends CustomizableClass
{
    use LotItemLoaderAwareTrait;
    use PositionalAuctionLotLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param int $quantity
     * @return AuctionLotItem[]
     */
    public function loadFirstLots(int $auctionId, int $quantity): array
    {
        $firstAuctionLot = $this->getPositionalAuctionLotLoader()->loadFirstLot($auctionId);
        if (!$firstAuctionLot) {
            return [];
        }
        $auctionLots = $this->getPositionalAuctionLotLoader()->loadNextLots(
            $auctionId,
            $firstAuctionLot->LotItemId,
            ['limit' => $quantity - 1]
        );
        array_unshift($auctionLots, $firstAuctionLot);
        return $auctionLots;
    }

    public function loadLotItem(int $lotItemId, bool $isReadOnlyDb = false): ?LotItem
    {
        $lotItem = $this->getLotItemLoader()->load($lotItemId, $isReadOnlyDb);
        return $lotItem;
    }
}
