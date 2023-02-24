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

namespace Sam\EntityMaker\AuctionLot\Validate\Constants;

use Sam\Core\Service\CustomizableClass;

/**
 * Class ResultCode
 * @package Sam\EntityMaker\AuctionLot\Validate\Constants
 */
class ResultCode extends CustomizableClass
{
    public const ACCESS_DENIED = 56;
    public const AUCTION_LOT_EXIST = 1;
    public const AUCTION_NOT_FOUND = 2;
    public const AUCTION_ID_NOT_SPECIFIED = 3;
    public const BULK_CONTROL_NOT_FOUND = 4;
    public const BULK_MASTER_WIN_BID_DISTRIBUTION_UNKNOWN = 5;
    public const BUY_NOW_AMOUNT_INVALID = 6;
    public const BUY_NOW_AMOUNT_SUPPORTED_IN_REVERSE_AUCTION = 7;
    public const BUY_NOW_AMOUNT_REQUIRED = 8;
    public const END_PREBIDDING_DATE_INVALID = 9;
    public const GENERAL_NOTE_INVALID_ENCODING = 10;
    public const GENERAL_NOTE_REQUIRED = 11;
    public const LOT_ALREADY_MARKED_SOLD = 12;
    public const LOT_FULL_NUM_PARSE_ERROR = 13;
    public const LOT_GROUP_INVALID = 14;
    public const LOT_GROUP_REQUIRED = 15;
    public const LOT_ITEM_NOT_FOUND = 16;
    public const LOT_ITEM_ID_NOT_SPECIFIED = 17;
    public const LOT_NUM_EXIST = 18;
    public const LOT_NUM_EXT_INVALID = 19;
    public const LOT_NUM_EXT_INVALID_LENGTH = 20;
    public const LOT_NUM_HIGHER_MAX_AVAILABLE_VALUE = 21;
    public const LOT_NUM_INVALID = 22;
    public const LOT_NUM_PREFIX_INVALID = 23;
    public const LOT_NUM_REQUIRED = 24;
    public const LOT_STATUS_ID_ALREADY_ACTIVE = 25;
    public const LOT_STATUS_ID_UNKNOWN = 26;
    public const LOT_STATUS_UNKNOWN = 27;
    public const NOTE_TO_CLERK_INVALID_ENCODING = 28;
    public const NOTE_TO_CLERK_REQUIRED = 29;
    public const ORDER_INVALID = 30;
    public const PUBLISH_DATE_INVALID = 31;
    public const QUANTITY_INVALID = 32;
    public const QUANTITY_REQUIRED = 33;
    public const QUANTITY_DIGITS_INVALID = 106;
    public const QUANTITY_DIGITS_REQUIRED = 107;
    public const SEO_URL_INVALID_ENCODING = 34;
    public const START_BIDDING_DATE_INVALID = 35;
    public const START_BIDDING_DATE_LATER_START_CLOSING_DATE = 36;
    public const TIMEZONE_UNKNOWN = 37;
    public const UNPUBLISH_DATE_INVALID = 38;
    public const START_CLOSING_DATE_INVALID = 39;
    public const START_CLOSING_DATE_REQUIRED = 40;
    public const SYNC_KEY_EXIST = 57;
    public const BULK_MASTER_LOT_START_BIDDING_DATE_NOT_EDITABLE = 41;
    public const BULK_MASTER_LOT_START_CLOSING_DATE_NOT_EDITABLE = 42;
    public const BULK_GROUP_EXCLUDE_BUY_NOW = 43;
    public const BUY_NOW_EXCLUDE_BULK_GROUP = 44;
    public const CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID = 45;
    public const CONSIGNOR_COMMISSION_ID_INVALID = 46;
    public const CONSIGNOR_COMMISSION_RANGE_INVALID = 47;
    public const CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID = 48;
    public const CONSIGNOR_SOLD_FEE_ID_INVALID = 49;
    public const CONSIGNOR_SOLD_FEE_RANGE_INVALID = 50;
    public const CONSIGNOR_SOLD_FEE_REFERENCE_INVALID = 51;
    public const CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID = 52;
    public const CONSIGNOR_UNSOLD_FEE_ID_INVALID = 53;
    public const CONSIGNOR_UNSOLD_FEE_RANGE_INVALID = 54;
    public const CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID = 55;
    public const LOT_STATUS_REQUIRED = 58;
    public const LOT_STATUS_ID_REQUIRED = 59;
    public const TIMEZONE_REQUIRED = 60;
    public const PUBLISH_DATE_REQUIRED = 61;
    public const UNPUBLISH_DATE_REQUIRED = 62;
    public const START_BIDDING_DATE_REQUIRED = 63;
    public const END_PREBIDDING_DATE_REQUIRED = 64;
    public const SEO_URL_REQUIRED = 65;
    public const TERMS_AND_CONDITIONS_REQUIRED = 66;
    public const BULK_GROUP_REQUIRED = 67;
    public const CONSIGNOR_COMMISSION_REQUIRED = 109;
    public const CONSIGNOR_SOLD_FEE_REQUIRED = 110;
    public const CONSIGNOR_UNSOLD_FEE_REQUIRED = 111;
    public const BUY_NOW_SELECT_QUANTITY_WITH_QUANTITY_DIGITS_NOT_ALLOWED = 112;
    public const BUY_NOW_SELECT_QUANTITY_FOR_LIVE_OR_HYBRID_AUCTION_NOT_ALLOWED = 113;
    public const BUY_NOW_SELECT_QUANTITY_FOR_REVERSE_AUCTION_NOT_ALLOWED = 114;
    public const HP_TAX_SCHEMA_ID_INVALID = 115;
    public const HP_TAX_SCHEMA_ID_REQUIRED = 116;
    public const HP_TAX_SCHEMA_COUNTRY_MISMATCH = 121;
    public const BP_TAX_SCHEMA_ID_INVALID = 117;
    public const BP_TAX_SCHEMA_ID_REQUIRED = 118;
    public const BP_TAX_SCHEMA_COUNTRY_MISMATCH = 122;

