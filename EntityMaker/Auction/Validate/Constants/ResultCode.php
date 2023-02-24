<?php
/**
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 1, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Constants;

use Sam\Core\Service\CustomizableClass;

/**
 * Class ResultCode
 * @package Sam\EntityMaker\Auction\Validate\Constants
 */
class ResultCode extends CustomizableClass
{
    public const ABSENTEE_BIDS_DISPLAY_UNKNOWN = 1;
    public const ACCESS_DENIED = 113;
    public const ADDITIONAL_BP_INTERNET_INVALID = 2;
    public const AUCTION_AUCTIONEER_ID_NOT_FOUND = 3;
    public const AUCTION_CATALOG_ACCESS_UNKNOWN = 4;
    public const AUCTION_HELD_IN_UNKNOWN = 5;
    public const AUCTION_INFO_ACCESS_UNKNOWN = 6;
    public const AUCTION_STATUS_ID_UNKNOWN = 7;
    public const AUCTION_STATUS_UNKNOWN = 8;
    public const AUCTION_TYPE_REQUIRED = 9;
    public const AUCTION_TYPE_UNKNOWN = 10;
    public const AUCTION_VISIBILITY_ACCESS_UNKNOWN = 11;
    public const AUTHORIZATION_AMOUNT_INVALID = 12;
    public const BIDDING_CONSOLE_ACCESS_DATE_EARLIER_START_DATE = 13;
    public const BIDDING_CONSOLE_ACCESS_DATE_INVALID = 14;
    public const BIDDING_CONSOLE_ACCESS_DATE_REQUIRED = 15;
    public const BP_RANGE_CALCULATION_UNKNOWN = 16;
    public const BP_RULE_NOT_FOUND = 17;
    public const BP_TAX_SCHEMA_ID_HIDDEN = 173;
    public const BP_TAX_SCHEMA_ID_INVALID = 174;
    public const BP_TAX_SCHEMA_ID_REQUIRED = 175;
    public const BP_TAX_SCHEMA_COUNTRY_MISMATCH = 238;
    public const BUYERS_PREMIUMS_VALIDATION_FAILED = 18;
    public const CC_THRESHOLD_DOMESTIC_INVALID = 19;
    public const CC_THRESHOLD_INTERNATIONAL_INVALID = 20;
    public const CLERKING_STYLE_UNKNOWN = 21;
    public const CURRENCY_NOT_FOUND = 22;
    public const DATE_ASSIGNMENT_STRATEGY_UNKNOWN = 23;
    public const DEFAULT_LOT_PERIOD_INVALID = 24;
    public const DEFAULT_LOT_POSTAL_CODE_INVALID = 25;
    public const EMAIL_INVALID = 26;
    public const END_PREBIDDING_DATE_INVALID = 27;
    public const END_REGISTER_DATE_EARLIER_START_REGISTER_DATE = 28;
    public const END_REGISTER_DATE_INVALID = 29;
    public const EVENT_LOCATION_NOT_FOUND = 30;
    public const EVENT_TYPE_UNKNOWN = 31;
    public const EXTEND_TIME_INVALID = 32;
    public const EXTEND_TIME_TOO_SMALL = 33;
    public const EXTEND_TIME_TOO_BIG = 231;
    public const EXTEND_TIME_REQUIRED = 230;
    public const HP_TAX_SCHEMA_ID_HIDDEN = 176;
    public const HP_TAX_SCHEMA_ID_INVALID = 177;
    public const HP_TAX_SCHEMA_ID_REQUIRED = 178;
    public const HP_TAX_SCHEMA_COUNTRY_MISMATCH = 237;
    public const INCREMENTS_AMOUNT_EXIST = 34;
    public const INCREMENTS_INVALID_AMOUNT = 35;
    public const INCREMENTS_INVALID_FORMAT = 36;
    public const INCREMENTS_INVALID_RANGE = 37;
    public const LIVE_END_DATE_EARLIER_START_CLOSING_DATE = 38;
    public const LIVE_END_DATE_INVALID = 39;
    public const LIVE_END_DATE_REQUIRED = 40;
    public const LIVE_VIEW_ACCESS_UNKNOWN = 41;
    public const INVOICE_LOCATION_NOT_FOUND = 42;
    public const LOTS_PER_INTERVAL_INVALID = 43;
    public const LOTS_PER_INTERVAL_REQUIRED = 44;
    public const LOT_BIDDING_HISTORY_ACCESS_UNKNOWN = 45;
    public const LOT_BIDDING_INFO_ACCESS_UNKNOWN = 46;
    public const LOT_DETAILS_ACCESS_UNKNOWN = 47;
    public const LOT_ORDER_PRIMARY_CUSTOM_FIELD_ID_NOT_FOUND = 48;
    public const LOT_ORDER_PRIMARY_CUSTOM_FIELD_NOT_FOUND = 49;
    public const LOT_ORDER_PRIMARY_CUSTOM_FIELD_REQUIRED = 50;
    public const LOT_ORDER_PRIMARY_TYPE_NOT_UNIQUE = 51;
    public const LOT_ORDER_PRIMARY_TYPE_UNKNOWN = 52;
    public const LOT_ORDER_QUATERNARY_CUSTOM_FIELD_ID_NOT_FOUND = 53;
    public const LOT_ORDER_QUATERNARY_CUSTOM_FIELD_NOT_FOUND = 54;
    public const LOT_ORDER_QUATERNARY_CUSTOM_FIELD_REQUIRED = 55;
    public const LOT_ORDER_QUATERNARY_TYPE_NOT_UNIQUE = 56;
    public const LOT_ORDER_QUATERNARY_TYPE_UNKNOWN = 57;
    public const LOT_ORDER_SECONDARY_CUSTOM_FIELD_ID_NOT_FOUND = 58;
    public const LOT_ORDER_SECONDARY_CUSTOM_FIELD_NOT_FOUND = 59;
    public const LOT_ORDER_SECONDARY_CUSTOM_FIELD_REQUIRED = 60;
    public const LOT_ORDER_SECONDARY_TYPE_NOT_UNIQUE = 61;
    public const LOT_ORDER_SECONDARY_TYPE_UNKNOWN = 62;
    public const LOT_ORDER_TERTIARY_CUSTOM_FIELD_ID_NOT_FOUND = 63;
    public const LOT_ORDER_TERTIARY_CUSTOM_FIELD_NOT_FOUND = 64;
    public const LOT_ORDER_TERTIARY_CUSTOM_FIELD_REQUIRED = 65;
    public const LOT_ORDER_TERTIARY_TYPE_NOT_UNIQUE = 66;
    public const LOT_ORDER_TERTIARY_TYPE_UNKNOWN = 67;
    public const LOT_STARTING_BID_ACCESS_UNKNOWN = 68;
    public const LOT_START_GAP_TIME_INVALID = 69;
    public const LOT_START_GAP_TIME_TOO_BIG = 232;
    public const LOT_START_GAP_TIME_TOO_SMALL = 233;
    public const LOT_WINNING_BID_ACCESS_UNKNOWN = 70;
    public const MAX_OUTSTANDING_INVALID = 71;
    public const NAME_INVALID = 72;
    public const NAME_REQUIRED = 73;
    public const NOTIFY_X_LOTS_INVALID = 74;
    public const NOTIFY_X_MINUTES_INVALID = 75;
    public const POST_AUC_IMPORT_PREMIUM_INVALID = 76;
    public const PUBLISHED_DEPRECATED = 77;
    public const PUBLISH_DATE_INVALID = 78;
    public const PUBLISH_DATE_MISSING_PRIVILEGE = 79;
    public const RTBD_NAME_INCORRECT = 80;
    public const SALE_FULL_NO_PARSE_ERROR = 81;
    public const SALE_NO_EXIST = 82;
    public const SALE_NUM_EXT_INVALID = 83;
    public const SALE_NUM_EXT_NOT_ALPHA = 84;
    public const SALE_NUM_HIGHER_MAX_AVAILABLE_VALUE = 85;
    public const SALE_NUM_INVALID = 86;
    public const SPECIFIC_EVENT_LOCATION_INVALID = 219;
    public const SPECIFIC_EVENT_LOCATION_REDUNDANT = 221;
    public const SPECIFIC_INVOICE_LOCATION_INVALID = 220;
    public const SPECIFIC_INVOICE_LOCATION_REDUNDANT = 222;
    public const STAGGER_CLOSING_UNKNOWN = 87;
    public const START_BIDDING_DATE_DO_NOT_MATCH_ITEMS_DATE = 88;
    public const START_BIDDING_DATE_INVALID = 89;
    public const START_BIDDING_DATE_REQUIRED = 90;
    public const START_CLOSING_DATE_DO_NOT_MATCH_ITEMS_DATE = 91;
    public const START_CLOSING_DATE_INVALID = 92;
    public const START_CLOSING_DATE_REQUIRED = 93;
    public const START_REGISTER_DATE_INVALID = 94;
    public const STREAM_DISPLAY_UNKNOWN = 95;
    public const SYNC_KEY_EXIST = 96;
    public const TAX_PERCENT_INVALID = 97;
    public const TAX_STATE_UNKNOWN = 227;
    public const TIMEZONE_REQUIRED = 98;
    public const TIMEZONE_UNKNOWN = 99;
    public const UNPUBLISH_DATE_INVALID = 100;
    public const UNPUBLISH_DATE_MISSING_PRIVILEGE = 101;
    public const CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID = 102;
    public const CONSIGNOR_COMMISSION_ID_INVALID = 103;
    public const CONSIGNOR_COMMISSION_RANGE_INVALID = 104;
    public const CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID = 105;
    public const CONSIGNOR_SOLD_FEE_ID_INVALID = 106;
    public const CONSIGNOR_SOLD_FEE_RANGE_INVALID = 107;
    public const CONSIGNOR_SOLD_FEE_REFERENCE_INVALID = 108;
    public const CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID = 109;
    public const CONSIGNOR_UNSOLD_FEE_ID_INVALID = 110;
    public const CONSIGNOR_UNSOLD_FEE_RANGE_INVALID = 111;
    public const CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID = 112;
    public const CLERKING_STYLE_HIDDEN = 117;
    public const CLERKING_STYLE_ID_HIDDEN = 118;
    public const CONSIGNOR_COMMISSION_CALCULATION_METHOD_HIDDEN = 119;
    public const CONSIGNOR_COMMISSION_ID_HIDDEN = 120;
    public const CONSIGNOR_COMMISSION_RANGES_HIDDEN = 121;
    public const CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_HIDDEN = 122;
    public const CONSIGNOR_SOLD_FEE_ID_HIDDEN = 123;
    public const CONSIGNOR_SOLD_FEE_RANGES_HIDDEN = 124;
    public const CONSIGNOR_SOLD_FEE_REFERENCE_HIDDEN = 125;
    public const CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_HIDDEN = 126;
    public const CONSIGNOR_UNSOLD_FEE_ID_HIDDEN = 127;
    public const CONSIGNOR_UNSOLD_FEE_RANGES_HIDDEN = 128;
    public const CONSIGNOR_UNSOLD_FEE_REFERENCE_HIDDEN = 129;
    public const DATE_ASSIGNMENT_STRATEGY_HIDDEN = 130;
    public const DESCRIPTION_HIDDEN = 131;
    public const END_PREBIDDING_DATE_HIDDEN = 133;
    public const END_REGISTER_DATE_HIDDEN = 134;
    public const EVENT_LOCATION_HIDDEN = 135;
    public const EVENT_LOCATION_ID_HIDDEN = 136;
    public const EVENT_TYPE_HIDDEN = 137;
    public const EVENT_TYPE_ID_HIDDEN = 138;
    public const EXCLUDE_CLOSED_LOTS_HIDDEN = 139;
    public const IMAGE_HIDDEN = 140;
    public const INVOICE_LOCATION_HIDDEN = 141;
    public const INVOICE_LOCATION_ID_HIDDEN = 142;
    public const INVOICE_NOTES_HIDDEN = 143;
    public const LISTING_ONLY_HIDDEN = 144;
    public const ONLY_ONGOING_LOTS_HIDDEN = 145;
    public const NOT_SHOW_UPCOMING_LOTS_HIDDEN = 146;
    public const PARCEL_CHOICE_HIDDEN = 148;
    public const PUBLISH_DATE_HIDDEN = 149;
    public const REVERSE_HIDDEN = 150;
    public const SALE_FULL_NO_HIDDEN = 151;
    public const SALE_NUM_HIDDEN = 152;
    public const SALE_NUM_EXT_HIDDEN = 153;
    public const SEO_META_DESCRIPTION_HIDDEN = 154;
    public const SEO_META_KEYWORDS_HIDDEN = 155;
    public const SEO_META_TITLE_HIDDEN = 156;
    public const SHIPPING_INFO_HIDDEN = 157;
    public const STAGGER_CLOSING_HIDDEN = 158;
    public const LOTS_PER_INTERVAL_HIDDEN = 159;
    public const START_BIDDING_DATE_HIDDEN = 160;
    public const START_CLOSING_DATE_HIDDEN = 161;
    public const START_REGISTER_DATE_HIDDEN = 162;
    public const STREAM_DISPLAY_HIDDEN = 163;
    public const STREAM_DISPLAY_VALUE_HIDDEN = 164;
    public const TAX_PERCENT_HIDDEN = 165;
    public const TAX_DEFAULT_COUNTRY_HIDDEN = 166;
    public const TERMS_AND_CONDITIONS_HIDDEN = 167;
    public const TIMEZONE_HIDDEN = 168;
    public const UNPUBLISH_DATE_HIDDEN = 169;
    public const AUCTION_INFO_LINK_HIDDEN = 114;
    public const AUCTION_INFO_LINK_REQUIRED = 170;
    public const AUCTION_INFO_LINK_URL_INVALID = 218;
    public const CLERKING_STYLE_REQUIRED = 171;
    public const CLERKING_STYLE_ID_REQUIRED = 172;
    public const DATE_ASSIGNMENT_STRATEGY_REQUIRED = 184;
    public const DESCRIPTION_REQUIRED = 185;
    public const END_PREBIDDING_DATE_REQUIRED = 186;
    public const END_REGISTER_DATE_REQUIRED = 187;
    public const EVENT_LOCATION_REQUIRED = 188;
    public const EVENT_LOCATION_ID_REQUIRED = 189;
    public const EVENT_TYPE_REQUIRED = 190;
    public const EVENT_TYPE_ID_REQUIRED = 191;
    public const EXCLUDE_CLOSED_LOTS_REQUIRED = 192;
    public const IMAGE_REQUIRED = 193;
    public const INVOICE_LOCATION_REQUIRED = 194;
    public const INVOICE_LOCATION_ID_REQUIRED = 195;
    public const INVOICE_NOTES_REQUIRED = 196;
    public const PUBLISH_DATE_REQUIRED = 201;
    public const REVERSE_REQUIRED = 202;
    public const SALE_FULL_NO_REQUIRED = 203;
    public const SALE_NUM_REQUIRED = 204;
    public const SEO_META_DESCRIPTION_REQUIRED = 206;
    public const SEO_META_KEYWORDS_REQUIRED = 207;
    public const SEO_META_TITLE_REQUIRED = 208;
    public const SHIPPING_INFO_REQUIRED = 209;
    public const STAGGER_CLOSING_REQUIRED = 210;
    public const START_REGISTER_DATE_REQUIRED = 211;
    public const STREAM_DISPLAY_REQUIRED = 212;
    public const STREAM_DISPLAY_VALUE_REQUIRED = 213;
    public const TAX_PERCENT_REQUIRED = 214;
    public const TAX_DEFAULT_COUNTRY_REQUIRED = 215;
    public const TAX_DEFAULT_COUNTRY_UNKNOWN = 229;
    public const TERMS_AND_CONDITIONS_REQUIRED = 216;
    public const UNPUBLISH_DATE_REQUIRED = 217;
    public const BP_RULE_WITH_INDIVIDUAL_BP_CAN_NOT_BE_ASSIGNED_TOGETHER = 223;
    public const CONSIGNOR_COMMISSION_REQUIRED = 224;
    public const CONSIGNOR_SOLD_FEE_REQUIRED = 225;
    public const CONSIGNOR_UNSOLD_FEE_REQUIRED = 226;
    public const HIDE_UNSOLD_LOTS_HIDDEN = 228;
    public const SERVICES_TAX_SCHEMA_ID_HIDDEN = 234;
    public const SERVICES_TAX_SCHEMA_ID_INVALID = 235;
    public const SERVICES_TAX_SCHEMA_ID_REQUIRED = 236;
    public const SERVICES_TAX_SCHEMA_COUNTRY_MISMATCH = 239;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
