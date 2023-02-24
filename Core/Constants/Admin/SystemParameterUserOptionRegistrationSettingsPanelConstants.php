<?php
/**
 * SAM-9992: Move sections' logic to separate Panel classes at Manage settings system parameters users options page (/admin/manage-system-parameter/user-option)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Nov 28, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterUserOptionRegistrationSettingsPanelConstants
 */
class SystemParameterUserOptionRegistrationSettingsPanelConstants
{
    public const CID_CHK_STAY_ON_ACCOUNT_DOMAIN = 'uof87';
    public const CID_CHK_SIMPLE_SIGNUP = 'uof2';
    public const CID_CHK_VERIFY_EMAIL = 'uof10';
    public const CID_CHK_SEND_CONFIRMATION_LINK = 'uof72';
    public const CID_CHK_REGISTRATION_REQUIRE_CC = 'uof11';
    public const CID_CHK_ENABLE_RESELLER_REG = 'uof13';
    public const CID_CHK_SAVE_RESELLER_CERT_IN_PROFILE = 'uof86';
    public const CID_CHK_DONT_MAKE_USER_BIDDER = 'uof13a';
    public const CID_CHK_AUTO_PREFERRED = 'uof14';
    public const CID_CHK_CONSIGNOR_PAYMENT_INFO = 'uof16';
    public const CID_CHK_BILLING_OPTIONAL = 'uof17';
    public const CID_CHK_SHIPPING_OPTIONAL = 'uof18';
    public const CID_CHK_AUTO_INCREMENT_CUSTOMER_NUM = 'uof70';
    public const CID_CHK_MAKE_PERMANENT_BIDDER_NUM = 'uof71';
    public const CID_CHK_AUTO_ASSIGN_ACCOUNT_ADMIN_PRIVILEGES = 'uof76';
    public const CID_LST_DEFAULT_COUNTRY_CODE = 'uof77';
    public const CID_CHK_HIDE_COUNTRY_CODE_SELECTION = 'uof78';
    public const CID_CHK_INCLUDE_BASIC_INFO = 'uof31';
    public const CID_CHK_MANDATORY_BASIC_INFO = 'uof32';
    public const CID_CHK_INCLUDE_BILLING_INFO = 'uof33';
    public const CID_CHK_MANDATORY_BILLING_INFO = 'uof34';
    public const CID_CHK_INCLUDE_CC_INFO = 'uof35';
    public const CID_CHK_MANDATORY_CC_INFO = 'uof36';
    public const CID_CHK_INCLUDE_ACH_INFO = 'uof37';
    public const CID_CHK_MANDATORY_ACH_INFO = 'uof38';
    public const CID_LST_ON_REGISTRATION = 'uof63';
    public const CID_TXT_ON_REGISTRATION_AMOUNT = 'uof64';
    public const CID_TXT_ON_REGISTRATION_EXPIRES = 'uof65';
    public const CID_CHK_AUTO_PREFERRED_CC = 'uof51';
    public const CID_CHK_REVOKE_PREFERRED_PRIVILEGE = 'uof53';
    public const CID_CHK_INCLUDE_USER_PREF = 'uof66';
    public const CID_CHK_ENABLE_USER_COMPANY = 'uof59';
    public const CID_CHK_MANDATORY_USER_PREF = 'uof79';
    public const CID_CHK_REQUIRE_IDENTIFICATION = 'uof67';
    public const CID_CHK_AGENT_OPTION = 'uof68';
    public const CID_CHK_ENABLE_CONSIGNOR_COMPANY_CLERK = 'uof69';
    public const CID_CHK_AUTO_CONSIGNOR_PRIVILEGES = 'uof75';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_STAY_ON_ACCOUNT_DOMAIN => Constants\Setting::STAY_ON_ACCOUNT_DOMAIN,
        self::CID_CHK_SIMPLE_SIGNUP => Constants\Setting::SIMPLIFIED_SIGNUP,
        self::CID_CHK_VERIFY_EMAIL => Constants\Setting::VERIFY_EMAIL,
        self::CID_CHK_SEND_CONFIRMATION_LINK => Constants\Setting::SEND_CONFIRMATION_LINK,
        self::CID_CHK_REGISTRATION_REQUIRE_CC => Constants\Setting::REGISTRATION_REQUIRE_CC,
        self::CID_CHK_ENABLE_RESELLER_REG => Constants\Setting::ENABLE_RESELLER_REG,
        self::CID_CHK_SAVE_RESELLER_CERT_IN_PROFILE => Constants\Setting::SAVE_RESELLER_CERT_IN_PROFILE,
        self::CID_CHK_DONT_MAKE_USER_BIDDER => Constants\Setting::DONT_MAKE_USER_BIDDER,
        self::CID_CHK_AUTO_PREFERRED => Constants\Setting::AUTO_PREFERRED,
        self::CID_CHK_CONSIGNOR_PAYMENT_INFO => Constants\Setting::ENABLE_CONSIGNOR_PAYMENT_INFO,
        self::CID_CHK_BILLING_OPTIONAL => Constants\Setting::PROFILE_BILLING_OPTIONAL,
        self::CID_CHK_SHIPPING_OPTIONAL => Constants\Setting::PROFILE_SHIPPING_OPTIONAL,
        self::CID_CHK_AUTO_INCREMENT_CUSTOMER_NUM => Constants\Setting::AUTO_INCREMENT_CUSTOMER_NUM,
        self::CID_CHK_MAKE_PERMANENT_BIDDER_NUM => Constants\Setting::MAKE_PERMANENT_BIDDER_NUM,
        self::CID_CHK_AUTO_ASSIGN_ACCOUNT_ADMIN_PRIVILEGES => Constants\Setting::AUTO_ASSIGN_ACCOUNT_ADMIN_PRIVILEGES,
        self::CID_LST_DEFAULT_COUNTRY_CODE => Constants\Setting::DEFAULT_COUNTRY_CODE,
        self::CID_CHK_HIDE_COUNTRY_CODE_SELECTION => Constants\Setting::HIDE_COUNTRY_CODE_SELECTION,
        self::CID_CHK_INCLUDE_BASIC_INFO => Constants\Setting::INCLUDE_BASIC_INFO,
        self::CID_CHK_MANDATORY_BASIC_INFO => Constants\Setting::MANDATORY_BASIC_INFO,
        self::CID_CHK_INCLUDE_BILLING_INFO => Constants\Setting::INCLUDE_BILLING_INFO,
        self::CID_CHK_MANDATORY_BILLING_INFO => Constants\Setting::MANDATORY_BILLING_INFO,
        self::CID_CHK_INCLUDE_CC_INFO => Constants\Setting::INCLUDE_CC_INFO,
        self::CID_CHK_MANDATORY_CC_INFO => Constants\Setting::MANDATORY_CC_INFO,
        self::CID_CHK_INCLUDE_ACH_INFO => Constants\Setting::INCLUDE_ACH_INFO,
        self::CID_CHK_MANDATORY_ACH_INFO => Constants\Setting::MANDATORY_ACH_INFO,
        self::CID_LST_ON_REGISTRATION => Constants\Setting::ON_REGISTRATION,
        self::CID_TXT_ON_REGISTRATION_AMOUNT => Constants\Setting::ON_REGISTRATION_AMOUNT,
        self::CID_TXT_ON_REGISTRATION_EXPIRES => Constants\Setting::ON_REGISTRATION_EXPIRES,
        self::CID_CHK_AUTO_PREFERRED_CC => Constants\Setting::AUTO_PREFERRED_CREDIT_CARD,
        self::CID_CHK_REVOKE_PREFERRED_PRIVILEGE => Constants\Setting::REVOKE_PREFERRED_BIDDER,
        self::CID_CHK_ENABLE_USER_COMPANY => Constants\Setting::ENABLE_USER_COMPANY,
        self::CID_CHK_INCLUDE_USER_PREF => Constants\Setting::INCLUDE_USER_PREFERENCES,
        self::CID_CHK_MANDATORY_USER_PREF => Constants\Setting::MANDATORY_USER_PREFERENCES,
        self::CID_CHK_REQUIRE_IDENTIFICATION => Constants\Setting::REQUIRE_IDENTIFICATION,
        self::CID_CHK_AGENT_OPTION => Constants\Setting::AGENT_OPTION,
        self::CID_CHK_ENABLE_CONSIGNOR_COMPANY_CLERK => Constants\Setting::ENABLE_CONSIGNOR_COMPANY_CLERKING,
        self::CID_CHK_AUTO_CONSIGNOR_PRIVILEGES => Constants\Setting::AUTO_CONSIGNOR_PRIVILEGES,
    ];

    public const CLASS_BLK_MAKE_PARAMETER_BIDDER_NUM = 'mpbn';
    public const CLASS_BLK_ON_REGISTRATION = 'on_registration';
    public const CLASS_BLK_OPTIONAL = 'optional';
    public const CLASS_BLK_RESELLER_OPTION = 'resellerOption';
    public const CLASS_BLK_SEND_CONFIRMATION = 'send_confirmation';
    public const CLASS_BLK_SIMPLIFIED = 'simplified';
    public const CLASS_BLK_USER_PREFERENCES = 'user-preferences';
}
