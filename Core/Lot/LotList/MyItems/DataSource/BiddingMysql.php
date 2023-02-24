<?php

namespace Sam\Core\Lot\LotList\MyItems\DataSource;

use Sam\Core\Constants;
use Sam\Core\Lot\LotList\MyItems\DataSourceMysql;

/**
 * Class BiddingMysql
 * @package Sam\Core\Lot\LotList\MyItems\DataSource
 */
class BiddingMysql extends DataSourceMysql
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
        $where = ' AND ' . $this->getBaseConditionClause();
        $openAuctionStatusList = implode(',', Constants\Auction::$openAuctionStatuses);
        $runningLiveAuctionStatusList = implode(', ', [Constants\Auction::AS_STARTED, Constants\Auction::AS_PAUSED]);
        $lsActive = Constants\Lot::LS_ACTIVE;
        $queryParts['from'] = [
            'FROM auction_lot_item ali',
            'INNER JOIN lot_item li ON li.id = ali.lot_item_id',
        ];
        $queryParts['from'][] = <<<SQL
INNER JOIN (
    SELECT DISTINCT ali.id ali_id 
    FROM bid_transaction bt 
    INNER JOIN auction a
        ON a.id = bt.auction_id
        AND a.auction_status_id IN ({$runningLiveAuctionStatusList}) 
    INNER JOIN auction_lot_item ali
        ON ali.auction_id = bt.auction_id
        AND ali.lot_item_id = bt.lot_item_id
        AND ali.lot_status_id = {$lsActive} 
    INNER JOIN lot_item li
        ON li.id = bt.lot_item_id 
    WHERE NOT bt.deleted 
        AND bt.user_id = @UserId
    {$where}
    UNION DISTINCT 
    SELECT DISTINCT ali.id ali_id 
    FROM absentee_bid ab 
    INNER JOIN auction a
        ON a.id=ab.auction_id
        AND a.auction_status_id IN ({$openAuctionStatusList}) 
    INNER JOIN auction_lot_item ali
        ON ali.auction_id=ab.auction_id
        AND ali.lot_item_id=ab.lot_item_id
        AND ali.lot_status_id = {$lsActive} 
    INNER JOIN lot_item li
        ON li.id = ab.lot_item_id
    WHERE ab.user_id=@UserId 
    {$where} 
    UNION DISTINCT 
    SELECT DISTINCT ali.id ali_id 
    FROM timed_online_offer_bid toob 
    INNER JOIN auction_lot_item ali
        ON ali.id=toob.auction_lot_item_id
        AND ali.lot_status_id = {$lsActive} 
    INNER JOIN auction a
        ON a.id=ali.auction_id
        AND a.auction_status_id IN ({$openAuctionStatusList}) 
    INNER JOIN lot_item li
        ON li.id=ali.lot_item_id 
    WHERE toob.user_id = @UserId
    {$where}
) lots ON lots.ali_id = ali.id
SQL;
        $queryParts['join'] = $this->getBaseJoins();

        $queryParts = parent::initializeFilterQueryParts($queryParts);
        return $queryParts;
    }

}
