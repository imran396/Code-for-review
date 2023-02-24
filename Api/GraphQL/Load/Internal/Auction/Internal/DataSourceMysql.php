<?php
/**
 * SAM-10467: Implement a GraphQL nested structure for a single auction
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\Auction\Internal;

/**
 * Class DataSourceMysql
 * @package Sam\Api\GraphQL\Load\Internal\Auction\Internal
 */
class DataSourceMysql extends \Sam\Auction\AuctionList\DataSourceMysql
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    protected function initResultFieldsMapping(): void
    {
        parent::initResultFieldsMapping();

        $this->resultFieldsMapping['allow_force_bid'] = [
            'select' => 'a.allow_force_bid',
        ];
        $this->resultFieldsMapping['reverse'] = [
            'select' => 'a.reverse',
        ];
        $this->resultFieldsMapping['currency_id'] = [
            'select' => 'IFNULL (a.currency, setsys.primary_currency_id)',
            'join' => ['setting_system'],
        ];
        $this->resultFieldsMapping['extend_time'] = [
            'select' => 'a.extend_time',
        ];
        $this->resultFieldsMapping['extend_all'] = [
            'select' => 'a.extend_all',
        ];
        $this->resultFieldsMapping['lot_start_gap_time'] = [
            'select' => 'a.lot_start_gap_time',
        ];
        $this->resultFieldsMapping['next_bid_button'] = [
            'select' => 'a.next_bid_button',
        ];
        $this->resultFieldsMapping['notify_absentee_bidders'] = [
            'select' => 'a.notify_absentee_bidders',
        ];
        $this->resultFieldsMapping['reserve_not_met_notice'] = [
            'select' => 'a.reserve_not_met_notice',
        ];
        $this->resultFieldsMapping['reserve_met_notice'] = [
            'select' => 'a.reserve_met_notice',
        ];
        $this->resultFieldsMapping['require_lot_change_confirmation'] = [
            'select' => 'a.require_lot_change_confirmation',
        ];
        $this->resultFieldsMapping['absentee_bids_display'] = [
            'select' => 'a.absentee_bids_display',
        ];
        $this->resultFieldsMapping['bidding_paused'] = [
            'select' => 'a.bidding_paused',
        ];
        $this->resultFieldsMapping['extend_all_start_closing_date'] = [
            'select' => 'IFNULL(adyn.extend_all_start_closing_date, a.start_closing_date)',
            'join' => 'auction_dynamic',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function initJoinsMapping(): void
    {
        parent::initJoinsMapping();
        // @formatter:off
        $this->joinsMapping['auction_dynamic'] =
            'LEFT JOIN `auction_dynamic` adyn ' .
                'ON adyn.auction_id = a.id';
    }
}
