<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\Lot\Internal;

use Sam\Core\Constants;

/**
 * Class LotItemDataSourceMysql
 * @package Sam\Api\GraphQL\Load\Internal\Lot\Internal
 */
class LotItemDataSourceMysql extends \Sam\Core\Lot\LotList\DataSourceMysql
{
    protected string $filterQueryBaseTable = 'lot_item';

    /**
     * Class instantiation method
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
        $queryParts['select'] = 'SELECT IFNULL(ali.id, 0) `ali_id`, li.id `li_id` ';
        $queryParts['join'] = $this->getBaseJoins();
        $queryParts['where'] = $this->getBaseConditions();
        // Grouping removes duplicates, because lot item may be assigned to multiple auctions
        $queryParts['group'] = 'li_id';

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
