<?php
/**
 * SAM-8724: Projector console - Extract image response building logic to separate service
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 23, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Projector\Image\Internal\Load;

use Auction;
use Sam\Auction\Load\AuctionLoader;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Image\Load\LotImageLoader;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepository;

/**
 * Class DataProvider
 * @package Sam\Rtb\Projector\Image\Internal\Load
 */
class DataProvider extends CustomizableClass
{
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
     * @param bool $isReadOnlyDb
     * @return Auction|null
     */
    public function loadAuction(int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return AuctionLoader::new()->load($auctionId, $isReadOnlyDb);
    }

    /**
     * Load lot items array for auction
     * @param array $lotItemIds
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function load(array $lotItemIds, int $auctionId, bool $isReadOnlyDb = false): array
    {
        return $this->prepareLotItemRepository($lotItemIds, $auctionId, $isReadOnlyDb)
            ->loadRows();
    }

    /**
     * Load array of images for lot
     * @param int|null $lotItemId null leads to empty array result
     * @return \LotImage[]
     */
    public function loadForLot(?int $lotItemId): array
    {
        return LotImageLoader::new()->loadForLot($lotItemId, [], true);
    }

    /**
     * Load default image for lot
     * @param int|null $lotItemId null leads to null result
     * @return \LotImage|null
     */
    public function loadDefaultForLot(?int $lotItemId): ?\LotImage
    {
        return LotImageLoader::new()->loadDefaultForLot($lotItemId, true);
    }

    /**
     * @param array $lotItemIds
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return LotItemReadRepository
     */
    protected function prepareLotItemRepository(array $lotItemIds, int $auctionId, bool $isReadOnlyDb): LotItemReadRepository
    {
        $select = [
            'ali.lot_item_id AS lot_item_id',
            'ali.lot_num AS lot_num',
            'ali.lot_num_ext AS lot_num_ext',
            'ali.lot_num_prefix AS lot_num_prefix',
            'li.name AS lot_item_name',
            'li.account_id AS lot_item_account_id'
        ];
        return LotItemReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinAuctionLotItemFilterAuctionId($auctionId)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->filterId($lotItemIds)
            ->select($select);
    }
}
