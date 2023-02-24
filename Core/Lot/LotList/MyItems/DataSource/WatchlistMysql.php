<?php

namespace Sam\Core\Lot\LotList\MyItems\DataSource;

use Sam\Core\Constants;
use Sam\Core\Lot\LotList\MyItems\DataSourceMysql;

/**
 * Class WatchlistMysql
 * @package Sam\Core\Lot\LotList\MyItems\DataSource
 */
class WatchlistMysql extends DataSourceMysql
{
    /**
     * Return instance of self
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Initialize parts of filtering query
     * @param array $queryParts
     * @return array
     */
    protected function initializeFilterQueryParts(array $queryParts = []): array
    {
        $this->filterAuctionStatusIds(Constants\Auction::$availableAuctionStatuses);
        $queryParts['from'] = [
            'FROM user_watchlist uw',
            'INNER JOIN auction_lot_item ali'
            . ' ON ali.lot_item_id=uw.lot_item_id'
            . ' AND ali.auction_id=uw.auction_id',
            'INNER JOIN lot_item li ON li.id = ali.lot_item_id',
        ];
        $queryParts['where'] = $this->getBaseConditions();
        $queryParts['where'][] = 'uw.user_id = @UserId';
        $queryParts['join'] = $this->getBaseJoins();

        $queryParts = parent::initializeFilterQueryParts($queryParts);
        return $queryParts;
    }

}
