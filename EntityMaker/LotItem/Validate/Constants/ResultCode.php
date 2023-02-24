<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Validate\Constants;

use Sam\Core\Service\CustomizableClass;

/**
 * Class ResultCode
 * @package Sam\EntityMaker\LotItem
 */
class ResultCode extends CustomizableClass
{
    public const ACCESS_DENIED = 65;
    public const ADDITIONAL_BP_INTERNET_INVALID_FORMAT = 63;
    public const AUCTION_SOLD_DO_NOT_EXIST = 60;
    public const BP_RANGE_CALCULATION_INVALID_FORMAT = 1;
    public const BP_RULE_UNKNOWN = 2;
    public const BUYERS_PREMIUMS_VALIDATION_FAILED = 64;
    public const CATEGORIES_DO_NOT_EXIST = 4;
    public const CATEGORIES_INVALID_ENCODING = 5;
    public const CATEGORIES_NODE_CAN_NOT_BE_ASSIGNED = 86;
    public const CATEGORIES_REQUIRED = 6;
    public const CHANGES_INVALID_ENCODING = 7;
    public const CHANGES_REQUIRED = 8;
    public const CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID = 76;
    public const CONSIGNOR_COMMISSION_ID_INVALID = 75;
    public const CONSIGNOR_COMMISSION_RANGE_INVALID = 77;
    public const CONSIGNOR_NAME_DO_NOT_EXIST = 13;
    public const CONSIGNOR_NAME_INVALID_FORMAT = 14;
    public const CONSIGNOR_REQUIRED = 12;
    public const CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID = 79;
    public const CONSIGNOR_SOLD_FEE_ID_INVALID = 78;
    public const CONSIGNOR_SOLD_FEE_RANGE_INVALID = 81;
    public const CONSIGNOR_SOLD_FEE_REFERENCE_INVALID = 80;
    public const CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID = 83;
    public const CONSIGNOR_UNSOLD_FEE_ID_INVALID = 82;
    public const CONSIGNOR_UNSOLD_FEE_RANGE_INVALID = 85;
    public const CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID = 84;
    public const COST_INVALID_FORMAT = 18;
    public const COST_INVALID_THOUSAND_SEPARATOR = 19;
    public const COST_REQUIRED = 20;
    public const DATE_SOLD_INVALID_FORMAT = 24;
    public const DESCRIPTION_INVALID_ENCODING = 22;
    public const DESCRIPTION_REQUIRED = 23;
    public const HAMMER_PRICE_INVALID_FORMAT = 25;
    public const HAMMER_PRICE_REQUIRED = 26;
    public const HIGH_ESTIMATE_INVALID_FORMAT = 27;
    public const HIGH_ESTIMATE_INVALID_THOUSAND_SEPARATOR = 28;
    public const HIGH_ESTIMATE_REQUIRED = 29;
    public const IMAGE_REMOTE_LOAD_FAILED = 74;
    public const INCREMENTS_AMOUNT_EXIST = 72;
    public const INCREMENTS_INVALID_AMOUNT = 61;
    public const INCREMENTS_INVALID_FORMAT = 30;
    public const INCREMENTS_INVALID_RANGE = 62;
    public const ITEM_FULL_NUM_PARSE_ERROR = 31;
    public const ITEM_NUM_ALREADY_EXIST = 32;
    public const ITEM_NUM_EXT_INVALID_FORMAT = 36;
    public const ITEM_NUM_EXT_INVALID_LENGTH = 37;
    public const ITEM_NUM_HIGHER_MAX_AVAILABLE_VALUE = 33;
    public const ITEM_NUM_INVALID_FORMAT = 34;
    public const ITEM_NUM_REQUIRED = 35;
    public const LOW_ESTIMATE_INVALID_FORMAT = 38;
    public const LOW_ESTIMATE_INVALID_THOUSAND_SEPARATOR = 39;
    public const LOW_ESTIMATE_REQUIRED = 40;
    public const NAME_INVALID_ENCODING = 42;
    public const NAME_IN_BLACKLIST = 43;
    public const NAME_REQUIRED = 41;
    public const REPLACEMENT_PRISE_INVALID_FORMAT = 44;
    public const REPLACEMENT_PRISE_INVALID_THOUSAND_SEPARATOR = 45;
    public const REPLACEMENT_PRISE_REQUIRED = 46;
    public const RESERVE_PRISE_INVALID_FORMAT = 47;
    public const RESERVE_PRISE_INVALID_THOUSAND_SEPARATOR = 48;
    public const RESERVE_PRISE_REQUIRED = 49;
    public const SALES_TAX_INVALID_FORMAT = 50;
    public const SALES_TAX_INVALID_THOUSAND_SEPARATOR = 51;
    public const SALES_TAX_REQUIRED = 52;
    public const SPECIFIC_LOCATION_INVALID = 165;
    public const SPECIFIC_LOCATION_REDUNDANT = 166;
    public const STARTING_BID_INVALID_FORMAT = 53;
    public const STARTING_BID_INVALID_THOUSAND_SEPARATOR = 54;
    public const STARTING_BID_REQUIRED = 55;
    public const SYNC_KEY_EXIST = 66;
    public const WARRANTY_INVALID_ENCODING = 57;
    public const WARRANTY_REQUIRED = 58;
    public const WINNING_BIDDER_DO_NOT_EXIST = 59;
    public const WINNING_BIDDER_NOT_REGISTERED_IN_AUCTION_SOLD = 161;
    public const IMAGE_REQUIRED = 90;
    public const INCREMENTS_REQUIRED = 91;
    public const WINNING_BIDDER_ID_REQUIRED = 92;
    public const WINNING_BIDDER_REQUIRED = 93;
    public const DATE_SOLD_REQUIRED = 94;
    public const AUCTION_SOLD_ID_REQUIRED = 95;
    public const AUCTION_SOLD_REQUIRED = 96;
    public const BP_REQUIRED = 97;
    public const TAX_DEFAULT_COUNTRY_REQUIRED = 99;
    public const TAX_DEFAULT_COUNTRY_UNKNOWN = 172;
    public const TAX_STATE_UNKNOWN = 173;
    public const LOCATION_NOT_FOUND = 174;
    public const LOCATION_REQUIRED = 100;
    public const SEO_META_DESCRIPTION_REQUIRED = 101;
    public const SEO_META_KEYWORDS_REQUIRED = 102;
    public const SEO_META_TITLE_REQUIRED = 103;
    public const FB_OG_DESCRIPTION_REQUIRED = 104;
    public const FB_OG_IMAGE_URL_REQUIRED = 105;
    public const FB_OG_TITLE_REQUIRED = 106;
    public const QUANTITY_REQUIRED = 107;
    public const QUANTITY_INVALID = 108;
    public const QUANTITY_DIGITS_INVALID = 163;
    public const QUANTITY_DIGITS_REQUIRED = 164;
    public const CONSIGNOR_COMMISSION_REQUIRED = 167;
    public const CONSIGNOR_SOLD_FEE_REQUIRED = 168;
    public const CONSIGNOR_UNSOLD_FEE_REQUIRED = 169;
    public const BP_RULE_WITH_INDIVIDUAL_BP_CAN_NOT_BE_ASSIGNED_TOGETHER = 170;
    public const BUY_NOW_SELECT_QUANTITY_NOT_ALLOWED = 171;
    public const HP_TAX_SCHEMA_ID_INVALID = 175;
    public const HP_TAX_SCHEMA_ID_REQUIRED = 176;
    public const HP_TAX_SCHEMA_COUNTRY_MISMATCH = 181;
    public const BP_TAX_SCHEMA_ID_INVALID = 177;
    public const BP_TAX_SCHEMA_ID_REQUIRED = 178;
    public const BP_TAX_SCHEMA_COUNTRY_MISMATCH = 182;

