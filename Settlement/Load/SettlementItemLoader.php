<?php
/**
 *
 * SAM-4559: SettlementItem Loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/7/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Load;

use Sam\Core\Constants;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Settlement\Load\Dto\SettlementItemDto;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\SettlementItem\SettlementItemReadRepository;
use Sam\Storage\ReadRepository\Entity\SettlementItem\SettlementItemReadRepositoryCreateTrait;
use SettlementItem;

/**
 * Class SettlementItemLoader
 * @package Sam\Settlement\Load
 */
class SettlementItemLoader extends EntityLoaderBase
{
    use EntityMemoryCacheManagerAwareTrait;
    use SettlementItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $settlementItemId
     * @param bool $isReadOnlyDb
     * @return SettlementItem|null
     */
    public function load(int $settlementItemId, bool $isReadOnlyDb = false): ?SettlementItem
    {
        $settlementItem = $this->prepareRepository($isReadOnlyDb)
            ->filterId($settlementItemId)
            ->loadEntity();
        return $settlementItem;
    }

    /**
     * Return active settlement items considering lot order
     *
     * @param int $settlementId settlement.id
     * @param bool $isReadOnlyDb
     * @return SettlementItem[]
     */
    public function loadBySettlementId(int $settlementId, bool $isReadOnlyDb = false): array
    {
        $lotStatusList = implode(',', Constants\Lot::$availableLotStatuses);
        $settlementItems = $this
            ->prepareRepository($isReadOnlyDb)
            ->extendJoinCondition('auction_lot_item', "AND ali.lot_status_id IN ({$lotStatusList})")
            ->filterSettlementId($settlementId)
            ->joinAuctionOrderBySaleNo()
            ->joinAuctionLotItemOrderByLotNo()
            ->loadEntities();
        return $settlementItems;
    }

    /**
     * Return inactive settlement items considering lot order
     *
     * @param int $settlementId settlement.id
     * @param bool $isReadOnlyDb
     * @return SettlementItem[]
     */
    public function loadInactiveBySettlementId(int $settlementId, bool $isReadOnlyDb = false): array
    {
        $lotStatusList = implode(',', Constants\Lot::$availableLotStatuses);
        $settlementItems = $this->createSettlementItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(false)
            ->extendJoinCondition('auction_lot_item', "AND ali.lot_status_id IN ({$lotStatusList})")
            ->filterSettlementId($settlementId)
            ->joinAuctionOrderBySaleNo()
            ->joinAuctionLotItemOrderByLotNo()
            ->loadEntities();
        return $settlementItems;
    }

    /**
     * Load settlement items and related data, and fill result DTO objects.
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return SettlementItemDto[]
     */
    public function loadDtos(int $settlementId, bool $isReadOnlyDb = false): array
    {
        $select = [
            'a.account_id',
            'a.auction_status_id',
            'a.auction_type',
            'a.end_date',
            'a.event_type',
            'a.name',
            'a.sale_num',
            'a.sale_num_ext',
            'a.start_closing_date',
            'a.test_auction',
            'a.timezone_id',
            'ali.lot_num',
            'ali.lot_num_ext',
            'ali.lot_num_prefix',
            'ali.lot_status_id',
            'ali.quantity',
            'ccfc.level as commission_level',
            'ccfc.name as commission_name',
            'ccff.level as fee_level',
            'ccff.name as fee_name',
            'li.high_estimate',
            'li.item_num',
            'li.item_num_ext',
            'li.low_estimate',
            'li.winning_bidder_id',
            's.consignor_tax',
            's.consignor_tax_comm',
            's.consignor_tax_hp',
            's.consignor_tax_hp_type',
            'si.*',
            'COALESCE(
                ali.quantity_digits, 
                li.quantity_digits, 
                (SELECT lc.quantity_digits
                 FROM lot_category lc
                   INNER JOIN lot_item_category lic ON lc.id = lic.lot_category_id
                 WHERE lic.lot_item_id = li.id
                   AND lc.active = 1
                 ORDER BY lic.id
                 LIMIT 1), 
                (SELECT seta.quantity_digits FROM setting_auction seta WHERE seta.account_id = li.account_id)
            ) as quantity_scale',
        ];
        $lotStatusList = implode(',', Constants\Lot::$availableLotStatuses);
        $rows = $this
            ->prepareRepository($isReadOnlyDb)
            ->extendJoinCondition('auction_lot_item', "AND ali.lot_status_id IN ({$lotStatusList})")
            ->filterSettlementId($settlementId)
            ->joinAuctionOrderBySaleNo()
            ->joinAuctionLotItemOrderByLotNo()
            ->joinCommission()
            ->joinFee()
            ->joinLotItem()
            ->joinSettlement()
            ->select($select)
            ->loadRows();
        $dtos = array_map(static fn(array $row) => SettlementItemDto::new()->fromDbRow($row), $rows);
        return $dtos;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return SettlementItemReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb = false): SettlementItemReadRepository
    {
        $repo = $this->createSettlementItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
        return $repo;
    }
}