    public const LOT_STATUS_HIDDEN = 68;
    public const LOT_STATUS_ID_HIDDEN = 69;
    public const TIMEZONE_HIDDEN = 70;
    public const PUBLISH_DATE_HIDDEN = 71;
    public const UNPUBLISH_DATE_HIDDEN = 72;
    public const START_BIDDING_DATE_HIDDEN = 73;
    public const END_PREBIDDING_DATE_HIDDEN = 74;
    public const START_CLOSING_DATE_HIDDEN = 75;
    public const LOT_NUMBER_HIDDEN = 76;
    public const LOT_NUMBER_EXT_HIDDEN = 77;
    public const LOT_NUMBER_PREFIX_HIDDEN = 78;
    public const SEO_URL_HIDDEN = 79;
    public const LOT_GROUP_HIDDEN = 80;
    public const QUANTITY_HIDDEN = 81;
    public const QUANTITY_DIGITS_HIDDEN = 108;
    public const QUANTITY_X_MONEY_HIDDEN = 82;
    public const TERMS_AND_CONDITIONS_HIDDEN = 83;
    public const LISTING_ONLY_HIDDEN = 84;
    public const BUY_NOW_AMOUNT_HIDDEN = 85;
    public const BUY_NOW_SELECT_QUANTITY_HIDDEN = 86;
    public const FEATURED_HIDDEN = 87;
    public const CLERK_NOTE_HIDDEN = 88;
    public const GENERAL_NOTE_HIDDEN = 89;
    public const BULK_GROUP_CONTROL_HIDDEN = 90;
    public const BULK_WIN_BID_DISTRIBUTION_HIDDEN = 91;
    public const CONSIGNOR_COMMISSION_CALCULATION_METHOD_HIDDEN = 92;
    public const CONSIGNOR_COMMISSION_ID_HIDDEN = 93;
    public const CONSIGNOR_COMMISSION_RANGES_HIDDEN = 94;
    public const CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_HIDDEN = 95;
    public const CONSIGNOR_SOLD_FEE_ID_HIDDEN = 96;
    public const CONSIGNOR_SOLD_FEE_RANGES_HIDDEN = 97;
    public const CONSIGNOR_SOLD_FEE_REFERENCE_HIDDEN = 98;
    public const CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_HIDDEN = 99;
    public const CONSIGNOR_UNSOLD_FEE_ID_HIDDEN = 100;
    public const CONSIGNOR_UNSOLD_FEE_RANGES_HIDDEN = 101;
    public const CONSIGNOR_UNSOLD_FEE_REFERENCE_HIDDEN = 102;
    public const LOT_FULL_NUMBER_HIDDEN = 103;
    public const BEST_OFFER_HIDDEN = 104;
    public const NO_BIDDING_HIDDEN = 105;
    public const HP_TAX_SCHEMA_ID_HIDDEN = 119;
    public const BP_TAX_SCHEMA_ID_HIDDEN = 120;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