    public const CONSIGNOR_COMMISSION_CALCULATION_METHOD_HIDDEN = 109;
    public const CONSIGNOR_COMMISSION_ID_HIDDEN = 110;
    public const CONSIGNOR_COMMISSION_RANGES_HIDDEN = 111;
    public const CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_HIDDEN = 112;
    public const CONSIGNOR_SOLD_FEE_ID_HIDDEN = 113;
    public const CONSIGNOR_SOLD_FEE_RANGES_HIDDEN = 114;
    public const CONSIGNOR_SOLD_FEE_REFERENCE_HIDDEN = 115;
    public const CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_HIDDEN = 116;
    public const CONSIGNOR_UNSOLD_FEE_ID_HIDDEN = 117;
    public const CONSIGNOR_UNSOLD_FEE_RANGES_HIDDEN = 118;
    public const CONSIGNOR_UNSOLD_FEE_REFERENCE_HIDDEN = 119;
    public const ITEM_NUMBER_HIDDEN = 120;
    public const ITEM_NUMBER_EXTENSION_HIDDEN = 121;
    public const ITEM_FULL_NUMBER_HIDDEN = 122;
    public const CATEGORY_HIDDEN = 123;
    public const QUANTITY_HIDDEN = 124;
    public const QUANTITY_X_MONEY_HIDDEN = 125;
    public const NAME_HIDDEN = 126;
    public const DESCRIPTION_HIDDEN = 127;
    public const CHANGES_HIDDEN = 128;
    public const WARRANTY_HIDDEN = 129;
    public const IMAGE_HIDDEN = 130;
    public const HIGH_ESTIMATE_HIDDEN = 131;
    public const LOW_ESTIMATE_HIDDEN = 132;
    public const STARTING_BID_HIDDEN = 133;
    public const INCREMENTS_HIDDEN = 134;
    public const COST_HIDDEN = 135;
    public const REPLACEMENT_PRICE_HIDDEN = 136;
    public const RESERVE_PRICE_HIDDEN = 137;
    public const CONSIGNOR_HIDDEN = 138;
    public const WINNING_BIDDER_HIDDEN = 139;
    public const DATE_SOLD_HIDDEN = 140;
    public const AUCTION_SOLD_HIDDEN = 141;
    public const ONLY_TAX_BP_HIDDEN = 142;
    public const TAX_HIDDEN = 143;
    public const TAX_ARTIST_RESALE_RIGHTS_HIDDEN = 160;
    public const BP_RULE_HIDDEN = 144;
    public const BUYERS_PREMIUM_ROWS_HIDDEN = 145;
    public const BP_RANGE_CALCULATION_HIDDEN = 146;
    public const ADDITIONAL_BP_INTERNET_HIDDEN = 147;
    public const NO_TAX_OUTSIDE_HIDDEN = 148;
    public const RETURNED_HIDDEN = 149;
    public const ITEM_BILLING_COUNTRY_HIDDEN = 150;
    public const LOCATION_HIDDEN = 151;
    public const SEO_META_DESCRIPTION_HIDDEN = 152;
    public const SEO_META_KEYWORDS_HIDDEN = 153;
    public const SEO_META_TITLE_HIDDEN = 154;
    public const FB_OG_DESCRIPTION_HIDDEN = 155;
    public const FB_OG_IMAGE_URL_HIDDEN = 156;
    public const FB_OG_TITLE_HIDDEN = 157;
    public const HAMMER_PRICE_HIDDEN = 158;
    public const BUYERS_PREMIUMS_HIDDEN = 159;
    public const QUANTITY_DIGITS_HIDDEN = 162;
    public const HP_TAX_SCHEMA_ID_HIDDEN = 179;
    public const BP_TAX_SCHEMA_ID_HIDDEN = 180;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
