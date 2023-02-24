<?php
/**
 * SAM-6668: Add constants for auction and lot placeholders
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class LotDetail
 * @package Sam\Core\Constants
 */
class LotDetail
{
    // Date
    public const PL_AUCTION_END_DATE = 'auction_end_date';
    public const PL_AUCTION_END_DATE_GMT = 'auction_end_date_gmt';
    public const PL_AUCTION_START_DATE = 'auction_start_date';
    public const PL_AUCTION_START_DATE_GMT = 'auction_start_date_gmt';
    public const PL_DATE_SOLD = 'date_sold';
    public const PL_DATE_SOLD_GMT = 'date_sold_gmt';
    public const PL_LOT_END_DATE = 'lot_end_date';
    public const PL_LOT_END_DATE_GMT = 'lot_end_date_gmt';
    public const PL_LOT_START_DATE = 'lot_start_date';
    public const PL_LOT_START_DATE_GMT = 'lot_start_date_gmt';
    public const PL_AUCTION_END_DATE_TZ_CODE = 'auction_end_date_tz_code';
    public const PL_AUCTION_END_DATE_TZ_OFFSET = 'auction_end_date_tz_offset';
    public const PL_AUCTION_START_DATE_TZ_CODE = 'auction_start_date_tz_code';
    public const PL_AUCTION_START_DATE_TZ_OFFSET = 'auction_start_date_tz_offset';
    public const PL_DATE_SOLD_TZ_CODE = 'date_sold_tz_code';
    public const PL_DATE_SOLD_TZ_OFFSET = 'date_sold_tz_offset';
    public const PL_LOT_END_DATE_TZ_CODE = 'lot_end_date_tz_code';
    public const PL_LOT_END_DATE_TZ_OFFSET = 'lot_end_date_tz_offset';
    public const PL_LOT_START_DATE_TZ_CODE = 'lot_start_date_tz_code';
    public const PL_LOT_START_DATE_TZ_OFFSET = 'lot_start_date_tz_offset';

    // Regular
    public const PL_ACCOUNT_ID = 'account_id';
    public const PL_ACCOUNT_COMPANY = 'account_company';
    public const PL_ACCOUNT_NAME = 'account_name';
    public const PL_FEATURED = 'featured';
    public const PL_INTERNET_BID = 'internet_bid';
    public const PL_NO_TAX_OUTSIDE_STATE = 'no_tax_outside_state';
    public const PL_NOTE_TO_CLERK = 'note_to_clerk';
    public const PL_RETURNED = 'returned';
    public const PL_AUCTION_ID = 'auction_id';
    public const PL_AUCTION_EMAIL = 'auction_email';
    public const PL_AUCTION_EVENT_ID = 'auction_event_id';
    public const PL_AUCTION_NAME = 'auction_name';
    public const PL_AUCTION_SEO_META_DESCRIPTION = 'auction_seo_meta_description';
    public const PL_AUCTION_META_SEO_KEYWORDS = 'auction_seo_meta_keywords';
    public const PL_AUCTION_SEO_META_TITLE = 'auction_seo_meta_title';
    public const PL_AUCTION_TYPE = 'auction_type';
    public const PL_AUCTION_TYPE_LANG = 'auction_type_lang';
    public const PL_BIDS = 'bids';
    public const PL_BULK_PIECE_INFO = 'bulk_piece_info';
    public const PL_BUYERS_PREMIUM = 'buyers_premium';
    public const PL_CHANGES = 'changes';
    public const PL_CONSIGNOR = 'consignor';
    public const PL_DESCRIPTION = 'description';
    public const PL_GENERAL_NOTE = 'general_note';
    public const PL_ID = 'id';
    public const PL_ITEM_NO = 'item_no';
    public const PL_LOCATION = 'location';
    public const PL_LOCATION_ADDRESS = 'location_address';
    public const PL_LOCATION_CITY = 'location_city';
    public const PL_LOCATION_COUNTRY = 'location_country';
    public const PL_LOCATION_COUNTY = 'location_county';
    public const PL_LOCATION_LOGO_TAG = 'location_logo_tag';
    public const PL_LOCATION_LOGO_URL = 'location_logo_url';
    public const PL_LOCATION_NAME = 'location_name';
    public const PL_LOCATION_STATE = 'location_state';
    public const PL_LOCATION_ZIP = 'location_zip';
    public const PL_LOT_NO = 'lot_no';
    public const PL_LOT_URL = 'lot_url';
    public const PL_NAME = 'name';
    public const PL_QUANTITY = 'quantity';
    public const PL_SALE_NO = 'sale_no';
    public const PL_SALE_SOLD_IN = 'sale_sold_in';
    public const PL_SALE_SOLD_IN_NO = 'sale_sold_in_no';
    public const PL_SALES_TAX = 'sales_tax';
    public const PL_SEO_META_DESCRIPTION = 'seo_meta_description';
    public const PL_SEO_META_KEYWORDS = 'seo_meta_keywords';
    public const PL_SEO_META_TITLE = 'seo_meta_title';
    public const PL_SEO_URL = 'seo_url';
    public const PL_STATUS = 'status';
    public const PL_STATUS_LANG = 'status_lang';
    public const PL_TIME_LEFT = 'time_left';
    public const PL_VIEWS = 'views';
    public const PL_WARRANTY = 'warranty';
    public const PL_WINNING_BIDDER_BIDDERNO = 'winning_bidder_bidderno';
    public const PL_WINNING_BIDDER_CUSTNO = 'winning_bidder_custno';
    public const PL_WINNING_BIDDER_ID = 'winning_bidder_id';
    public const PL_WINNING_BIDDER_USERNAME = 'winning_bidder_username';

    // Indexed array
    public const PL_CATEGORIES = 'categories';
    public const PL_CATEGORY_LINKS = 'category_links';
    public const PL_CATEGORY_PATHS = 'category_paths';
    public const PL_CATEGORY_PATH_LINKS = 'category_path_links';
    public const PL_IMAGE_URLS = 'image_urls';
    public const PL_IMAGE_TAGS = 'image_tags';

    // Money
    public const PL_ASKING_BID = 'asking_bid';
    public const PL_BUY_NOW_PRICE = 'buy_now_price';
    public const PL_COST = 'cost';
    public const PL_CURRENT_BID = 'current_bid';
    public const PL_HAMMER = 'hammer';
    public const PL_HIGH_ESTIMATE = 'high_estimate';
    public const PL_LOW_ESTIMATE = 'low_estimate';
    public const PL_REPLACEMENT_PRICE = 'replacement_price';
    public const PL_RESERVE = 'reserve';
    public const PL_SRARTING_BID = 'starting_bid';

    public const NOT_AVAILABLE = 'N/A';
}
