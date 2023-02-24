<?php
/**
 * Result code constants.
 * Do not add any other constants to this class.
 * Assign different values for different type of result codes (success, error, warning, info).
 *
 * SAM-4989: User Entity Maker
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           5/15/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Validate\Constants;

use Sam\Core\Service\CustomizableClass;

/**
 * Class ResultCode
 * @package
 */
class ResultCode extends CustomizableClass
{
    public const ACCOUNT_ID_NOT_FOUND = 24;
    public const ACCOUNT_NO_RIGHTS = 25;
    public const ADDITIONAL_BP_INTERNET_HYBRID_INVALID = 47;
    public const ADDITIONAL_BP_INTERNET_LIVE_INVALID = 48;
    public const ADMIN_NO_SUB_PRIVILEGES_SELECTED = 73;
    public const AGENT_NOT_FOUND = 23;
    public const BILLING_ADDRESS_INVALID_ENCODING = 135;
    public const BILLING_ADDRESS_REQUIRED = 92;
    public const BILLING_ADDRESS2_INVALID_ENCODING = 136;
    public const BILLING_ADDRESS3_INVALID_ENCODING = 137;
    public const BILLING_BANK_ACCOUNT_NAME_INVALID_ENCODING = 164;
    public const BILLING_BANK_ACCOUNT_NAME_REQUIRED = 100;
    public const BILLING_BANK_ACCOUNT_NUMBER_INVALID_ENCODING = 161;
    public const BILLING_BANK_ACCOUNT_NUMBER_REQUIRED = 101;
    public const BILLING_BANK_ACCOUNT_TYPE_INVALID_ENCODING = 162;
    public const BILLING_BANK_ACCOUNT_TYPE_REQUIRED = 102;
    public const BILLING_BANK_ACCOUNT_TYPE_UNKNOWN = 10;
    public const BILLING_BANK_NAME_INVALID_ENCODING = 163;
    public const BILLING_BANK_NAME_REQUIRED = 103;
    public const BILLING_BANK_ROUTING_NUMBER_INVALID_ENCODING = 160;
    public const BILLING_BANK_ROUTING_NUMBER_REQUIRED = 104;
    public const BILLING_CC_CODE_REQUIRED = 107;
    public const BILLING_CC_EXP_DATE_INVALID = 2;
    public const BILLING_CC_EXP_DATE_INVALID_ENCODING = 159;
    public const BILLING_CC_EXP_DATE_REQUIRED = 109;
    public const BILLING_CC_NUMBER_HASH_EXIST = 7;
    public const BILLING_CC_NUMBER_HASH_INVALID = 6;
    public const BILLING_CC_NUMBER_INVALID = 4;
    public const BILLING_CC_NUMBER_INVALID_ENCODING = 157;
    public const BILLING_CC_NUMBER_REQUIRED = 108;
    public const BILLING_CC_TYPE_INVALID_ENCODING = 158;
    public const BILLING_CC_TYPE_NOT_FOUND = 3;
    public const BILLING_CC_TYPE_REQUIRED = 110;
    public const BILLING_CITY_INVALID_ENCODING = 138;
    public const BILLING_CITY_REQUIRED = 96;
    public const BILLING_COMPANY_NAME_INVALID_ENCODING = 131;
    public const BILLING_CONTACT_TYPE_INVALID_ENCODING = 130;
    public const BILLING_CONTACT_TYPE_REQUIRED = 121;
    public const BILLING_CONTACT_TYPE_UNKNOWN = 13;
    public const BILLING_COUNTRY_INVALID_ENCODING = 134;
    public const BILLING_COUNTRY_REQUIRED = 94;
    public const BILLING_COUNTRY_UNKNOWN = 5;
    public const BILLING_EMAIL_INVALID = 12;
    public const BILLING_FAX_INVALID = 9;
    public const BILLING_FIRST_NAME_INVALID_ENCODING = 132;
    public const BILLING_FIRST_NAME_REQUIRED = 97;
    public const BILLING_LAST_NAME_INVALID_ENCODING = 133;
    public const BILLING_LAST_NAME_REQUIRED = 98;
    public const BILLING_PHONE_INVALID = 8;
    public const BILLING_PHONE_REQUIRED = 99;
    public const BILLING_STATE_INVALID_ENCODING = 139;
    public const BILLING_STATE_REQUIRED = 95;
    public const BILLING_STATE_NAME_INVALID_ENCODING = 140;
    public const BILLING_STATE_UNKNOWN = 141;
    public const BILLING_ZIP_REQUIRED = 93;
    public const BP_RANGE_CALCULATION_HYBRID_UNKNOWN = 44;
    public const BP_RANGE_CALCULATION_LIVE_UNKNOWN = 45;
    public const BP_RANGE_CALCULATION_TIMED_UNKNOWN = 46;
    public const BP_RULE_NOT_FOUND = 49;
    public const BUYERS_PREMIUMS_HYBRID_VALIDATION_FAILED = 50;
    public const BUYERS_PREMIUMS_LIVE_VALIDATION_FAILED = 51;
    public const BUYERS_PREMIUMS_TIMED_VALIDATION_FAILED = 52;
    public const COLLATERAL_ACCOUNT_ID_NOT_FOUND = 181;
    public const COMPANY_NAME_INVALID_ENCODING = 128;
    public const COMPANY_NAME_REQUIRED = 118;
    public const CONSIGNOR_PAYMENT_INFO_INVALID_ENCODING = 154;
    public const CONSIGNOR_SALES_TAX_INVALID = 32;
    public const CONSIGNOR_TAX_INVALID = 31;
    public const CUSTOMER_NO_AS_BIDDER_NO_EXIST = 71;
    public const CUSTOMER_NO_EXIST = 1;
    public const CUSTOMER_NO_HIGHER_MAX_AVAILABLE_VALUE = 165;
    public const CUSTOMER_NO_INVALID = 16;
    public const CUSTOMER_NO_REQUIRED = 70;
    public const EMAIL_CONFIRM_NOT_MATCH = 115;
    public const EMAIL_CONFIRM_REQUIRED = 116;
    public const EMAIL_EXIST = 17;
    public const EMAIL_INVALID = 18;
    public const EMAIL_REQUIRED = 117;
    public const FIRST_NAME_INVALID_ENCODING = 124;
    public const FIRST_NAME_REQUIRED = 83;
    public const FLAG_UNKNOWN = 22;
    public const IDENTIFICATION_INVALID_ENCODING = 156;
    public const IDENTIFICATION_REQUIRED = 119;
    public const IDENTIFICATION_TYPE_REQUIRED = 120;
    public const IDENTIFICATION_TYPE_UNKNOWN = 37;
    public const LAST_NAME_INVALID_ENCODING = 125;
    public const LAST_NAME_REQUIRED = 84;
    public const LOCATION_ID_NOT_FOUND = 38;
    public const LOCATION_INVALID_ENCODING = 155;
    public const LOCATION_NOT_FOUND = 39;
    public const MAX_OUTSTANDING_INVALID = 41;
    public const NMI_VAULT_ID_EXIST = 15;
    public const NOTE_INVALID_ENCODING = 129;
    public const PASSWORD_CONFIRM_REQUIRED = 112;
    public const PASSWORD_INVALID = 26;
    public const PASSWORD_NOT_MATCH = 72;
    public const PASSWORD_REQUIRED = 111;
    public const PAY_TRACE_CUST_ID_EXIST = 11;
    public const PHONE_INVALID = 33;
    public const PHONE_INVALID_ENCODING = 123;
    public const PHONE_REQUIRED = 113;
    public const PHONE_TYPE_REQUIRED = 114;
    public const PHONE_TYPE_UNKNOWN = 36;
    public const REFERRER_INVALID = 40;
    public const REG_AUTH_DATE_INVALID = 35;
    public const SALES_COMMISSIONS_VALIDATION_FAILED = 78;
    public const SALES_TAX_INVALID = 43;
    public const SHIPPING_ADDRESS_INVALID_ENCODING = 147;
    public const SHIPPING_ADDRESS_REQUIRED = 87;
    public const SHIPPING_ADDRESS2_INVALID_ENCODING = 148;
    public const SHIPPING_ADDRESS3_INVALID_ENCODING = 149;
    public const SHIPPING_CITY_INVALID_ENCODING = 150;
    public const SHIPPING_CITY_REQUIRED = 105;
    public const SHIPPING_COMPANY_NAME_INVALID_ENCODING = 143;
    public const SHIPPING_CONTACT_TYPE_INVALID_ENCODING = 142;
    public const SHIPPING_CONTACT_TYPE_REQUIRED = 122;
    public const SHIPPING_CONTACT_TYPE_UNKNOWN = 30;
    public const SHIPPING_COUNTRY_INVALID_ENCODING = 146;
    public const SHIPPING_COUNTRY_REQUIRED = 88;
    public const SHIPPING_COUNTRY_UNKNOWN = 29;
    public const SHIPPING_FAX_INVALID = 28;
    public const SHIPPING_FIRST_NAME_INVALID_ENCODING = 144;
    public const SHIPPING_FIRST_NAME_REQUIRED = 85;
    public const SHIPPING_LAST_NAME_INVALID_ENCODING = 145;
    public const SHIPPING_LAST_NAME_REQUIRED = 86;
    public const SHIPPING_PHONE_INVALID = 27;
    public const SHIPPING_PHONE_REQUIRED = 91;
    public const SHIPPING_STATE_INVALID_ENCODING = 151;
    public const SHIPPING_STATE_REQUIRED = 89;
    public const SHIPPING_STATE_NAME_INVALID_ENCODING = 152;
    public const SHIPPING_STATE_UNKNOWN = 153;
    public const SHIPPING_ZIP_REQUIRED = 90;
    public const SPECIFIC_LOCATION_INVALID = 216;
    public const SPECIFIC_LOCATION_REDUNDANT = 217;
    public const SYNC_KEY_EXIST = 14;
    public const TAX_APPLICATION_INVALID = 42;
    public const TAX_APPLICATION_INVALID_ENCODING = 127;
    public const TIMEZONE_INVALID = 106;
    public const USER_STATUS_ID_UNKNOWN = 20;
    public const USER_STATUS_UNKNOWN = 21;
    public const USERNAME_EXIST = 19;
    public const USERNAME_INVALID = 69;
    public const USERNAME_INVALID_ENCODING = 126;
    public const USERNAME_REQUIRED = 68;
    public const VIEW_LANGUAGE_NOT_FOUND = 34;
    public const CONSIGNOR_COMMISSION_ID_INVALID = 170;
    public const CONSIGNOR_COMMISSION_CALCULATION_METHOD_INVALID = 171;
    public const CONSIGNOR_COMMISSION_RANGE_INVALID = 172;
    public const CONSIGNOR_SOLD_FEE_ID_INVALID = 173;
    public const CONSIGNOR_SOLD_FEE_CALCULATION_METHOD_INVALID = 174;
    public const CONSIGNOR_SOLD_FEE_REFERENCE_INVALID = 175;
    public const CONSIGNOR_SOLD_FEE_RANGE_INVALID = 176;
    public const CONSIGNOR_UNSOLD_FEE_ID_INVALID = 177;
    public const CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD_INVALID = 178;
    public const CONSIGNOR_UNSOLD_FEE_REFERENCE_INVALID = 179;
    public const CONSIGNOR_UNSOLD_FEE_RANGE_INVALID = 180;
    public const ADMIN_AND_BIDDER_CONSIGNOR_PRIVILEGE_TOGETHER_NOT_ALLOWED = 182;
    public const ADMIN_PRIVILEGES_IS_NOT_EDITABLE = 183;
    public const BIDDER_AND_CONSIGNOR_PRIVILEGES_IS_NOT_EDITABLE = 184;
    public const MANAGE_AUCTIONS_PRIVILEGE_IS_NOT_EDITABLE = 185;
    public const MANAGE_INVENTORY_PRIVILEGE_IS_NOT_EDITABLE = 186;
    public const MANAGE_USERS_PRIVILEGE_IS_NOT_EDITABLE = 187;
    public const MANAGE_INVOICES_PRIVILEGE_IS_NOT_EDITABLE = 188;
    public const MANAGE_SETTLEMENTS_PRIVILEGE_IS_NOT_EDITABLE = 189;
    public const MANAGE_SETTINGS_PRIVILEGE_IS_NOT_EDITABLE = 190;
    public const MANAGE_CC_INFO_PRIVILEGE_IS_NOT_EDITABLE = 191;
    public const SALES_STAFF_PRIVILEGE_IS_NOT_EDITABLE = 192;
    public const MANAGE_REPORTS_PRIVILEGE_IS_NOT_EDITABLE = 193;
    public const SUPERADMIN_PRIVILEGE_IS_NOT_EDITABLE = 194;
    public const SUB_AUCTION_MANAGE_ALL_PRIVILEGE_IS_NOT_EDITABLE = 195;
    public const SUB_AUCTION_DELETE_PRIVILEGE_IS_NOT_EDITABLE = 196;
    public const SUB_AUCTION_ARCHIVE_PRIVILEGE_IS_NOT_EDITABLE = 197;
    public const SUB_AUCTION_RESET_PRIVILEGE_IS_NOT_EDITABLE = 198;
    public const SUB_AUCTION_INFORMATION_PRIVILEGE_IS_NOT_EDITABLE = 199;
    public const SUB_AUCTION_PUBLISH_PRIVILEGE_IS_NOT_EDITABLE = 200;
    public const SUB_AUCTION_LOT_LIST_PRIVILEGE_IS_NOT_EDITABLE = 201;
    public const SUB_AUCTION_AVAILABLE_LOT_PRIVILEGE_IS_NOT_EDITABLE = 202;
    public const SUB_AUCTION_BIDDER_PRIVILEGE_IS_NOT_EDITABLE = 203;
    public const SUB_AUCTION_REMAINING_USER_PRIVILEGE_IS_NOT_EDITABLE = 204;
    public const SUB_AUCTION_RUN_LIVE_PRIVILEGE_IS_NOT_EDITABLE = 205;
    public const SUB_AUCTION_AUCTIONEER_PRIVILEGE_IS_NOT_EDITABLE = 206;
    public const SUB_AUCTION_PROJECTOR_PRIVILEGE_IS_NOT_EDITABLE = 207;
    public const SUB_AUCTION_BID_INCREMENT_PRIVILEGE_IS_NOT_EDITABLE = 208;
    public const SUB_AUCTION_BUYER_PREMIUM_PRIVILEGE_IS_NOT_EDITABLE = 209;
    public const SUB_AUCTION_PERMISSION_PRIVILEGE_IS_NOT_EDITABLE = 210;
    public const SUB_USER_BULK_EXPORT_PRIVILEGE_IS_NOT_EDITABLE = 211;
    public const SUB_USER_DELETE_PRIVILEGE_IS_NOT_EDITABLE = 220;
    public const SUB_USER_PASSWORD_PRIVILEGE_IS_NOT_EDITABLE = 212;
    public const SUB_USER_PRIVILEGE_PRIVILEGE_IS_NOT_EDITABLE = 213;
    public const SUB_AUCTION_CREATE_BIDDER_PRIVILEGE_IS_NOT_EDITABLE = 214;
    public const CROSS_DOMAIN_ADMIN_AT_PORTAL_ACCOUNT_NOT_APPLICABLE = 215;
    public const BP_RULE_WITH_INDIVIDUAL_BP_CAN_NOT_BE_ASSIGNED_TOGETHER = 218;
    public const CANNOT_LOCK_USERNAME = 219;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
