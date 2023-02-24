<?php

namespace Sam\Core\Constants\Csv;

use Sam\Core\Constants;

/**
 * Class User
 * @package Sam\Core\Constants\Csv
 */
class User
{
    public const ADDITIONAL_BP_PERCENTAGE_FOR_INTERNET_BUYERS_HYBRID = 'additionalBpInternetHybrid';
    public const ADDITIONAL_BP_PERCENTAGE_FOR_INTERNET_BUYERS_LIVE = 'additionalBpInternetLive';
    public const AGENT = 'bidderAgent';
    public const APPLY_TAX_TO = 'taxApplicationName';
    public const ARCHIVE_AUCTION = 'archiveAuction';
    public const ASSISTANT_CLERK = 'auctioneerScreen';
    public const AVAILABLE_LOTS = 'availableLots';
    public const BANK_ACCOUNT_NAME = 'billingBankAccountName';
    public const BANK_ACCOUNT_NO = 'billingBankAccountNumber';
    public const BANK_ACCOUNT_TYPE = 'billingBankAccountType';
    public const BANK_NAME = 'billingBankName';
    public const BANK_ROUTING_NO = 'billingBankRoutingNumber';
    public const BID_INCREMENTS = 'bidIncrements';
    public const BIDDER_NO = 'bidderNo';   // for auction bidder import
    public const BIDDERS = 'bidders';
    public const BILLING_ADDRESS = 'billingAddress';
    public const BILLING_ADDRESS_2 = 'billingAddress2';
    public const BILLING_ADDRESS_3 = 'billingAddress3';
    public const BILLING_CITY = 'billingCity';
    public const BILLING_COMPANY_NAME = 'billingCompanyName';
    public const BILLING_CONTACT_TYPE = 'billingContactType';
    public const BILLING_COUNTRY = 'billingCountry';
    public const BILLING_FAX = 'billingFax';
    public const BILLING_FIRST_NAME = 'billingFirstName';
    public const BILLING_LAST_NAME = 'billingLastName';
    public const BILLING_PHONE = 'billingPhone';
    public const BILLING_STATE = 'billingState';
    public const BILLING_ZIP = 'billingZip';
    public const BUYER_SALES_TAX = 'salesTax';
    public const BUYERS_PREMIUM = 'buyersPremium';
    public const BP_CALCULATION_HYBRID = 'bpRangeCalculationHybrid';
    public const BP_CALCULATION_LIVE = 'bpRangeCalculationLive';
    public const BP_CALCULATION_TIMED = 'bpRangeCalculationTimed';
    public const BP_RANGES_HYBRID = 'buyersPremiumsHybrid';
    public const CONSIGNOR_COMMISSION_RANGES = 'consignorCommissionRanges';
    public const CONSIGNOR_COMMISSION_ID = 'consignorCommissionId';
    public const CONSIGNOR_COMMISSION_CALCULATION_METHOD = 'consignorCommissionCalculationMethod';
    public const CONSIGNOR_SOLD_FEE_RANGES = 'consignorSoldFeeRanges';
    public const CONSIGNOR_SOLD_FEE_ID = 'consignorSoldFeeId';
    public const CONSIGNOR_SOLD_FEE_CALCULATION_METHOD = 'consignorSoldFeeCalculationMethod';
    public const CONSIGNOR_SOLD_FEE_REFERENCE = 'consignorSoldFeeReference';
    public const CONSIGNOR_UNSOLD_FEE_RANGES = 'consignorUnsoldFeeRanges';
    public const CONSIGNOR_UNSOLD_FEE_ID = 'consignorUnsoldFeeId';
    public const CONSIGNOR_UNSOLD_FEE_CALCULATION_METHOD = 'consignorUnsoldFeeCalculationMethod';
    public const CONSIGNOR_UNSOLD_FEE_REFERENCE = 'consignorUnsoldFeeReference';
    public const BP_RANGES_LIVE = 'buyersPremiumsLive';
    public const BP_RANGES_TIMED = 'buyersPremiumsTimed';
    public const CC_EXP_DATE = 'billingCcExpDate';
    public const CC_NUMBER = 'billingCcNumber';
    public const CC_TYPE = 'billingCcType';
    public const COMPANY_NAME = 'companyName';
    public const CONSIGNOR_BUYER_TAX_BP = 'consignorSalesTaxBP';
    public const CONSIGNOR_BUYER_TAX_HP = 'consignorSalesTaxHP';
    public const CONSIGNOR_BUYER_TAX_PERCENTAGE = 'consignorSalesTax';
    public const CONSIGNOR_BUYER_TAX_SERVICES = 'consignorSalesTaxServices';
    public const CONSIGNOR_PRIVILEGES = 'consignor';
    public const CONSIGNOR_TAX_COMMISSION = 'consignorTaxCommission';
    public const CONSIGNOR_TAX_PERCENTAGE = 'consignorTax';
    public const CONSIGNOR_TAX_HP = 'consignorTaxHP';
    public const CONSIGNOR_TAX_HP_INCLUSIVE_OR_EXCLUSIVE = 'exclusive';
    public const CONSIGNOR_TAX_SERVICES = 'consignorTaxServices';
    public const CUSTOMER_NO = 'customerNo';
    public const DELETE_AUCTION = 'deleteAuction';
    public const DELETE_USER = 'deleteUser';
    public const EMAIL = 'email';
    public const FIRST_NAME = 'firstName';
    public const FLAG = 'flag';
    public const HAS_CREDIT_CARD = 'billingUseCard';
    public const HOUSE_BIDDER = 'bidderHouse';
    public const IDENTIFICATION = 'identification';
    public const IDENTIFICATION_TYPE = 'identificationType';
    public const INFORMATION = 'information';
    public const IP_USED = 'ipUsed';
    public const IS_ADMIN = 'admin';
    public const IS_BIDDER = 'bidder';
    public const IS_PREFERRED_BIDDER = 'bidderPreferred';
    public const LAST_BID_DATE = 'lastBidDate';
    public const LAST_INVOICE_DATE = 'lastInvoiceDate';
    public const LAST_LOGIN_DATE = 'lastLoginDate';
    public const LAST_NAME = 'lastName';
    public const LAST_PAYMENT_DATE = 'lastPaymentDate';
    public const LAST_WIN_DATE = 'lastWinDate';
    public const LOCATION = 'location';
    public const LOCATION_ADDRESS = 'locationAddress';
    public const LOCATION_COUNTRY = 'locationCountry';
    public const LOCATION_CITY = 'locationCity';
    public const LOCATION_COUNTY = 'locationCounty';
    public const LOCATION_LOGO = 'locationLogo';
    public const LOCATION_STATE = 'locationState';
    public const LOCATION_ZIP = 'locationZip';
    public const LOTS = 'lots';
    public const MAKE_PERMANENT_BIDDER_NO = 'usePermanentBidderno';
    public const MANAGE_ALL_AUCTIONS = 'manageAllAuctions';
    public const MANAGE_AUCTIONS = 'manageAuctions';
    public const MANAGE_CC_INFO = 'manageCCInfo';
    public const MANAGE_INVENTORY = 'manageInventory';
    public const MANAGE_INVOICES = 'manageInvoices';
    public const MANAGE_SETTINGS = 'manageSettings';
    public const MANAGE_SETTLEMENTS = 'manageConsignorSettlements';
    public const MANAGE_USERS = 'manageUsers';
    public const NEWSLETTER = 'newsletter';
    public const NOTES = 'note';
    public const PASSWORD = 'pword';
    public const PAYMENT_INFO = 'consignorPaymentInfo';
    public const PERMISSIONS = 'permissions';
    public const CREATE_BIDDER = 'createBidder';
    public const PHONE = 'phone';
    public const PHONE_TYPE = 'phoneType';
    public const PROJECTOR = 'projector';
    public const PUBLISH = 'publish';
    public const REFERRER = 'referrer';
    public const REFERRER_HOST = 'referrerHost';
    public const REGISTRATION_DATE = 'registrationDate';
    public const REMAINING_USERS = 'remainingUsers';
    public const REPORTS = 'reports';
    public const RESET_AUCTION = 'resetAuction';
    public const RUN_LIVE_AUCTION = 'runLiveAuction';
    public const SALES_COMMISSION_STEPDOWN = 'adminSalesCommissionStepdown';
    public const SALES_CONSIGNMENT_COMMISSION = 'salesCommissions';
    public const SALES_STAFF = 'salesStaff';
    public const SHIPPING_ADDRESS = 'shippingAddress';
    public const SHIPPING_ADDRESS_2 = 'shippingAddressLn2';
    public const SHIPPING_ADDRESS_3 = 'shippingAddressLn3';
    public const SHIPPING_CITY = 'shippingCity';
    public const SHIPPING_COMPANY_NAME = 'shippingCompanyName';
    public const SHIPPING_CONTACT_TYPE = 'shippingContactType';
    public const SHIPPING_COUNTRY = 'shippingCountry';
    public const SHIPPING_FAX = 'shippingFax';
    public const SHIPPING_FIRST_NAME = 'shippingFirstName';
    public const SHIPPING_LAST_NAME = 'shippingLastName';
    public const SHIPPING_PHONE = 'shippingPhone';
    public const SHIPPING_STATE = 'shippingState';
    public const SHIPPING_ZIP = 'shippingZip';
    public const SUPERADMIN = 'superadmin';
    public const TEXT_ALERT = 'sendTextAlerts';
    public const USER_BULK_EXPORT = 'bulkUserExport';
    public const USER_PASSWORDS = 'userPasswords';
    public const USER_PRIVILEGES = 'userPrivileges';
    public const USERNAME = 'username';

    // csv values for consignor.consignor_tax_hp_type
    public const TAX_HP_EXCLUSIVE = 'E';
    public const TAX_HP_INCLUSIVE = 'I';
    /** @var string[] */
    public static array $consignorTaxHpValues = [
        Constants\Consignor::TAX_HP_EXCLUSIVE => self::TAX_HP_EXCLUSIVE,
        Constants\Consignor::TAX_HP_INCLUSIVE => self::TAX_HP_INCLUSIVE,
    ];
}
