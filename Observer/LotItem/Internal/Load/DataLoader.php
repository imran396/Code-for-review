<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\LotItem\Internal\Load;

use LotItem;
use Sam\AuctionLot\Order\Reorder\Auction\Load\DataLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataLoader
 * @package Sam\Observer\LotItem\Internal\Load
 * @internal
 */
class DataLoader extends CustomizableClass
{
    use DataLoaderAwareTrait;

    protected static array $auctionIdsCache = [];
    protected static ?int $cacheLotItemId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotItem $lotItem
     * @return array
     */
    public function loadLotItemAuctionIds(LotItem $lotItem): array
    {
        if (static::$cacheLotItemId !== $lotItem->Id) {
            static::$auctionIdsCache = $this->getDataLoader()->loadAuctionIdsByLotItemId($lotItem->Id);
            static::$cacheLotItemId = $lotItem->Id;
        }
        return static::$auctionIdsCache;
    }
}
