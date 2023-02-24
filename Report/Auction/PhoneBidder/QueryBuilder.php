<?php
/**
 * Query builder for phone bidders
 *
 * SAM-4617: Refactor Auction Bids report
 *
 * @author        Vahagn Hovsepyan
 * @since         Dec 17, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Report\Auction\PhoneBidder;

use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class QueryBuilder
 */
class QueryBuilder extends CustomizableClass
{
    use DbConnectionTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $params
     * @return array
     */
    public function getQueryParts(array $params = []): array
    {
        $n = "\n";

        $minLotNum = isset($params['MinLot']) ? (int)$params['MinLot'] : null;
        $maxLotNum = isset($params['MaxLot']) ? (int)$params['MaxLot'] : null;

        $queryParts = [
            'select_count' => 'SELECT COUNT(1) AS lot_total ',

            'select' => 'SELECT ' . $n .
                'ab.id, ' . $n .
                'ali.lot_item_id AS lot_id, ' . $n .
                'ali.lot_num_prefix, ' . $n .
                'ali.lot_num, ' . $n .
                'ali.lot_num_ext, ' . $n .
                'ab.user_id, ' . $n .
                'aub.bidder_num, ' . $n .
                'ui.first_name, ' . $n .
                'ui.last_name, ' . $n .
                'ui.phone AS iphone, ' . $n .
                'ub.phone AS bphone, ' . $n .
                'us.phone AS sphone, ' . $n .
                'ab.bid_type AS bid_type, ' . $n .
                'ab.assigned_clerk AS assigned_clerk ' . $n,

            'from_count' => 'FROM auction_lot_item ali ' . $n .
                'LEFT JOIN absentee_bid ab ON ali.auction_id = ab.auction_id AND ali.lot_item_id = ab.lot_item_id ' . $n,

            'from' => 'FROM auction_lot_item ali ' . $n .
                'LEFT JOIN absentee_bid ab ON ali.auction_id = ab.auction_id AND ali.lot_item_id = ab.lot_item_id ' . $n .
                'LEFT JOIN auction_bidder aub ON ab.auction_id = aub.auction_id AND ab.user_id = aub.user_id ' . $n .
                'LEFT JOIN user_info ui ON ui.user_id = ab.user_id ' . $n .
                'LEFT JOIN user_billing ub ON ub.user_id = ab.user_id ' . $n .
                'LEFT JOIN user_shipping us ON us.user_id = ab.user_id ' . $n,

            'where_count' => 'WHERE ali.auction_id = ' . $this->escape($params['AuctionId']) . ' ' . $n .
                'AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ') ' . $n,

            'where' => 'WHERE ali.auction_id = ' . $this->escape($params['AuctionId']) . ' ' . $n .
                'AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ') ' . $n,

            'order' => 'ORDER BY ' . $n .
                'ali.lot_num_prefix,ali.lot_num,ali.lot_num_ext,IF(ab.placed_on is null,ab.created_on,ab.placed_on) ' . $n,

            'limit' => '',
        ];

        if (isset($params['Limit'], $params['Offset'])) {
            $offset = (int)$params['Offset'];
            $limit = (int)$params['Limit'];
            $queryParts['limit'] = "LIMIT {$offset}, {$limit}" . $n;
        }

        $filterCond =
            (isset($params['BidderId']) && (int)$params['BidderId'] !== 0
                ? 'AND ab.user_id=' . $this->escape($params['BidderId']) . ' ' . $n : '') .
            (isset($params['Clerk']) && (string)$params['Clerk'] !== ''
                ? 'AND ab.assigned_clerk=' . $this->escape($params['Clerk']) . ' ' . $n : '') .
            ($minLotNum > 0 ? 'AND ali.lot_num >= ' . $this->escape($minLotNum) . ' ' . $n : '') .
            ($maxLotNum > 0 ? 'AND ali.lot_num <= ' . $this->escape($maxLotNum) . ' ' . $n : '') .
            (isset($params['UnassignedOnly']) && (int)$params['UnassignedOnly'] === 1
                ? 'AND ab.assigned_clerk = \'--\' ' . $n : '') .
            (isset($params['AllLots']) && (int)$params['AllLots'] === 0 ? 'AND ab.bid_type = ' . Constants\Bid::ABT_PHONE . ' ' . $n : '');

        $queryParts['where_count'] .= $filterCond;
        $queryParts['where'] .= $filterCond;

        return $queryParts;
    }
}
