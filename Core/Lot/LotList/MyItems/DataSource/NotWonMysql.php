<?php

namespace Sam\Core\Lot\LotList\MyItems\DataSource;

use Sam\Core\Constants;
use Sam\Core\Lot\LotList\MyItems\DataSourceMysql;

/**
 * Class NotWonMysql
 * @package Sam\Core\Lot\LotList\MyItems\DataSource
 */
class NotWonMysql extends DataSourceMysql
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
        $deletedAuctions = Constants\Auction::AS_DELETED;
        $closedAuctions = Constants\Auction::AS_CLOSED;
        $activeLot = Constants\Lot::LS_ACTIVE;
        $unsoldSoldLotStatusList = implode(', ', [Constants\Lot::LS_UNSOLD, Constants\Lot::LS_SOLD]);
        $queryParts['from'] = [
            'FROM auction_lot_item ali',
            'INNER JOIN lot_item li ON li.id = ali.lot_item_id',
        ];
        $queryParts['from'][] = <<<SQL
INNER JOIN (
    SELECT DISTINCT ali.id ali_id 
    FROM bid_transaction bt 
    INNER JOIN auction a
        ON a.id=bt.auction_id
        AND a.auction_status_id != {$deletedAuctions} #exclude deleted auctions
    INNER JOIN auction_lot_item ali
        ON ali.auction_id=bt.auction_id
        AND ali.lot_item_id=bt.lot_item_id
        #lots sold/ unsold
        AND (ali.lot_status_id IN ({$unsoldSoldLotStatusList}) 
        # or auction closed, but lot still active
        OR (a.auction_status_id = {$closedAuctions} AND ali.lot_status_id = {$activeLot}))
    INNER JOIN lot_item li
        ON li.id=bt.lot_item_id
        AND (li.winning_bidder_id IS NULL OR li.winning_bidder_id != bt.user_id) 
    WHERE bt.user_id = @UserId
    $where
    UNION DISTINCT 
    SELECT DISTINCT ali.id ali_id 
    FROM absentee_bid ab 
    INNER JOIN auction a
        ON a.id=ab.auction_id
        AND a.auction_status_id != {$deletedAuctions}
    INNER JOIN auction_lot_item ali
        ON ali.auction_id=ab.auction_id
        AND ali.lot_item_id=ab.lot_item_id
        AND (ali.lot_status_id IN ({$unsoldSoldLotStatusList}) OR (a.auction_status_id = {$closedAuctions} AND ali.lot_status_id = {$activeLot}))
    INNER JOIN lot_item li
        ON li.id=ab.lot_item_id
        AND (li.winning_bidder_id IS NULL OR li.winning_bidder_id != ab.user_id) 
    WHERE ab.user_id=@UserId
    $where
) lots ON lots.ali_id = ali.id
SQL;
        $queryParts['join'] = $this->getBaseJoins();

        $queryParts = parent::initializeFilterQueryParts($queryParts);
        return $queryParts;
    }

}
