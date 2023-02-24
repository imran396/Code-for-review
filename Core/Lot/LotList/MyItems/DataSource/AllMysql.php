<?php

namespace Sam\Core\Lot\LotList\MyItems\DataSource;

use Sam\Core\Constants;
use Sam\Core\Lot\LotList\MyItems\DataSourceMysql;

/**
 * Class AllMysql
 * @package Sam\Core\Lot\LotList\MyItems\DataSource
 */
class AllMysql extends DataSourceMysql
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
     * @param array $queryParts
     * @return array
     */
    protected function initializeFilterQueryParts(array $queryParts = []): array
    {
        $where = ' AND ' . $this->getBaseConditionClause();
        $wonLotStatusList = implode(',', Constants\Lot::$wonLotStatuses);
        $availableLotStatusList = implode(',', Constants\Lot::$availableLotStatuses);
        $availableAuctionStatusList = implode(',', Constants\Auction::$availableAuctionStatuses);

        // @formatter:off
        $queryParts['from'] = [
            'FROM auction_lot_item ali',
            'INNER JOIN lot_item li ON li.id = ali.lot_item_id',
        ];

        /**
         * Won items are defined by winning bidder of lot item.
         */
        $subQueries[] = <<<SQL
    SELECT DISTINCT ali.id ali_id 
    FROM lot_item li 
    INNER JOIN auction_lot_item ali
        ON ali.lot_item_id=li.id
        AND ali.auction_id=li.auction_id
        AND ali.lot_status_id IN ({$wonLotStatusList}) 
    INNER JOIN auction a 
        ON a.id = li.auction_id
        AND a.auction_status_id IN ({$availableAuctionStatusList}) 
    WHERE li.winning_bidder_id = @UserId
    {$where}
SQL;

        /**
         * Consigned items exclude unassigned in comparison with "Consigned Items" page, where we display unassigned.
         */
        $subQueries[] = <<<SQL
    SELECT DISTINCT ali.id ali_id 
    FROM lot_item li 
    INNER JOIN auction_lot_item ali
        ON ali.lot_item_id=li.id
        AND ali.lot_status_id IN ({$availableLotStatusList}) 
    INNER JOIN auction a
        ON a.id=ali.auction_id
        AND a.auction_status_id IN ({$availableAuctionStatusList}) 
    WHERE li.consignor_id = @UserId
    {$where}
SQL;

        /**
         * Items, registered in Watchlist
         */
        $subQueries[] = <<<SQL
    SELECT DISTINCT ali.id ali_id 
    FROM user_watchlist uw 
    INNER JOIN auction_lot_item ali
        ON ali.lot_item_id=uw.lot_item_id
        AND ali.auction_id=uw.auction_id
        AND ali.lot_status_id IN ({$availableLotStatusList}) 
    INNER JOIN auction a
        ON a.id=uw.auction_id
        AND a.auction_status_id IN ({$availableAuctionStatusList}) 
    INNER JOIN lot_item li
        ON li.id=uw.lot_item_id 
    WHERE uw.user_id = @UserId
    {$where}
SQL;

        /**
         * Bidding + Not Won items
         *
         * SAM-9368: Add "Not Won" lot set at "My Items / All Lots" page
         * Optimization for "Not Won" items is possible in context of "All items".
         *
         * "Bidding" means lots where you have placed the bid, but lot is still active.
         * "Not Won" means lots where you have placed the bid, but lot wasn't sold to you.
         * Since this "All lots" query also results with "Won" lots that are sold to you,
         * then we can resolve "Not Won" lots with help of "Bidding" query for any lot status.
         * I.e. if lot has your bid and it has "Sold"/"Received" status, it means lot is either sold to you and it matches "Won" set,
         * or lot is sold to anybody else and it matches "Not Won" set.
         * Auction can have status: Active, Started, Closed, Paused.
         */

        /**
         * BidTransaction describes bids for timed and live/hybrid auctions.
         */
        $subQueries[] = <<<SQL
    SELECT DISTINCT ali.id ali_id 
    FROM bid_transaction bt 
    INNER JOIN auction a
        ON a.id=bt.auction_id
        AND a.auction_status_id IN ({$availableAuctionStatusList}) 
    INNER JOIN auction_lot_item ali
        ON ali.auction_id=bt.auction_id
        AND ali.lot_item_id=bt.lot_item_id
        AND ali.lot_status_id IN ({$availableLotStatusList}) 
    INNER JOIN lot_item li
        ON li.id=bt.lot_item_id 
    WHERE bt.user_id = @UserId
    {$where}
SQL;

        /**
         * AbsenteeBid describes pre-sale bids for live/hybrid auctions.
         */
        $subQueries[] = <<<SQL
    SELECT DISTINCT ali.id ali_id 
    FROM absentee_bid ab 
    INNER JOIN auction a
        ON a.id=ab.auction_id
        AND a.auction_status_id IN ({$availableAuctionStatusList}) 
    INNER JOIN auction_lot_item ali
        ON ali.auction_id=ab.auction_id
        AND ali.lot_item_id=ab.lot_item_id
        AND ali.lot_status_id IN ({$availableLotStatusList})  
    INNER JOIN lot_item li
        ON li.id=ab.lot_item_id 
    WHERE ab.user_id = @UserId
    {$where}
SQL;

        /**
         * TimedOnlineOfferBid describes specific timed auction bids with "offer" type.
         */
        $subQueries[] = <<<SQL
    SELECT DISTINCT ali.id ali_id 
    FROM timed_online_offer_bid toob 
    INNER JOIN auction_lot_item ali
        ON ali.id=toob.auction_lot_item_id
        AND ali.lot_status_id IN ({$availableLotStatusList})  
    INNER JOIN auction a
        ON a.id=ali.auction_id
        AND a.auction_status_id IN ({$availableAuctionStatusList}) 
    INNER JOIN lot_item li
        ON li.id=ali.lot_item_id 
    WHERE toob.user_id = @UserId
    {$where}
SQL;

        $unionizedQuery = implode(" UNION DISTINCT ", $subQueries);

        $queryParts['from'][] = <<<SQL
INNER JOIN (
    {$unionizedQuery}
) lots ON lots.ali_id = ali.id
SQL;
        // @formatter:on

        $queryParts['join'] = $this->getBaseJoins();

        $queryParts = parent::initializeFilterQueryParts($queryParts);
        return $queryParts;
    }

}
