<?php

namespace Sam\Core\Lot\LotList\MyItems\DataSource;

use Sam\Core\Constants;
use Sam\Core\Lot\LotList\MyItems\DataSourceMysql;

/**
 * Class ConsignedMysql
 * @package Sam\Core\Lot\LotList\MyItems\DataSource
 */
class ConsignedMysql extends DataSourceMysql
{
    protected bool $isEnabledConsiderOptionHideUnsoldLots = false; // SAM-2877 (exception for public site)

    /**
     * Return instance of self
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $queryParts
     * @return array
     */
    protected function initializeFilterQueryParts(array $queryParts = []): array
    {
        $this->dropFilterLotStatusId();
        $queryParts['from'] = [
            'FROM lot_item li',
            'LEFT JOIN auction_lot_item ali ON li.id = ali.lot_item_id '
            . 'AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ')',
        ];
        $queryParts['join'] = $this->getBaseJoins();
        $queryParts['where'] = $this->getBaseConditions();
        $queryParts['where'][] = 'li.consignor_id = @UserId';
        $queryParts['select'] = 'SELECT IFNULL(ali.id, 0) `ali_id`, li.id `li_id` ';
        $queryParts['select_count'] = 'SELECT COUNT(li.id) ';

        $queryParts = parent::initializeFilterQueryParts($queryParts);
        return $queryParts;
    }

    /**
     * Initialize aggregated list query parts
     *
     * @param array $queryParts
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function initializeAggregatedListQueryParts(array $queryParts = []): array
    {
        if (!isset($queryParts['group'])) {
            //$queryParts['group'] = '';    // we don't need to group in ideal situation
            $queryParts['group'] = 'lt.li_id';    // group results because of fetched duplicated lots, that happen because of bug with duplicated invoices and so on
        }
        $aggregatedListQueryParts = parent::initializeAggregatedListQueryParts($queryParts);
        return $aggregatedListQueryParts;
    }
}
