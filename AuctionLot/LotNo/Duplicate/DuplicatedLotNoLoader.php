<?php
/**
 * SAM-6373: Refactor duplicated lot# detection at Auction Lot List page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\LotNo\Duplicate;

use Auction;
use AuctionLotItem;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class DuplicatedLotNoLoader
 * @package Sam\View\Admin\Form\AuctionLotListForm
 */
class DuplicatedLotNoLoader extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use LotRendererAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * Load duplicated full lot#s
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadFullLotNos(int $auctionId, bool $isReadOnlyDb = false): array
    {
        $select = [
            "ali.lot_num",
            "ali.lot_num_prefix",
            "ali.lot_num_ext",
        ];
        $rows = $this->load($auctionId, $select, $isReadOnlyDb);
        $fullLotNos = [];
        foreach ($rows as $row) {
            $fullLotNos[] = $this->getLotRenderer()->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']);
        }
        return $fullLotNos;
    }

    /**
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadInfos(int $auctionId, bool $isReadOnlyDb = false): array
    {
        $select = [
            "ali.lot_num",
            "ali.lot_num_prefix",
            "ali.lot_num_ext",
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
                'lot_no' => $this->getLotRenderer()->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']),
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
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->groupByLotNum()
            ->groupByLotNumExt()
            ->groupByLotNumPrefix()
            ->having('COUNT(1) > 1')
            ->joinAuction()
            ->joinLotItemFilterActive(true)
            ->select($select)
            ->skipLotNum(null)
            ->loadRows();
        return $rows;
    }

    /**
     * @param Auction|null $auction
     * @param AuctionLotItem|null $auctionLot
     * @param bool $isReadOnlyDb
     * @return array<array{id: string, name: string}>
     */
    public function loadDuplicates(
        ?Auction $auction,
        ?AuctionLotItem $auctionLot,
        bool $isReadOnlyDb = false
    ): array {
        if (
            !$auction
            || !$auctionLot
        ) {
            return [];
        }

        return $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->select(['li.id', 'li.name'])
            ->filterAuctionId($auction->Id)
            ->filterLotNum($auctionLot->LotNum)
            ->filterLotNumExt($auctionLot->LotNumExt)
            ->filterLotNumPrefix($auctionLot->LotNumPrefix)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAuction()
            ->joinLotItemFilterActive(true)
            ->skipLotItemId($auctionLot->LotItemId)
            ->loadRows();
    }
}
