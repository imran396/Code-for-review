<?php
/**
 * Placeholders related data for translation and db access
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 * SAM-6595: Templated-content building - simplify module structure for v3.5
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 9, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\Base;

use Sam\Core\Constants;

/**
 * Class ConfigManager
 * @package Sam\Details
 */
class ConfigManager extends \Sam\Details\Core\ConfigManager
{
    /**
     * @var array<array<string, array{lang?: string[], select: string[]|string, available?: bool, observable?: bool}>>
     */
    protected array $keysConfig = [
        Constants\Placeholder::REGULAR => [
            Constants\LotDetail::PL_ACCOUNT_ID => [
                'lang' => ['LD_ACCOUNT_ID', 'lot_details'],
                'select' => ['account_id'],
            ],
            Constants\LotDetail::PL_ACCOUNT_COMPANY => [
                'lang' => ['LD_ACCOUNT_COMPANY', 'lot_details'],
                'select' => ['account_company_name'],
            ],
            Constants\LotDetail::PL_ACCOUNT_NAME => [
                'lang' => ['LD_ACCOUNT_NAME', 'lot_details'],
                'select' => ['account_name'],
            ],
            Constants\LotDetail::PL_AUCTION_ID => [
                'lang' => ['LD_AUCTION_ID', 'lot_details'],
                'select' => ['auction_id'],
            ],
            Constants\LotDetail::PL_AUCTION_EMAIL => [
                'lang' => ['LD_AUCTION_EMAIL', 'lot_details'],
                'select' => ['auction_email'],
            ],
            Constants\LotDetail::PL_AUCTION_EVENT_ID => [
                'lang' => ['LD_AUCTION_EVENT_ID', 'lot_details'],
                'select' => ['auction_event_id'],
            ],
            Constants\LotDetail::PL_AUCTION_NAME => [
                'lang' => ['LD_AUCTION_NAME', 'lot_details'],
                'select' => ['auction_name', 'auction_test'],
            ],
            Constants\LotDetail::PL_AUCTION_SEO_META_DESCRIPTION => [
                'select' => ['auction_seo_meta_description'],
            ],
            Constants\LotDetail::PL_AUCTION_META_SEO_KEYWORDS => [
                'select' => ['auction_seo_meta_keywords'],
            ],
            Constants\LotDetail::PL_AUCTION_SEO_META_TITLE => [
                'select' => ['auction_seo_meta_title'],
            ],
            Constants\LotDetail::PL_AUCTION_TYPE => [
                'lang' => ['LD_AUCTION_TYPE', 'lot_details'],
                'select' => ['auction_type'],
            ],
            Constants\LotDetail::PL_AUCTION_TYPE_LANG => [
                'select' => ['auction_type'],
            ],
            Constants\LotDetail::PL_BIDS => [
                'lang' => ['LD_BIDS', 'lot_details'],
                'select' => ['bid_count'],
            ],
            Constants\LotDetail::PL_BULK_PIECE_INFO => [
                'select' => [
                    'account_id',
                    'alid',
                    'auction_type',
                    'bulk_master_id',
                    'bulk_master_auction_id',
                    'bulk_master_lot_item_id',
                    'bulk_master_name',
                    'bulk_master_lot_num',
                    'bulk_master_lot_num_ext',
                    'bulk_master_lot_num_prefix',
                    'bulk_master_seo_url',
                    'is_bulk_master',
                ],
            ],
            Constants\LotDetail::PL_BUYERS_PREMIUM => [
                'lang' => ['LD_BUYERS_PREMIUM', 'lot_details'],
                'select' => ['id', 'winning_auction_id', 'winner_user_id'],
            ],
            Constants\LotDetail::PL_CHANGES => [
                'lang' => ['LD_CHANGES', 'lot_details'],
                'select' => ['changes'],
            ],
            Constants\LotDetail::PL_CONSIGNOR => [
                'lang' => ['LD_CONSIGNOR', 'lot_details'],
                'select' => ['consignor_username'],
            ],
            Constants\LotDetail::PL_DESCRIPTION => [
                'lang' => ['LD_DESCRIPTION', 'lot_details'],
                'select' => ['lot_desc'],
            ],
            Constants\LotDetail::PL_FEATURED => [
                'lang' => ['LD_FEATURED', 'lot_details'],
                'select' => ['sample_lot'],
            ],
            Constants\LotDetail::PL_GENERAL_NOTE => [
                'lang' => ['LD_GENERAL_NOTE', 'lot_details'],
                'select' => ['general_note'],
            ],
            Constants\LotDetail::PL_ID => [
                'lang' => ['LD_ID', 'lot_details'],
                'select' => ['id'],
            ],
            Constants\LotDetail::PL_INTERNET_BID => [
                'lang' => ['LD_INTERNET_BID', 'lot_details'],
                'select' => ['internet_bid'],
            ],
            Constants\LotDetail::PL_ITEM_NO => [
                'lang' => ['LD_ITEM_NO', 'lot_details'],
                'select' => ['item_num', 'item_num_ext'],
            ],
            Constants\LotDetail::PL_LOCATION => [
                'lang' => ['LD_LOCATION', 'lot_details'],
                'select' => [
                    'location_address',
                    'location_name',
                    'location_country',
                    'location_county',
                    'location_state',
                    'location_city',
                    'location_zip'
                ],
            ],
            Constants\LotDetail::PL_LOCATION_ADDRESS => [
                'lang' => ['LD_LOCATION_ADDRESS', 'lot_details'],
                'select' => ['location_address'],
            ],
            Constants\LotDetail::PL_LOCATION_CITY => [
                'lang' => ['LD_LOCATION_CITY', 'lot_details'],
                'select' => ['location_city'],
            ],
            Constants\LotDetail::PL_LOCATION_COUNTRY => [
                'lang' => ['LD_LOCATION_COUNTRY', 'lot_details'],
                'select' => ['location_country'],
            ],
            Constants\LotDetail::PL_LOCATION_COUNTY => [
                'lang' => ['LD_LOCATION_COUNTY', 'lot_details'],
                'select' => ['location_county'],
            ],
            Constants\LotDetail::PL_LOCATION_NAME => [
                'lang' => ['LD_LOCATION_NAME', 'lot_details'],
                'select' => ['location_name'],
            ],
            Constants\LotDetail::PL_LOCATION_STATE => [
                'lang' => ['LD_LOCATION_STATE', 'lot_details'],
                'select' => ['location_state'],
            ],
            Constants\LotDetail::PL_LOCATION_ZIP => [
                'lang' => ['LD_LOCATION_ZIP', 'lot_details'],
                'select' => ['location_zip'],
            ],
            Constants\LotDetail::PL_LOT_NO => [  // renamed from 'lot'
                'lang' => ['LD_LOT_NO', 'lot_details'],
                'select' => ['lot_num', 'lot_num_ext', 'lot_num_prefix'],
            ],
            Constants\LotDetail::PL_NAME => [
                'lang' => ['LD_NAME', 'lot_details'],
                'select' => ['lot_name', 'auction_test'],
            ],
            Constants\LotDetail::PL_NO_TAX_OUTSIDE_STATE => [
                'lang' => ['LD_NO_TAX_OUTSIDE_STATE', 'lot_details'],
                'select' => ['no_tax_oos'],
            ],
            Constants\LotDetail::PL_NOTE_TO_CLERK => [
                'lang' => ['LD_NOTE_TO_CLERK', 'lot_details'],
                'select' => ['note_to_clerk'],
            ],
            Constants\LotDetail::PL_QUANTITY => [
                'lang' => ['LD_QUANTITY', 'lot_details'],
                'select' => [
                    'account_id',
                    'ap_main_display_quantity',
                    'qty',
                    'qty_scale',
                    'qty_x_money',
                ],
            ],
            Constants\LotDetail::PL_RETURNED => [
                'lang' => ['LD_RETURNED', 'lot_details'],
                'select' => ['returned'],
            ],
            Constants\LotDetail::PL_SALE_NO => [
                'lang' => ['LD_SALE_NO', 'lot_details'],
                'select' => ['sale_num', 'sale_num_ext'],
            ],
            Constants\LotDetail::PL_SALE_SOLD_IN => [
                'lang' => ['LD_SALE_SOLD_IN', 'lot_details'],
                'select' => ['auction_sold_name', 'auction_sold_test_auction'],
            ],
            Constants\LotDetail::PL_SALE_SOLD_IN_NO => [
                'lang' => ['LD_SALE_SOLD_IN_NO', 'lot_details'],
                'select' => ['auction_sold_sale_num', 'auction_sold_sale_num_ext'],
            ],
            Constants\LotDetail::PL_SALES_TAX => [
                'lang' => ['LD_SALES_TAX', 'lot_details'],
                'select' => ['sales_tax'],
            ],
            Constants\LotDetail::PL_SEO_META_DESCRIPTION => [
                'select' => ['seo_meta_description'],
            ],
            Constants\LotDetail::PL_SEO_META_KEYWORDS => [
                'select' => ['seo_meta_keywords'],
            ],
            Constants\LotDetail::PL_SEO_META_TITLE => [
                'select' => ['seo_meta_title'],
            ],
            Constants\LotDetail::PL_SEO_URL => [
                'select' => ['seo_url'],
            ],
            Constants\LotDetail::PL_STATUS => [
                'lang' => ['LD_STATUS', 'lot_details'],
                'select' => ['lot_status_id', 'auction_reverse'],
            ],
            Constants\LotDetail::PL_STATUS_LANG => [
                'select' => ['account_id', 'lot_status_id', 'auction_reverse'],
            ],
            Constants\LotDetail::PL_TIME_LEFT => [
                'lang' => ['LD_TIME_LEFT', 'lot_details'],
                'select' => ['account_id', 'auction_type', 'auction_status_id', 'lot_status_id', 'seconds_before', 'seconds_left'],
            ],
            Constants\LotDetail::PL_VIEWS => [
                'lang' => ['LD_VIEWS', 'lot_details'],
                'select' => ['view_count'],
            ],
            Constants\LotDetail::PL_WARRANTY => [
                'lang' => ['LD_WARRANTY', 'lot_details'],
                'select' => ['warranty'],
            ],
            Constants\LotDetail::PL_WINNING_BIDDER_BIDDERNO => [
                'lang' => ['LD_WINNING_BIDDER_BIDDERNO', 'lot_details'],
                'select' => ['winner_bidder_num'],
            ],
            Constants\LotDetail::PL_WINNING_BIDDER_CUSTNO => [
                'lang' => ['LD_WINNING_BIDDER_CUSTNO', 'lot_details'],
                'select' => ['winner_customer_no'],
            ],
            Constants\LotDetail::PL_WINNING_BIDDER_ID => [
                'lang' => ['LD_WINNING_BIDDER_ID', 'lot_details'],
                'select' => ['winner_user_id'],
            ],
            Constants\LotDetail::PL_WINNING_BIDDER_USERNAME => [
                // 'observable' => false, // TODO: when we will pre-build and cache lot details content, then we should skip this field, because it must be rendered on-the-fly according to SAM-10235.
                'lang' => ['LD_WINNING_BIDDER_USERNAME', 'lot_details'],
                'select' => ['winner_username'],
            ],
        ],
        Constants\Placeholder::MONEY => [
            Constants\LotDetail::PL_ASKING_BID => [
                'lang' => ['LD_ASKING_BID', 'lot_details'],
                'select' => ['asking_bid', 'currency'],
            ],
            Constants\LotDetail::PL_BUY_NOW_PRICE => [
                'lang' => ['LD_BUY_NOW_PRICE', 'lot_details'],
                'select' => ['buy_amount', 'currency'],
            ],
            Constants\LotDetail::PL_COST => [
                'lang' => ['LD_COST', 'lot_details'],
                'select' => ['cost', 'currency'],
            ],
            Constants\LotDetail::PL_CURRENT_BID => [
                'lang' => ['LD_CURRENT_BID', 'lot_details'],
                'select' => ['current_bid', 'currency'],
            ],
            Constants\LotDetail::PL_HAMMER => [
                'lang' => ['LD_HAMMER', 'lot_details'],
                'select' => ['hammer_price', 'currency'],
            ],
            Constants\LotDetail::PL_HIGH_ESTIMATE => [
                'lang' => ['LD_HIGH_ESTIMATE', 'lot_details'],
                'select' => ['high_estimate', 'currency'],
            ],
            Constants\LotDetail::PL_LOW_ESTIMATE => [
                'lang' => ['LD_LOW_ESTIMATE', 'lot_details'],
                'select' => ['low_estimate', 'currency'],
            ],
            Constants\LotDetail::PL_REPLACEMENT_PRICE => [
                'lang' => ['LD_REPLACEMENT_PRICE', 'lot_details'],
                'select' => ['replacement_price', 'currency'],
            ],
            Constants\LotDetail::PL_RESERVE => [
                'lang' => ['LD_RESERVE', 'lot_details'],
                'select' => ['reserve_price', 'currency'],
            ],
            Constants\LotDetail::PL_SRARTING_BID => [
                'lang' => ['LD_STARTING_BID', 'lot_details'],
                'select' => ['starting_bid_normalized', 'currency'],
            ],
        ],
        Constants\Placeholder::URL => [
            Constants\LotDetail::PL_LOT_URL => [
                'select' => ['account_id', 'auction_id', 'id', 'lot_seo_url'],
            ],
            Constants\LotDetail::PL_LOCATION_LOGO_TAG => [
                'observable' => false,
                'select' => ['account_id', 'location_id'],
            ],
            Constants\LotDetail::PL_LOCATION_LOGO_URL => [
                'observable' => false,
                'select' => ['account_id', 'location_id'],
            ],
        ],
        Constants\Placeholder::INDEXED_ARRAY => [
            Constants\LotDetail::PL_IMAGE_TAGS => [
                'select' => ['account_id', 'id', 'lot_image_ids'],
            ],
            Constants\LotDetail::PL_IMAGE_URLS => [
                'select' => ['account_id', 'id', 'lot_image_ids'],
            ],
            Constants\LotDetail::PL_CATEGORIES => [
                'select' => ['account_id', 'category_values'],
                'lang' => ['LD_CATEGORIES', 'lot_details'],
            ],
            Constants\LotDetail::PL_CATEGORY_LINKS => [
                'select' => ['account_id', 'category_values'],
            ],
            Constants\LotDetail::PL_CATEGORY_PATHS => [
                'select' => ['account_id', 'id'],
            ],
            Constants\LotDetail::PL_CATEGORY_PATH_LINKS => [
                'select' => ['account_id', 'id'],
            ],
        ],
        Constants\Placeholder::DATE => [
            Constants\LotDetail::PL_AUCTION_END_DATE => [
                'lang' => ['LD_AUCTION_END_DATE', 'lot_details'],
                'select' => ['auc_en_dt', 'auc_tz_location'],
                'timezone' => 'auc_tz_location'
            ],
            Constants\LotDetail::PL_AUCTION_END_DATE_GMT => [
                'lang' => ['LD_AUCTION_END_DATE_GMT', 'lot_details'],
                'select' => ['auc_en_dt'],
            ],
            Constants\LotDetail::PL_AUCTION_START_DATE => [
                'lang' => ['LD_AUCTION_START_DATE', 'lot_details'],
                'select' => ['auc_st_dt', 'auc_tz_location'],
                'timezone' => 'auc_tz_location'
            ],
            Constants\LotDetail::PL_AUCTION_START_DATE_GMT => [
                'lang' => ['LD_AUCTION_START_DATE_GMT', 'lot_details'],
                'select' => ['auc_st_dt'],
            ],

            Constants\LotDetail::PL_DATE_SOLD => [
                'lang' => ['LD_DATE_SOLD', 'lot_details'],
                'select' => ['date_sold', 'lot_tz_location'],
                'timezone' => 'lot_tz_location'
            ],
            Constants\LotDetail::PL_DATE_SOLD_GMT => [
                'lang' => ['LD_DATE_SOLD_GMT', 'lot_details'],
                'select' => ['date_sold'],
            ],

            Constants\LotDetail::PL_LOT_END_DATE => [
                'lang' => ['LD_LOT_END_DATE', 'lot_details'],
                'select' => ['lot_en_dt', 'lot_tz_location'],
                'timezone' => 'lot_tz_location'
            ],
            Constants\LotDetail::PL_LOT_END_DATE_GMT => [
                'lang' => ['LD_LOT_END_DATE_GMT', 'lot_details'],
                'select' => ['lot_en_dt'],
            ],
            Constants\LotDetail::PL_LOT_START_DATE => [
                'lang' => ['LD_LOT_START_DATE', 'lot_details'],
                'select' => ['lot_st_dt', 'lot_tz_location'],
                'timezone' => 'lot_tz_location'
            ],
            Constants\LotDetail::PL_LOT_START_DATE_GMT => [
                'lang' => ['LD_LOT_START_DATE_GMT', 'lot_details'],
                'select' => ['lot_st_dt'],
            ],
        ],
        Constants\Placeholder::DATE_ADDITIONAL => [
            Constants\LotDetail::PL_AUCTION_END_DATE_TZ_CODE => [
                'lang' => ['LD_AUCTION_END_DATE_TZ_CODE', 'lot_details'],
                'select' => ['auc_tz_location', 'auc_en_dt'],
            ],
            Constants\LotDetail::PL_AUCTION_END_DATE_TZ_OFFSET => [
                'lang' => ['LD_AUCTION_END_DATE_TZ_OFFSET', 'lot_details'],
                'select' => ['auc_tz_location', 'auc_en_dt'],
            ],
            Constants\LotDetail::PL_AUCTION_START_DATE_TZ_CODE => [
                'lang' => ['LD_AUCTION_START_DATE_TZ_CODE', 'lot_details'],
                'select' => ['auc_tz_location', 'auc_st_dt']
            ],
            Constants\LotDetail::PL_AUCTION_START_DATE_TZ_OFFSET => [
                'lang' => ['LD_AUCTION_START_DATE_TZ_OFFSET', 'lot_details'],
                'select' => ['auc_tz_location', 'auc_st_dt'],
            ],

            Constants\LotDetail::PL_DATE_SOLD_TZ_CODE => [
                'lang' => ['LD_DATE_SOLD_TZ_CODE', 'lot_details'],
                'select' => ['lot_tz_location', 'date_sold'],
            ],
            Constants\LotDetail::PL_DATE_SOLD_TZ_OFFSET => [
                'lang' => ['LD_DATE_SOLD_TZ_OFFSET', 'lot_details'],
                'select' => ['lot_tz_location', 'date_sold'],
            ],

            Constants\LotDetail::PL_LOT_END_DATE_TZ_CODE => [
                'lang' => ['LD_LOT_END_DATE_TZ_CODE', 'lot_details'],
                'select' => ['lot_tz_location', 'lot_en_dt'],
            ],
            Constants\LotDetail::PL_LOT_END_DATE_TZ_OFFSET => [
                'lang' => ['LD_LOT_END_DATE_TZ_OFFSET', 'lot_details'],
                'select' => ['lot_tz_location', 'lot_en_dt'],
            ],
            Constants\LotDetail::PL_LOT_START_DATE_TZ_CODE => [
                'lang' => ['LD_LOT_START_DATE_TZ_CODE', 'lot_details'],
                'select' => ['lot_tz_location', 'lot_st_dt'],
            ],
            Constants\LotDetail::PL_LOT_START_DATE_TZ_OFFSET => [
                'lang' => ['LD_LOT_START_DATE_TZ_OFFSET', 'lot_details'],
                'select' => ['lot_tz_location', 'lot_st_dt'],
            ],
        ],
        Constants\Placeholder::LOT_CUSTOM_FIELD => [],
        Constants\Placeholder::LANG_LABEL => [],
    ];

    /**
     * We use it to get observing classes and properties information
     */
    protected ?DataSourceMysql $dataSource = null;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function construct(): static
    {
        parent::construct();

        if (!$this->cfg()->get('core->portal->enabled')) {
            $this->keysConfig[Constants\Placeholder::REGULAR][Constants\LotDetail::PL_ACCOUNT_ID]['available'] = false;
            $this->keysConfig[Constants\Placeholder::REGULAR][Constants\LotDetail::PL_ACCOUNT_NAME]['available'] = false;
        }
        return $this;
    }

    protected function detectObservingProperties(string $resultField): ?array
    {
        if (!$this->dataSource) {
            $this->dataSource = DataSourceMysql::new();
        }
        return $this->dataSource->getObservingProperties($resultField);
    }
}
