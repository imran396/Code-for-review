<?php
/**
 * SAM-4636: Refactor under bidders report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-04-19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\UnderBidder\Csv;


/**
 * Class QueryBuilder
 */
class QueryBuilder extends \Sam\Report\UnderBidder\Base\QueryBuilder
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    protected function getSelectClause(): string
    {
        $returnFields = [
            'ali.`lot_num`, ali.`lot_num_ext`, ali.`lot_num_prefix`',
            'ali.`lot_item_id` AS li_id',
            'li.`name` AS li_name',
            'LEFT(li.`name`, 80) AS li_name',
            'ali.`auction_id` AS a_id',
            'a.`auction_type` AS a_type',
            'li.`hammer_price` AS li_hp',
            'li.`winning_bidder_id` AS wb_id',
            'wu.`customer_no` AS wb_cust_no',
            'wu.`username` AS wb_username',
            'wu.`email` AS wb_email',
            'wab.`bidder_num` AS wb_bidder_num',
            'IF(MAX(bt.max_bid)> MAX(bt.bid), MAX(bt.max_bid), MAX(bt.bid)) AS `amount`',
            'bt.`user_id` AS ub_id',
            'u.`customer_no` AS ub_cust_no',
            'u.`username` AS ub_username',
            'u.`email` AS ub_email',
            'ab.`bidder_num` AS ub_bidder_num'
        ];

        $query = '';
        foreach ($returnFields as $returnField) {
            $query .= ($query ? ', ' : '');
            $query .= $returnField;
        }

        return sprintf('SELECT %s ', $query);
    }
}
