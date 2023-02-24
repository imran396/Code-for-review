<?php

namespace Sam\View\Admin\Form\LotItemsPanel\Load;

use Sam\Core\Constants;

/**
 * Class DataSourceMysql
 * @package Sam\Core\Lot\LotList\Search
 */
class DataSourceMysql extends \Sam\Core\Lot\LotList\DataSourceMysql
{
    protected string $filterQueryBaseTable = 'lot_item';

    /**
     * Return instance of self
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Define mapping for result fields
     * @param array $queryParts
     * @return array
     */
    protected function initializeFilterQueryParts(array $queryParts = []): array
    {
        // we cannot use ali.lot_status_id condition in where clause, because it filters out un-assigned to auction lots
        $this->dropFilterLotStatusId();

        // Even in inventory we filter by auction, thus we need to join "auction_lot_item" for ali.auction_id
        $queryParts['from'] = [
            'FROM lot_item li',
            'LEFT JOIN auction_lot_item ali ON li.id = ali.lot_item_id'
            . ' AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ')',
        ];
        $queryParts['join'] = $this->getBaseJoins();
        $queryParts['where'] = $this->getBaseConditions();
        // Grouping removes duplicates, because lot item may be assigned to multiple auctions
        $queryParts['group'] = 'li_id';
        $queryParts['select'] = 'SELECT IFNULL(ali.id, 0) `ali_id`, li.id `li_id` ';
        $queryParts['select_count'] = 'SELECT COUNT(li.id) ';

        $queryParts = parent::initializeFilterQueryParts($queryParts);
        return $queryParts;
    }

    protected function initializeAggregatedListQueryParts($queryParts = []): array
    {
        // Grouping removes repeated records because of duplications in invoiced items
        $queryParts['group'] = 'lt.li_id';
        return parent::initializeAggregatedListQueryParts($queryParts);
    }
}
