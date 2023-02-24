<?php
/**
 * Placeholders related data for translation and db access
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Mar 2, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Base;

use Sam\Core\Constants;

/**
 * Class ConfigManager
 * @package Sam\Details
 */
class ConfigManager extends \Sam\Details\Core\ConfigManager
{
    /**
     * 'lang' - translation label and section;
     * 'observable' - set false to turn off placeholder's fields observing for cached content pre-build, when pre-build for it is supported.
     *     cached pre-build not supported for placeholders that are based on translation labels, installation config options, dynamically changed {time_left}.
     *     the presence of placeholder observability is determined by existing of 'observe' option of selected field, thus we don't explicitly set them to 'true'.
     * 'select' - result set field aliases from AuctionList\DataSourceMysql;
     * 'timezone' - field alias for timezone, used for DateTime building;
     * @var array<array<string, array{lang?: string[], select: string[]|string, available?: bool, observable?: bool}>>
     */
    protected array $keysConfig = [
        Constants\Placeholder::REGULAR => [
            Constants\AuctionDetail::PL_ACCOUNT_COMPANY => [
                'lang' => ['AD_ACCOUNT_COMPANY', 'auction_details'],
                'select' => ['account_company_name'],
            ],
            Constants\AuctionDetail::PL_ACCOUNT_ID => [
                'lang' => ['AD_ACCOUNT_ID', 'auction_details'],
                'select' => ['account_id'],
            ],
            Constants\AuctionDetail::PL_ACCOUNT_NAME => [
                'lang' => ['AD_ACCOUNT_NAME', 'auction_details'],
                'select' => ['account_name'],
            ],
            Constants\AuctionDetail::PL_ACCOUNT_PHONE => [
                'lang' => ['AD_ACCOUNT_PHONE', 'auction_details'],
                'select' => ['account_phone'],
            ],
            Constants\AuctionDetail::PL_ACCOUNT_EMAIL => [
                'lang' => ['AD_ACCOUNT_EMAIL', 'auction_details'],
                'select' => ['account_email'],
            ],
            Constants\AuctionDetail::PL_ACCOUNT_PUBLIC_SUPPORT_CONTACT_NAME => [
                'lang' => ['AD_ACCOUNT_PUBLIC_SUPPORT_CONTACT', 'auction_details'],
                'select' => ['account_public_support_contact_name'],
            ],
            Constants\AuctionDetail::PL_AUCTIONEER => [
                'lang' => ['AD_AUCTIONEER', 'auction_details'],
                'select' => ['auctioneer_name'],
            ],
            Constants\AuctionDetail::PL_COUNTRY => [
                'lang' => ['AD_COUNTRY', 'auction_details'],
                'select' => ['auction_held_in'],
            ],
            Constants\AuctionDetail::PL_CURRENCY_SIGN => [
                'lang' => ['AD_CURRENCY', 'auction_details'],
                'select' => ['currency_sign'],
            ],
            Constants\AuctionDetail::PL_DAYS => [
                'lang' => ['AD_DAYS', 'auction_details'],
                'select' => ['auction_type', 'start_closing_date', 'end_date'],
            ],
            Constants\AuctionDetail::PL_DESCRIPTION => [
                'lang' => ['AD_DESCRIPTION', 'auction_details'],
                'select' => ['description'],
            ],
            Constants\AuctionDetail::PL_EMAIL => [
                'lang' => ['AD_EMAIL', 'auction_details'],
                'select' => ['email'],
            ],
            Constants\AuctionDetail::PL_EVENT_ID => [
                'lang' => ['AD_EVENT_ID', 'auction_details'],
                'select' => ['event_id'],
            ],
            Constants\AuctionDetail::PL_ID => [
                'lang' => ['AD_ID', 'auction_details'],
                'select' => ['id'],
            ],
            Constants\AuctionDetail::PL_INVOICE_LOCATION => [
                'lang' => ['AD_LOCATION', 'auction_details'],
                'select' => [
                    'invoice_location_address',
                    'invoice_location_name',
                    'invoice_location_country',
                    'invoice_location_county',
                    'invoice_location_state',
                    'invoice_location_city',
                    'invoice_location_zip'
                ],
            ],
            Constants\AuctionDetail::PL_INVOICE_LOCATION_ADDRESS => [
                'lang' => ['AD_LOCATION_ADDRESS', 'auction_details'],
                'select' => ['invoice_location_address'],
            ],
            Constants\AuctionDetail::PL_INVOICE_LOCATION_NAME => [
                'lang' => ['AD_LOCATION_NAME', 'auction_details'],
                'select' => ['invoice_location_name'],
            ],
            Constants\AuctionDetail::PL_INVOICE_LOCATION_COUNTRY => [
                'lang' => ['AD_LOCATION_COUNTRY', 'auction_details'],
                'select' => ['invoice_location_country'],
            ],
            Constants\AuctionDetail::PL_INVOICE_LOCATION_COUNTY => [
                'lang' => ['AD_LOCATION_COUNTY', 'auction_details'],
                'select' => ['invoice_location_county'],
            ],
            Constants\AuctionDetail::PL_INVOICE_LOCATION_STATE => [
                'lang' => ['AD_LOCATION_STATE', 'auction_details'],
                'select' => ['invoice_location_country', 'invoice_location_state'],
            ],
            Constants\AuctionDetail::PL_INVOICE_LOCATION_CITY => [
                'lang' => ['AD_LOCATION_CITY', 'auction_details'],
                'select' => ['invoice_location_city'],
            ],
            Constants\AuctionDetail::PL_INVOICE_LOCATION_ZIP => [
                'lang' => ['AD_LOCATION_ZIP', 'auction_details'],
                'select' => ['invoice_location_zip'],
            ],
            Constants\AuctionDetail::PL_EVENT_LOCATION => [
                'lang' => ['AD_EVENT_LOCATION', 'auction_details'],
                'select' => [
                    'event_location_address',
                    'event_location_name',
                    'event_location_country',
                    'event_location_county',
                    'event_location_state',
                    'event_location_city',
                    'event_location_zip'
                ],
            ],
            Constants\AuctionDetail::PL_EVENT_LOCATION_ADDRESS => [
                'lang' => ['AD_EVENT_LOCATION_ADDRESS', 'auction_details'],
                'select' => ['event_location_address'],
            ],
            Constants\AuctionDetail::PL_EVENT_LOCATION_NAME => [
                'lang' => ['AD_EVENT_LOCATION_NAME', 'auction_details'],
                'select' => ['event_location_name'],
            ],
            Constants\AuctionDetail::PL_EVENT_LOCATION_COUNTRY => [
                'lang' => ['AD_EVENT_LOCATION_COUNTRY', 'auction_details'],
                'select' => ['event_location_country'],
            ],
            Constants\AuctionDetail::PL_EVENT_LOCATION_COUNTY => [
                'lang' => ['AD_EVENT_LOCATION_COUNTY', 'auction_details'],
                'select' => ['event_location_county'],
            ],
            Constants\AuctionDetail::PL_EVENT_LOCATION_STATE => [
                'lang' => ['AD_EVENT_LOCATION_STATE', 'auction_details'],
                'select' => ['event_location_country', 'event_location_state'],
            ],
            Constants\AuctionDetail::PL_EVENT_LOCATION_CITY => [
                'lang' => ['AD_EVENT_LOCATION_CITY', 'auction_details'],
                'select' => ['event_location_city'],
            ],
            Constants\AuctionDetail::PL_EVENT_LOCATION_ZIP => [
                'lang' => ['AD_EVENT_LOCATION_ZIP', 'auction_details'],
                'select' => ['event_location_zip'],
            ],
            Constants\AuctionDetail::PL_NAME => [
                'lang' => ['AD_NAME', 'auction_details'],
                'select' => ['name', 'test_auction'],
            ],
            Constants\AuctionDetail::PL_SALE_NO => [
                'lang' => ['AD_SALE_NO', 'auction_details'],
                'select' => ['sale_num', 'sale_num_ext'],
            ],
            Constants\AuctionDetail::PL_SEO_META_DESCRIPTION => [
                'select' => ['seo_meta_description'],
            ],
            Constants\AuctionDetail::PL_SEO_META_KEYWORDS => [
                'select' => ['seo_meta_keywords'],
            ],
            Constants\AuctionDetail::PL_SEO_META_TITLE => [
                'select' => ['seo_meta_title'],
            ],
            Constants\AuctionDetail::PL_SHIPPING_INFO => [
                'lang' => ['AD_SHIPPING_INFO', 'auction_details'],
                'select' => ['shipping_info'],
            ],
            Constants\AuctionDetail::PL_STATUS => [
                'lang' => ['AD_STATUS', 'auction_details'],
                'select' => ['auction_type', 'auction_status_id', 'end_date', 'event_type', 'start_closing_date', 'start_bidding_date'],
            ],
            Constants\AuctionDetail::PL_STATUS_LANG => [
                'select' => ['auction_type', 'auction_status_id', 'end_date', 'event_type', 'start_closing_date', 'start_bidding_date'],
            ],
            Constants\AuctionDetail::PL_TAX => [
                'lang' => ['AD_TAX', 'auction_details'],
                'select' => ['tax_percent'],
            ],
            Constants\AuctionDetail::PL_TAX_COUNTRY => [
                'lang' => ['AD_TAX_COUNTRY', 'auction_details'],
                'select' => ['tax_default_country'],
            ],
            Constants\AuctionDetail::PL_TAX_STATES => [
                'lang' => ['AD_TAX_STATES', 'auction_details'],
                'select' => ['tax_states'],
            ],
            Constants\AuctionDetail::PL_TERMS => [
                'lang' => ['AD_TERMS', 'auction_details'],
                'select' => ['terms_and_conditions'],
            ],
            Constants\AuctionDetail::PL_TIME_LEFT => [
                'lang' => ['AD_TIME_LEFT', 'auction_details'],
                'observable' => false,
                'select' => ['account_id', 'seconds_before', 'seconds_left'],
            ],
            Constants\AuctionDetail::PL_TOTAL_LOTS => [
                'lang' => ['AD_TOTAL_LOTS', 'auction_details'],
                'select' => ['total_lots'],
            ],
            Constants\AuctionDetail::PL_TYPE => [
                'lang' => ['AD_TYPE', 'auction_details'],
                'select' => ['auction_type'],
            ],
            Constants\AuctionDetail::PL_TYPE_LANG => [
                'select' => ['auction_type'],
            ],
            Constants\AuctionDetail::PL_WAVEBID_AUCTION_GUID => [
                'lang' => ['AD_WAVEBID_AUCTION_GUID', 'auction_details'],
                'select' => ['wavebid_auction_guid'],
            ],
        ],
        Constants\Placeholder::URL => [
            Constants\AuctionDetail::PL_ACCOUNT_IMAGE_TAG => [
                'observable' => false,
                'select' => ['account_id'],
            ],
            Constants\AuctionDetail::PL_ACCOUNT_IMAGE_URL => [
                'observable' => false,
                'select' => ['account_id'],
            ],
            Constants\AuctionDetail::PL_ACCOUNT_SITE_URL => [
                'observable' => false,
                'select' => ['account_site_url'],
            ],
            Constants\AuctionDetail::PL_CATALOG_URL => [
                'observable' => false,
                'select' => ['account_id', 'auction_seo_url', 'id'],
            ],
            Constants\AuctionDetail::PL_IMAGE_TAG => [
                'observable' => false,
                'select' => ['account_id', 'image_id'],
            ],
            Constants\AuctionDetail::PL_IMAGE_URL => [
                'observable' => false,
                'select' => ['account_id', 'image_id'],
            ],
            Constants\AuctionDetail::PL_INFO_URL => [
                'lang' => ['AD_INFO_URL', 'auction_details'],
                'observable' => false,
                'select' => ['account_id', 'auction_info_link', 'auction_seo_url', 'id'],
            ],
            Constants\AuctionDetail::PL_LIVE_URL => [
                'lang' => ['AD_LIVE_URL', 'auction_details'],
                'observable' => false,
                'select' => ['account_id', 'auction_seo_url', 'auction_type', 'id'],
            ],
            Constants\AuctionDetail::PL_REGISTER_TO_BID_URL => [
                'observable' => false,
                'select' => ['account_id', 'id'],
            ],
            Constants\AuctionDetail::PL_INVOICE_LOCATION_LOGO_TAG => [
                'observable' => false,
                'select' => ['account_id', 'invoice_location_id'],
            ],
            Constants\AuctionDetail::PL_INVOICE_LOCATION_LOGO_URL => [
                'observable' => false,
                'select' => ['account_id', 'invoice_location_id'],
            ],
            Constants\AuctionDetail::PL_EVENT_LOCATION_LOGO_TAG => [
                'observable' => false,
                'select' => ['account_id', 'event_location_id'],
            ],
            Constants\AuctionDetail::PL_EVENT_LOCATION_LOGO_URL => [
                'observable' => false,
                'select' => ['account_id', 'event_location_id'],
            ],
        ],
        Constants\Placeholder::DATE => [
            Constants\AuctionDetail::PL_START_DATE => [
                'lang' => ['AD_START_DATE', 'auction_details'],
                'select' => ['start_date', 'timezone_location'],
                'timezone' => 'timezone_location'
            ],
            Constants\AuctionDetail::PL_START_DATE_GMT => [
                'lang' => ['AD_START_DATE_GMT', 'auction_details'],
                'select' => ['start_date'],
            ],
            Constants\AuctionDetail::PL_END_DATE => [
                'lang' => ['AD_END_DATE', 'auction_details'],
                'select' => ['end_date', 'timezone_location'],
                'timezone' => 'timezone_location'
            ],
            Constants\AuctionDetail::PL_END_DATE_GMT => [
                'lang' => ['AD_END_DATE_GMT', 'auction_details'],
                'select' => ['end_date'],
            ],
            Constants\AuctionDetail::PL_STARTS_CLOSING_DATE => [
                'lang' => ['AD_STARTS_CLOSING_DATE', 'auction_details'],
                'select' => ['start_closing_date', 'timezone_location', 'ap_show_auction_starts_ending'],
                'timezone' => 'timezone_location'
            ],
            Constants\AuctionDetail::PL_STARTS_CLOSING_DATE_GMT => [
                'lang' => ['AD_STARTS_CLOSING_DATE_GMT', 'auction_details'],
                'select' => ['start_closing_date', 'ap_show_auction_starts_ending'],
            ],
        ],
        Constants\Placeholder::DATE_ADDITIONAL => [
            Constants\AuctionDetail::PL_START_DATE_TZ_CODE => [
                'lang' => ['AD_START_DATE_TZ_CODE', 'auction_details'],
                'select' => ['timezone_location', 'start_date']
            ],
            Constants\AuctionDetail::PL_START_DATE_TZ_OFFSET => [
                'lang' => ['AD_START_DATE_TZ_OFFSET', 'auction_details'],
                'select' => ['timezone_location', 'start_date']
            ],
            Constants\AuctionDetail::PL_END_DATE_TZ_CODE => [
                'lang' => ['AD_END_DATE_TZ_CODE', 'auction_details'],
                'select' => ['timezone_location', 'end_date']
            ],
            Constants\AuctionDetail::PL_END_DATE_TZ_OFFSET => [
                'lang' => ['AD_END_DATE_TZ_OFFSET', 'auction_details'],
                'select' => ['timezone_location', 'end_date'],
            ],
            Constants\AuctionDetail::PL_STARTS_CLOSING_DATE_TZ_CODE => [
                'lang' => ['AD_STARTS_CLOSING_DATE_TZ_CODE', 'auction_details'],
                'select' => ['start_ending_timezone_location', 'start_closing_date', 'ap_show_auction_starts_ending'],
            ],
            Constants\AuctionDetail::PL_STARTS_CLOSING_DATE_TZ_OFFSET => [
                'lang' => ['AD_STARTS_CLOSING_DATE_TZ_OFFSET', 'auction_details'],
                'select' => ['start_ending_timezone_location', 'start_closing_date', 'ap_show_auction_starts_ending'],
            ],
        ],
        Constants\Placeholder::BOOLEAN => [
            Constants\AuctionDetail::PL_IS_LIVE_OR_HYBRID => [
                'select' => ['auction_type'],
            ],
            Constants\AuctionDetail::PL_IS_TIMED_SCHEDULED => [
                'lang' => ['AD_IS_TIMED_SCHEDULED', 'auction_details'],
                'select' => ['auction_type', 'event_type'],
            ],
            Constants\AuctionDetail::PL_IS_TIMED_ONGOING => [
                'lang' => ['AD_IS_TIMED_ONGOING', 'auction_details'],
                'select' => ['auction_type', 'event_type'],
            ],
            Constants\AuctionDetail::PL_IS_CLOSED => [
                'lang' => ['AD_IS_CLOSED', 'auction_details'],
                'select' => ['auction_status_id', 'id', 'end_date', 'auction_type', 'event_type'],
            ],
            Constants\AuctionDetail::PL_IS_MULTIPLE_TENANT_INSTALL => [
                'select' => []
            ],
            Constants\AuctionDetail::PL_IS_SINGLE_TENANT_INSTALL => [
                'select' => []
            ]
        ],
        Constants\Placeholder::AUCTION_CUSTOM_FIELD => [],
        Constants\Placeholder::LANG_LABEL => [],
    ];

    /**
     * We use it to get observing classes and properties information
     */
    protected ?\Sam\Auction\AuctionList\DataSourceMysql $dataSource = null;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function construct(): static
    {
        parent::construct();

        if (!$this->cfg()->get('core->portal->enabled')) {
            $this->keysConfig[Constants\Placeholder::REGULAR]['account_id']['available'] = false;
            $this->keysConfig[Constants\Placeholder::REGULAR]['account_name']['available'] = false;
        }
        return $this;
    }

    protected function detectObservingProperties(string $resultField): ?array
    {
        if (!$this->dataSource) {
            $this->dataSource = \Sam\Auction\AuctionList\DataSourceMysql::new();
        }
        return $this->dataSource->getObservingProperties($resultField);
    }
}
