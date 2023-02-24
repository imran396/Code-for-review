<?php
/**
 * SAM-6668: Add constants for auction and lot placeholders
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class Details
 * @package Sam\Core\Constants
 */
class AuctionDetail
{
    // Date
    public const PL_START_DATE = 'start_date';
    public const PL_START_DATE_GMT = 'start_date_gmt';
    public const PL_END_DATE = 'end_date';
    public const PL_END_DATE_GMT = 'end_date_gmt';
    public const PL_STARTS_CLOSING_DATE = 'starts_closing_date';
    public const PL_STARTS_CLOSING_DATE_GMT = 'starts_closing_date_gmt';
    public const PL_START_DATE_TZ_CODE = 'start_date_tz_code';
    public const PL_START_DATE_TZ_OFFSET = 'start_date_tz_offset';
    public const PL_END_DATE_TZ_CODE = 'end_date_tz_code';
    public const PL_END_DATE_TZ_OFFSET = 'end_date_tz_offset';
    public const PL_STARTS_CLOSING_DATE_TZ_CODE = 'starts_closing_date_tz_code';
    public const PL_STARTS_CLOSING_DATE_TZ_OFFSET = 'starts_closing_date_tz_offset';

    // Regular
    public const PL_ACCOUNT_COMPANY = 'account_company';
    public const PL_ACCOUNT_EMAIL = 'account_email';
    public const PL_ACCOUNT_ID = 'account_id';
    public const PL_ACCOUNT_NAME = 'account_name';
    public const PL_ACCOUNT_PHONE = 'account_phone';
    public const PL_ACCOUNT_PUBLIC_SUPPORT_CONTACT_NAME = 'account_public_support_contact_name';
    public const PL_ACCOUNT_IMAGE_TAG = 'account_image_tag';
    public const PL_ACCOUNT_SITE_URL = 'account_site_url';
    public const PL_ACCOUNT_IMAGE_URL = 'account_image_url';
    public const PL_AUCTIONEER = 'auctioneer';
    public const PL_TYPE = 'type';
    public const PL_TYPE_LANG = 'type_lang';
    public const PL_CATALOG_URL = 'catalog_url';
    public const PL_COUNTRY = 'country';
    public const PL_CURRENCY_SIGN = 'currency_sign';
    public const PL_DAYS = 'days';
    public const PL_DESCRIPTION = 'description';
    public const PL_EMAIL = 'email';
    public const PL_EVENT_ID = 'event_id';
    public const PL_ID = 'id';
    public const PL_IMAGE_TAG = 'image_tag';
    public const PL_IMAGE_URL = 'image_url';
    public const PL_INFO_URL = 'info_url';
    public const PL_LIVE_URL = 'live_url';
    public const PL_INVOICE_LOCATION = 'invoice_location';
    public const PL_INVOICE_LOCATION_ADDRESS = 'invoice_location_address';
    public const PL_INVOICE_LOCATION_LOGO_TAG = 'invoice_location_logo_tag';
    public const PL_INVOICE_LOCATION_LOGO_URL = 'invoice_location_logo_url';
    public const PL_INVOICE_LOCATION_NAME = 'invoice_location_name';
    public const PL_INVOICE_LOCATION_COUNTRY = 'invoice_location_country';
    public const PL_INVOICE_LOCATION_COUNTY = 'invoice_location_county';
    public const PL_INVOICE_LOCATION_STATE = 'invoice_location_state';
    public const PL_INVOICE_LOCATION_CITY = 'invoice_location_city';
    public const PL_INVOICE_LOCATION_ZIP = 'invoice_location_zip';
    public const PL_EVENT_LOCATION_LOGO_TAG = 'event_location_logo_tag';
    public const PL_EVENT_LOCATION_LOGO_URL = 'event_location_logo_url';
    public const PL_EVENT_LOCATION = 'event_location';
    public const PL_EVENT_LOCATION_ADDRESS = 'event_location_address';
    public const PL_EVENT_LOCATION_NAME = 'event_location_name';
    public const PL_EVENT_LOCATION_COUNTRY = 'event_location_country';
    public const PL_EVENT_LOCATION_COUNTY = 'event_location_county';
    public const PL_EVENT_LOCATION_STATE = 'event_location_state';
    public const PL_EVENT_LOCATION_CITY = 'event_location_city';
    public const PL_EVENT_LOCATION_ZIP = 'event_location_zip';
    public const PL_NAME = 'name';
    public const PL_REGISTER_TO_BID_URL = 'register_to_bid_url';
    public const PL_SALE_NO = 'sale_no';
    public const PL_SEO_META_DESCRIPTION = 'seo_meta_description';
    public const PL_SEO_META_KEYWORDS = 'seo_meta_keywords';
    public const PL_SEO_META_TITLE = 'seo_meta_title';
    public const PL_SHIPPING_INFO = 'shipping_info';
    public const PL_STATUS = 'status';
    public const PL_STATUS_LANG = 'status_lang';
    public const PL_TAX = 'tax';
    public const PL_TAX_STATES = 'tax_states';
    public const PL_TAX_COUNTRY = 'tax_country';
    public const PL_TERMS = 'terms';
    public const PL_TIME_LEFT = 'time_left';
    public const PL_TOTAL_LOTS = 'total_lots';
    public const PL_WAVEBID_AUCTION_GUID = 'wavebid_auction_guid';

    // Boolean
    public const PL_IS_CLOSED = 'is_closed';
    public const PL_IS_LIVE_OR_HYBRID = 'is_live_or_hybrid';
    public const PL_IS_TIMED_SCHEDULED = 'is_timed_scheduled';
    public const PL_IS_TIMED_ONGOING = 'is_timed_ongoing';
    public const PL_IS_MULTIPLE_TENANT_INSTALL = 'is_multiple_tenant_install';
    public const PL_IS_SINGLE_TENANT_INSTALL = 'is_single_tenant_install';

    public const NOT_AVAILABLE = 'N/A';
}
