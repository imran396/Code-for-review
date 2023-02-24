<?php
/**
 *
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\LotNo\EmptyNo;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class EmptyLotNoLoader
 * @package ${NAMESPACE}
 */
class EmptyLotNoLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use LotRendererAwareTrait;

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
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadInfos(int $auctionId, bool $isReadOnlyDb = false): array
    {
        $select = [
            "li.name as lot_name",
            "a.test_auction",
            "li.item_num",
            "li.item_num_ext",
            "li.id as lot_item_id"
        ];
        $rows = $this->load($auctionId, $select, $isReadOnlyDb);
        $infos = [];
        foreach ($rows as $row) {
            $infos[] = [
                'lot_item_id' => (int)$row['lot_item_id'],
                'item_no' => $this->getLotRenderer()->makeItemNo($row['item_num'], $row['item_num_ext']),
                'lot_name' => $this->getLotRenderer()->makeName($row['lot_name'], (bool)$row['test_auction']),
            ];
        }
        return $infos;
    }

    /**
     * Return an array with duplicate lot numbers within an auction
     *
     * @param int $auctionId auction_lot_item.auction_id
     * @param array $select
     * @param bool $isReadOnlyDb
     * @return array
     */
    protected function load(int $auctionId, array $select, bool $isReadOnlyDb = false): array
    {
        $rows = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotNum(null)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAuction()
            ->joinLotItemFilterActive(true)
            ->select($select)
            ->loadRows();
        return $rows;
    }
}
