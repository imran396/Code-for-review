<?php
/**
 * Keys for optionals used for value-dependency injection.
 * Here is the list for common names, that are used in our services. Please follow it for following naming connascence principle.
 * Add here names when you introduce new one and it looks like common to be used in more than one service, e.g. auction parameters, installation options, entities.
 * ATTENTION! These constants are internal for using in services. Don't use these constants in caller code for defining optional values.
 * Caller code should operate with constants supplied by concrete service, they are prefixed by "OP_".
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Service\Optional;

/**
 * Class OptionalKeyConstants
 * @package Sam\Core\Service\Optional
 */
class OptionalKeyConstants
{
    public const KEY_ACCOUNT = 'account'; // Account
    public const KEY_ACCOUNT_ID = 'accountId'; // int
    public const KEY_ALLOWED_TAGS = 'allowedTags'; // string[]
    public const KEY_ALL_USER_REQUIRE_CC_AUTH = 'allUserRequireCcAuth'; // bool
    public const KEY_APP_HTTP_HOST = 'appHttpHost'; // string
    public const KEY_AUCTION = 'auction'; // Auction
    public const KEY_AUCTION_DOMAIN_MODE = 'auctionDomainMode'; // string
    public const KEY_AUCTION_INFO_LINK = 'auctionInfoLink'; // string
    public const KEY_AUCTION_LOT = 'auctionLot'; // AuctionLotItem
    public const KEY_AUTO_SAVE = 'autoSave'; // bool
    public const KEY_BACK_PAGE_PARAM_URL = 'backPageParamUrl'; // string
    public const KEY_BID_TRANSACTION = 'bidTransaction'; // BidTransaction
    public const KEY_CC_PAYMENT_AUTHORIZE_NET = 'ccPaymentAuthorizeNet'; // bool
    public const KEY_CC_PAYMENT_EWAY = 'ccPaymentEway'; // bool
    public const KEY_CC_PAYMENT_NMI = 'ccPaymentNmi'; // bool
    public const KEY_CC_PAYMENT_PAY_TRACE = 'ccPaymentPayTrace'; // bool
    public const KEY_CC_PAYMENT_OPAYO = 'ccPaymentOpayo'; // bool
    public const KEY_CONSIGNOR = 'consignor'; // Consignor
    public const KEY_CONTROLLER_NAME = 'controllerName'; // string
    public const KEY_CURRENCY_SIGN = 'currencySign'; // string
    public const KEY_CURRENT_DATE_UTC = 'currentDateUtc'; // DateTimeInterface
    public const KEY_DISPLAY_ITEM_NUM = 'displayItemNum'; // bool
    public const KEY_DOMAIN_RULE = 'domainRule'; // string
    public const KEY_EMAIL_PRIORITY = 'emailPriority'; // string
    public const KEY_ERROR_MESSAGES = 'errorMessages'; // string[]
    public const KEY_FORCE_INSERT = 'forceInsert'; // bool
    public const KEY_FORCE_UPDATE = 'forceUpdate'; // bool
    public const KEY_HOST_URL = 'hostUrl'; // string
    public const KEY_HTTP_HOST = 'httpHost'; // string
    public const KEY_IMAGE_LINK_PREFIXES = 'imageLinkPrefixes'; // string[]
    public const KEY_INCLUDE_BASIC_INFO = 'includeBasicInfo'; // bool
    public const KEY_INFO_MESSAGES = 'infoMessages'; // string[]
    public const KEY_INVOICE_ITEM_SALES_TAX_APPLICATION = 'invoiceItemSalesTaxApplication';
    public const KEY_IS_ACCOUNT_PAGE = 'isAccountPage'; // bool
    public const KEY_IS_AUTHORIZED = 'isAuthorized'; // bool
    public const KEY_IS_EMAILING_ENABLED = 'isEmailingEnabled'; // bool
    public const KEY_IS_ITEM_NO_CONCATENATED = 'isItemNoConcatenated'; // bool
    public const KEY_IS_LOT_NO_CONCATENATED = 'isLotNoConcatenated'; // bool
    public const KEY_IS_MULTIPLE_TENANT = 'isMultipleTenant'; // bool
    public const KEY_IS_READ_ONLY_DB = 'isReadOnlyDb'; // bool
    public const KEY_IS_SEO_FRIENDLY_URL = 'isSeoFriendlyUrl'; // bool
    public const KEY_IS_VALID_RESELLER = 'isValidReseller'; // bool
    public const KEY_ITEM_NO_EXTENSION_SEPARATOR = 'itemNoExtensionSeparator'; // string
    public const KEY_LOT_ITEM = 'lotItem'; // LotItem
    public const KEY_LOT_NO_EXTENSION_SEPARATOR = 'lotNoExtensionSeparator'; // string
    public const KEY_LOT_NO_PREFIX_SEPARATOR = 'lotNoPrefixSeparator'; // string
    public const KEY_MAIN_ACCOUNT_ID = 'mainAccountId'; // int
    public const KEY_MANDATORY_BASIC_INFO = 'mandatoryBasicInfo'; // bool
    public const KEY_MAX_STORED_SEARCHES = 'maxStoredSearches'; // int
    public const KEY_MESSAGE_GLUE = 'messageGlue'; // string
    public const KEY_MYSQL_MAX_INT = 'mysqlMaxInt'; // int
    public const KEY_PLACE_BID_REQUIRE_CC = 'placeBidRequireCc'; // bool
    public const KEY_PORTAL_DOMAIN_AUCTION_VISIBILITY = 'portalDomainAuctionVisibility'; // string
    public const KEY_PORTAL_URL_HANDLING = 'portalUrlHandling'; // string
    public const KEY_REQUEST_URI = 'requestUri'; // string
    public const KEY_SALES_TAX = 'salesTax'; // float
    public const KEY_SALE_NO_EXTENSION_SEPARATOR = 'saleNoExtensionSeparator'; // string
    public const KEY_SAM_TAX_DEFAULT_COUNTRY = 'samTaxDefaultCountry'; // string
    public const KEY_SCHEME = 'scheme'; // string
    public const KEY_SERVER_NAME = 'serverName'; // string
    public const KEY_SETTLEMENT = 'settlement'; // Settlement
    public const KEY_SETTLEMENT_UNPAID_LOTS = 'settlementUnpaidLots'; // bool
    public const KEY_SHARE_USER_INFO = 'shareUserInfo'; // int
    public const KEY_SHOW_HIGH_EST = 'showHighEst'; // bool
    public const KEY_SHOW_LOW_EST = 'showLowEst'; // bool
    public const KEY_SHOW_MEMBER_MENU_ITEMS = 'showMemberMenuItems'; // bool
    public const KEY_SIMPLIFIED_SIGNUP = 'simplifiedSignup'; // bool
    public const KEY_STAY_ON_ACCOUNT_DOMAIN = 'stayOnAccountDomain'; // bool
    public const KEY_SUCCESS_MESSAGES = 'successMessages'; // string[]
    public const KEY_SYSTEM_ACCOUNT = 'systemAccount'; // Account
    public const KEY_TEST_AUCTION_PREFIX = 'testAuctionPrefix';
    public const KEY_TTL = 'ttl'; // int
    public const KEY_UI = 'ui'; // int
    public const KEY_USER = 'user'; // User
    public const KEY_USER_AUTHENTICATION = 'userAuthentication'; // UserAuthentication
    public const KEY_US_NUMBER_FORMATTING = 'usNumberFormatting'; // bool
    public const KEY_WARNING_MESSAGES = 'warningMessages'; // string[]
    public const KEY_GRAND_TOTAL = 'grandTotal'; //float
    public const KEY_TOTAL_PAYMENTS = 'totalPayments'; //float
    public const KEY_SUB_TOTAL = 'subTotal'; //float
    public const KEY_BALANCE_DUE = 'balanceDue'; //float
    public const KEY_TOTAL_ADDITIONAL_CHARGES = 'totalAdditionalCharges'; //float
    public const KEY_INVOICE_ITEM_TAX_CALCULATION_AMOUNTS_DTOS = 'invoiceItemTaxCalculationAmountsDtos'; //InvoiceItemTaxCalculationAmountsDto[]
    public const KEY_CALC_TOTAL = 'calcTotal'; //float

    /**
     * Keys for optional values of result status collection.
     * @var string[]
     */
    public const KEYS_OF_RESULT_STATUS_COLLECTION = [
        self::KEY_ERROR_MESSAGES,
        self::KEY_SUCCESS_MESSAGES,
        self::KEY_WARNING_MESSAGES,
        self::KEY_INFO_MESSAGES,
        self::KEY_MESSAGE_GLUE
    ];
}
