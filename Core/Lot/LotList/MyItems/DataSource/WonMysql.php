<?php

namespace Sam\Core\Lot\LotList\MyItems\DataSource;

use Sam\Core\Constants;
use Sam\Core\Lot\LotList\MyItems\DataSourceMysql;

/**
 * Class WonMysql
 * @package Sam\Core\Lot\LotList\MyItems\DataSource
 */
class WonMysql extends DataSourceMysql
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
        $this->filterLotStatusIds(Constants\Lot::$wonLotStatuses);
        $queryParts['from'] = [
            'FROM auction_lot_item ali',
            'INNER JOIN lot_item li ON li.id = ali.lot_item_id',
        ];
        $queryParts['join'] = $this->getBaseJoins();
        $queryParts['where'] = $this->getBaseConditions();
        $queryParts['where'][] = 'li.winning_bidder_id = @UserId';

        $queryParts = parent::initializeFilterQueryParts($queryParts);
        return $queryParts;
    }
}
