<?php
/**
 * SAM-6376 : Lot bulk group drop-down rendering
 * https://bidpath.atlassian.net/browse/SAM-6376
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 07, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\BulkGroup\Dropdown\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\AuctionLot\BulkGroup\Dropdown\Load
 * @internal
 */
class DataLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param int[] $skipLotItemIds skip lot items by their ids
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function load(
        int $auctionId,
        array $skipLotItemIds = [],
        bool $isReadOnlyDb = false
    ): array {
        $select = [
            'ali.lot_num',
            'ali.lot_num_ext',
            'ali.lot_num_prefix',
            'li.item_num AS item_num',
            'li.item_num_ext AS item_num_ext',
            'li.name AS lot_name',
        ];
        $repo = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterByMasterRole()
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->select($select);

        if ($skipLotItemIds) {
            $repo->skipLotItemId($skipLotItemIds);
        }
        return $repo->loadRows();
    }
}
