<?php
/**
 * SAM-6740: "Preview in auction" adjustments for lot item preview link
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\PreviewInAuction\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;

/**
 * Class PreviewInAuctionDataLoader
 * @package Sam\View\Admin\Form\LotInfoPanel\PreviewInAuction\Internal\Load
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
     * @param int $filterLotItemId
     * @param bool $isReadOnlyDb
     * @return int - return value of Auction Lot Items count
     */
    public function count(int $filterLotItemId, bool $isReadOnlyDb = false): int
    {
        return $this->prepareAuctionLotItemRepository($filterLotItemId, $isReadOnlyDb)->count();
    }

    /**
     * @param int $filterLotItemId
     * @param bool $isReadOnlyDb
     * @return Dto[] - return values for Auction Lot Items
     */
    public function load(int $filterLotItemId, bool $isReadOnlyDb = false): array
    {
        $dtos = [];
        $rows = $this->prepareAuctionLotItemRepository($filterLotItemId, $isReadOnlyDb)->loadRows();
        foreach ($rows as $row) {
            $dtos[] = Dto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @param int $filterLotItemId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemReadRepository
     */
    public function prepareAuctionLotItemRepository(int $filterLotItemId, bool $isReadOnlyDb = false): AuctionLotItemReadRepository
    {
        $select = [
            'ali.auction_id',
            'ali.lot_status_id',
            'a.sale_num',
            'a.sale_num_ext',
            'a.start_closing_date',
            'a.name'
        ];
        return AuctionLotItemReadRepository::new()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotItemId($filterLotItemId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->select($select);
    }
}
