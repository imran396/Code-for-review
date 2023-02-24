<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Core\Constants;

use Sam\Core\Constants;
use SettingAccessPermission;
use SettingAuction;
use SettingBillingAuthorizeNet;
use SettingBillingEway;
use SettingBillingNmi;
use SettingBillingOpayo;
use SettingBillingPayTrace;
use SettingBillingPaypal;
use SettingBillingSmartPay;
use SettingInvoice;
use SettingPassword;
use SettingRtb;
use SettingSeo;
use SettingSettlement;
use SettingSettlementCheck;
use SettingShippingAuctionInc;
use SettingSms;
use SettingSmtp;
use SettingSystem;
use SettingUi;
use SettingUser;

class Setting
{
    public const AUCTION_LISTING_PAGE_DESC = 'AuctionListingPageDesc';
    public const AUCTION_LISTING_PAGE_KEYWORD = 'AuctionListingPageKeyword';
    public const AUCTION_LISTING_PAGE_TITLE = 'AuctionListingPageTitle';
    public const AUCTION_PAGE_DESC = 'AuctionPageDesc';
    public const AUCTION_PAGE_KEYWORD = 'AuctionPageKeyword';
    public const AUCTION_PAGE_TITLE = 'AuctionPageTitle';
    public const AUCTION_SEO_URL_TEMPLATE = 'AuctionSeoUrlTemplate';
    public const LOGIN_DESC = 'LoginDesc';
    public const LOGIN_KEYWORD = 'LoginKeyword';
    public const LOGIN_TITLE = 'LoginTitle';
    public const LOT_PAGE_DESC = 'LotPageDesc';
    public const LOT_PAGE_KEYWORD = 'LotPageKeyword';
    public const LOT_PAGE_TITLE = 'LotPageTitle';
    public const LOT_SEO_URL_TEMPLATE = 'LotSeoUrlTemplate';
    public const SEARCH_RESULTS_PAGE_DESC = 'SearchResultsPageDesc';
    public const SEARCH_RESULTS_PAGE_KEYWORD = 'SearchResultsPageKeyword';
    public const SEARCH_RESULTS_PAGE_TITLE = 'SearchResultsPageTitle';
    public const SIGNUP_DESC = 'SignupDesc';
    public const SIGNUP_KEYWORD = 'SignupKeyword';
    public const SIGNUP_TITLE = 'SignupTitle';
    public const ABOVE_RESERVE = 'AboveReserve';
    public const ABOVE_STARTING_BID = 'AboveStartingBid';
    public const ABSENTEE_BID_LOT_NOTIFICATION = 'AbsenteeBidLotNotification';
    public const ABSENTEE_BIDS_DISPLAY = 'AbsenteeBidsDisplay';
    public const ADD_BIDS_TO_WATCHLIST = 'AddBidsToWatchlist';
    public const ADD_DESCRIPTION_IN_LOT_NAME_COLUMN = 'AddDescriptionInLotNameColumn';
    public const ALL_USER_REQUIRE_CC_AUTH = 'AllUserRequireCcAuth';
    public const ALLOW_ANYONE_TO_TELL_A_FRIEND = 'AllowAnyoneToTellAFriend';
    public const ALLOW_FORCE_BID = 'AllowForceBid';
    public const ALLOW_MANUAL_BIDDER_FOR_FLAGGED_BIDDERS = 'AllowManualBidderForFlaggedBidders';
    public const ALLOW_MULTIBIDS = 'AllowMultibids';
    public const ASSIGNED_LOTS_RESTRICTION = 'AssignedLotsRestriction';
    public const AUCTION_DOMAIN_MODE = 'AuctionDomainMode';
    public const AUCTION_LINKS_TO = 'AuctionLinksTo';
    public const BID_TRACKING_CODE = 'BidTrackingCode';
    public const BLACKLIST_PHRASE = 'BlacklistPhrase';
    public const BLOCK_SOLD_LOTS = 'BlockSoldLots';
    public const BUY_NOW_UNSOLD = 'BuyNowUnsold';
    public const CONDITIONAL_SALES = 'ConditionalSales';
    public const CONFIRM_ADDRESS_SALE = 'ConfirmAddressSale';
    public const CONFIRM_MULTIBIDS = 'ConfirmMultibids';
    public const CONFIRM_MULTIBIDS_TEXT = 'ConfirmMultibidsText';
    public const CONFIRM_TERMS_AND_CONDITIONS_SALE = 'ConfirmTermsAndConditionsSale';
    public const CONFIRM_TIMED_BID = 'ConfirmTimedBid';
    public const CONFIRM_TIMED_BID_TEXT = 'ConfirmTimedBidText';
    public const DISPLAY_BIDDER_INFO = 'DisplayBidderInfo';
    public const DISPLAY_ITEM_NUM = 'DisplayItemNum';
    public const DISPLAY_QUANTITY = 'DisplayQuantity';
    public const ENABLE_SECOND_CHANCE = 'EnableSecondChance';
    public const EXTEND_TIME_TIMED = 'ExtendTimeTimed';
    public const GA_BID_TRACKING = 'GaBidTracking';
    public const HAMMER_PRICE_BP = 'HammerPriceBp';
    public const HIDE_BIDDER_NUMBER = 'HideBidderNumber';
    public const HIDE_MOVETOSALE = 'HideMovetosale';
    public const HIDE_UNSOLD_LOTS = 'HideUnsoldLots';
    public const INLINE_BID_CONFIRM = 'InlineBidConfirm';
    public const ITEM_NUM_LOCK = 'ItemNumLock';
    public const LOT_STATUS = 'LotStatus';
    public const MAX_STORED_SEARCHES = 'MaxStoredSearches';
    public const NEXT_BID_BUTTON = 'NextBidButton';
    public const NO_LOWER_MAXBID = 'NoLowerMaxbid';
    public const NOTIFY_ABSENTEE_BIDDERS = 'NotifyAbsenteeBidders';
    public const ON_AUCTION_REGISTRATION = 'OnAuctionRegistration';
    public const ON_AUCTION_REGISTRATION_AMOUNT = 'OnAuctionRegistrationAmount';
    public const ON_AUCTION_REGISTRATION_AUTO = 'OnAuctionRegistrationAuto';
    public const ON_AUCTION_REGISTRATION_EXPIRES = 'OnAuctionRegistrationExpires';
    public const ONLY_ONE_REG_EMAIL = 'OnlyOneRegEmail';
    public const PAYMENT_TRACKING_CODE = 'PaymentTrackingCode';
    public const PLACE_BID_REQUIRE_CC = 'PlaceBidRequireCc';
    public const QUANTITY_DIGITS = 'QuantityDigits';
    public const REG_CONFIRM_AUTO_APPROVE = 'RegConfirmAutoApprove';
    public const REG_CONFIRM_PAGE = 'RegConfirmPage';
    public const REG_CONFIRM_PAGE_CONTENT = 'RegConfirmPageContent';
    public const REG_USE_HIGH_BIDDER = 'RegUseHighBidder';
    public const REQUIRE_ON_INC_BIDS = 'RequireOnIncBids';
    public const REQUIRE_REENTER_CC = 'RequireReenterCc';
    public const RESERVE_MET_NOTICE = 'ReserveMetNotice';
    public const RESERVE_NOT_MET_NOTICE = 'ReserveNotMetNotice';
    public const SEND_RESULTS_ONCE = 'SendResultsOnce';
    public const SHIPPING_INFO_BOX = 'ShippingInfoBox';
    public const SHOW_AUCTION_STARTS_ENDING = 'ShowAuctionStartsEnding';
    public const SHOW_COUNTDOWN_SECONDS = 'ShowCountdownSeconds';
    public const SHOW_HIGH_EST = 'ShowHighEst';
    public const SHOW_LOW_EST = 'ShowLowEst';
    public const SHOW_WINNER_IN_CATALOG = 'ShowWinnerInCatalog';
    public const TAKE_MAX_BIDS_UNDER_RESERVE = 'TakeMaxBidsUnderReserve';
    public const TELL_A_FRIEND = 'TellAFriend';
    public const TIMED_ABOVE_RESERVE = 'TimedAboveReserve';
    public const TIMED_ABOVE_STARTING_BID = 'TimedAboveStartingBid';
    public const USE_ALTERNATE_PDF_CATALOG = 'UseAlternatePdfCatalog';
    public const VISIBLE_AUCTION_STATUSES = 'VisibleAuctionStatuses';
    public const ACH_PAYMENT = 'AchPayment';
    public const AUTH_NET_ACCOUNT_TYPE = 'AuthNetAccountType';
    public const AUTH_NET_CIM = 'AuthNetCim';
    public const AUTH_NET_LOGIN = 'AuthNetLogin';
    public const AUTH_NET_MODE = 'AuthNetMode';
    public const AUTH_NET_TRANKEY = 'AuthNetTrankey';
    public const CC_PAYMENT = 'CcPayment';
    public const CC_PAYMENT_EWAY = 'CcPaymentEway';
    public const EWAY_ACCOUNT_TYPE = 'EwayAccountType';
    public const EWAY_API_KEY = 'EwayApiKey';
    public const EWAY_ENCRYPTION_KEY = 'EwayEncryptionKey';
    public const EWAY_PASSWORD = 'EwayPassword';
    public const ACH_PAYMENT_NMI = 'AchPaymentNmi';
    public const CC_PAYMENT_NMI = 'CcPaymentNmi';
    public const NMI_MODE = 'NmiMode';
    public const NMI_PASSWORD = 'NmiPassword';
    public const NMI_USERNAME = 'NmiUsername';
    public const NMI_VAULT = 'NmiVault';
    public const NMI_VAULT_OPTION = 'NmiVaultOption';
    public const ACH_PAYMENT_OPAYO = 'AchPaymentOpayo';
    public const CC_PAYMENT_OPAYO = 'CcPaymentOpayo';
    public const OPAYO_3DSECURE = 'Opayo3dsecure';
    public const OPAYO_AUTH_TRANSACTION_TYPE = 'OpayoAuthTransactionType';
    public const OPAYO_AVSCV2 = 'OpayoAvscv2';
    public const OPAYO_CURRENCY = 'OpayoCurrency';
    public const OPAYO_MODE = 'OpayoMode';
    public const OPAYO_SEND_EMAIL = 'OpayoSendEmail';
    public const OPAYO_TOKEN = 'OpayoToken';
    public const OPAYO_VENDOR_NAME = 'OpayoVendorName';
    public const ENABLE_PAYPAL_PAYMENTS = 'EnablePaypalPayments';
    public const PAYPAL_ACCOUNT_TYPE = 'PaypalAccountType';
    public const PAYPAL_BN_CODE = 'PaypalBnCode';
    public const PAYPAL_EMAIL = 'PaypalEmail';
    public const PAYPAL_IDENTITY_TOKEN = 'PaypalIdentityToken';
    public const ENABLE_SMART_PAYMENTS = 'EnableSmartPayments';
    public const SMART_PAY_ACCOUNT_TYPE = 'SmartPayAccountType';
    public const SMART_PAY_MERCHANT_ACCOUNT = 'SmartPayMerchantAccount';
    public const SMART_PAY_MERCHANT_MODE = 'SmartPayMerchantMode';
    public const SMART_PAY_MODE = 'SmartPayMode';
    public const SMART_PAY_SHARED_SECRET = 'SmartPaySharedSecret';
    public const SMART_PAY_SKIN_CODE = 'SmartPaySkinCode';
    public const ACH_PAYMENT_PAY_TRACE = 'AchPaymentPayTrace';
    public const CC_PAYMENT_PAY_TRACE = 'CcPaymentPayTrace';
    public const PAY_TRACE_CIM = 'PayTraceCim';
    public const PAY_TRACE_MODE = 'PayTraceMode';
    public const PAY_TRACE_PASSWORD = 'PayTracePassword';
    public const PAY_TRACE_USERNAME = 'PayTraceUsername';
    public const AUTO_INVOICE = 'AutoInvoice';
    public const CASH_DISCOUNT = 'CashDiscount';
    public const CATEGORY_IN_INVOICE = 'CategoryInInvoice';
    public const DEFAULT_INVOICE_NOTES = 'DefaultInvoiceNotes';
    public const DEFAULT_LOT_ITEM_NO_TAX_OOS = 'DefaultLotItemNoTaxOos';
    public const DEFAULT_POST_AUC_IMPORT_PREMIUM = 'DefaultPostAucImportPremium';
    public const INVOICE_BP_TAX_SCHEMA_ID = 'InvoiceBpTaxSchemaId';
    public const INVOICE_HP_TAX_SCHEMA_ID = 'InvoiceHpTaxSchemaId';
    public const INVOICE_IDENTIFICATION = 'InvoiceIdentification';
    public const INVOICE_ITEM_DESCRIPTION = 'InvoiceItemDescription';
    public const INVOICE_ITEM_SALES_TAX = 'InvoiceItemSalesTax';
    public const INVOICE_ITEM_SALES_TAX_APPLICATION = 'InvoiceItemSalesTaxApplication';
    public const INVOICE_ITEM_SEPARATE_TAX = 'InvoiceItemSeparateTax';
    public const INVOICE_LOGO = 'InvoiceLogo';
    public const INVOICE_SERVICES_TAX_SCHEMA_ID = 'InvoiceServicesTaxSchemaId';
    public const INVOICE_TAX_DESIGNATION_STRATEGY = 'InvoiceTaxDesignationStrategy';
    public const KEEP_DECIMAL_INVOICE = 'KeepDecimalInvoice';
    public const MULTIPLE_SALE_INVOICE = 'MultipleSaleInvoice';
    public const ONE_SALE_GROUPED_INVOICE = 'OneSaleGroupedInvoice';
    public const PROCESSING_CHARGE = 'ProcessingCharge';
    public const QUANTITY_IN_INVOICE = 'QuantityInInvoice';
    public const RENDER_LOT_CUSTOM_FIELDS_IN_SEPARATE_ROW_FOR_INVOICE = 'RenderLotCustomFieldsInSeparateRowForInvoice';
    public const SALES_TAX = 'SalesTax';
    public const SALES_TAX_SERVICES = 'SalesTaxServices';
    public const SAM_TAX = 'SamTax';
    public const SAM_TAX_DEFAULT_COUNTRY = 'SamTaxDefaultCountry';
    public const SHIPPING_CHARGE = 'ShippingCharge';
    public const FAILED_LOGIN_ATTEMPT_LOCKOUT_TIMEOUT = 'FailedLoginAttemptLockoutTimeout';
    public const FAILED_LOGIN_ATTEMPT_TIME_INCREMENT = 'FailedLoginAttemptTimeIncrement';
    public const PW_HISTORY_REPEAT = 'PwHistoryRepeat';
    public const PW_MAX_CONSEQ_LETTER = 'PwMaxConseqLetter';
    public const PW_MAX_CONSEQ_NUM = 'PwMaxConseqNum';
    public const PW_MAX_SEQ_LETTER = 'PwMaxSeqLetter';
    public const PW_MAX_SEQ_NUM = 'PwMaxSeqNum';
    public const PW_MIN_LEN = 'PwMinLen';
    public const PW_MIN_LETTER = 'PwMinLetter';
    public const PW_MIN_NUM = 'PwMinNum';
    public const PW_MIN_SPECIAL = 'PwMinSpecial';
    public const PW_RENEW = 'PwRenew';
    public const PW_REQ_MIXED_CASE = 'PwReqMixedCase';
    public const PW_TMP_TIMEOUT = 'PwTmpTimeout';
    public const CHARGE_CONSIGNOR_COMMISSION = 'ChargeConsignorCommission';
    public const CONSIGNOR_COMMISSION_ID = 'ConsignorCommissionId';
    public const CONSIGNOR_SOLD_FEE_ID = 'ConsignorSoldFeeId';
    public const CONSIGNOR_UNSOLD_FEE_ID = 'ConsignorUnsoldFeeId';
    public const MULTIPLE_SALE_SETTLEMENT = 'MultipleSaleSettlement';
    public const QUANTITY_IN_SETTLEMENT = 'QuantityInSettlement';
    public const RENDER_LOT_CUSTOM_FIELDS_IN_SEPARATE_ROW_FOR_SETTLEMENT = 'RenderLotCustomFieldsInSeparateRowForSettlement';
    public const SETTLEMENT_LOGO = 'SettlementLogo';
    public const SETTLEMENT_UNPAID_LOTS = 'SettlementUnpaidLots';
    public const STLM_CHECK_ADDRESS_COORD_X = 'StlmCheckAddressCoordX';
    public const STLM_CHECK_ADDRESS_COORD_Y = 'StlmCheckAddressCoordY';
    public const STLM_CHECK_ADDRESS_TEMPLATE = 'StlmCheckAddressTemplate';
    public const STLM_CHECK_AMOUNT_COORD_X = 'StlmCheckAmountCoordX';
    public const STLM_CHECK_AMOUNT_COORD_Y = 'StlmCheckAmountCoordY';
    public const STLM_CHECK_AMOUNT_SPELLING_COORD_X = 'StlmCheckAmountSpellingCoordX';
    public const STLM_CHECK_AMOUNT_SPELLING_COORD_Y = 'StlmCheckAmountSpellingCoordY';
    public const STLM_CHECK_DATE_COORD_X = 'StlmCheckDateCoordX';
    public const STLM_CHECK_DATE_COORD_Y = 'StlmCheckDateCoordY';
    public const STLM_CHECK_ENABLED = 'StlmCheckEnabled';
    public const STLM_CHECK_FILE = 'StlmCheckFile';
    public const STLM_CHECK_HEIGHT = 'StlmCheckHeight';
    public const STLM_CHECK_MEMO_COORD_X = 'StlmCheckMemoCoordX';
    public const STLM_CHECK_MEMO_COORD_Y = 'StlmCheckMemoCoordY';
    public const STLM_CHECK_MEMO_TEMPLATE = 'StlmCheckMemoTemplate';
    public const STLM_CHECK_NAME_COORD_X = 'StlmCheckNameCoordX';
    public const STLM_CHECK_NAME_COORD_Y = 'StlmCheckNameCoordY';
    public const STLM_CHECK_PAYEE_TEMPLATE = 'StlmCheckPayeeTemplate';
    public const STLM_CHECK_PER_PAGE = 'StlmCheckPerPage';
    public const STLM_CHECK_REPEAT_COUNT = 'StlmCheckRepeatCount';
    public const ALLOW_ACCOUNT_ADMIN_ADD_FLOOR_BIDDER = 'AllowAccountAdminAddFloorBidder';
    public const ALLOW_ACCOUNT_ADMIN_MAKE_BIDDER_PREFERRED = 'AllowAccountAdminMakeBidderPreferred';
    public const ALLOW_CONSIGNOR_DELETE_ITEM = 'AllowConsignorDeleteItem';
    public const AUCTION_CATALOG_ACCESS = 'AuctionCatalogAccess';
    public const AUCTION_INFO_ACCESS = 'AuctionInfoAccess';
    public const AUCTION_VISIBILITY_ACCESS = 'AuctionVisibilityAccess';
    public const AUTO_ASSIGN_ACCOUNT_ADMIN_PRIVILEGES = 'AutoAssignAccountAdminPrivileges';
    public const AUTO_CONSIGNOR_PRIVILEGES = 'AutoConsignorPrivileges';
    public const DONT_MAKE_USER_BIDDER = 'DontMakeUserBidder';
    public const LIVE_VIEW_ACCESS = 'LiveViewAccess';
    public const LOT_BIDDING_HISTORY_ACCESS = 'LotBiddingHistoryAccess';
    public const LOT_BIDDING_INFO_ACCESS = 'LotBiddingInfoAccess';
    public const LOT_DETAILS_ACCESS = 'LotDetailsAccess';
    public const LOT_STARTING_BID_ACCESS = 'LotStartingBidAccess';
    public const LOT_WINNING_BID_ACCESS = 'LotWinningBidAccess';
    public const SHARE_USER_INFO = 'ShareUserInfo';
    public const SHARE_USER_STATS = 'ShareUserStats';
    public const TEXT_MSG_API_NOTIFICATION = 'TextMsgApiNotification';
    public const TEXT_MSG_API_OUTBID_NOTIFICATION = 'TextMsgApiOutbidNotification';
    public const TEXT_MSG_API_POST_VAR = 'TextMsgApiPostVar';
    public const TEXT_MSG_API_URL = 'TextMsgApiUrl';
    public const TEXT_MSG_ENABLED = 'TextMsgEnabled';
    public const SMTP_AUTH = 'SmtpAuth';
    public const SMTP_PASSWORD = 'SmtpPassword';
    public const SMTP_PORT = 'SmtpPort';
    public const SMTP_SERVER = 'SmtpServer';
    public const SMTP_SSL_TYPE = 'SmtpSslType';
    public const SMTP_USERNAME = 'SmtpUsername';
    public const AUC_INC_ACCOUNT_ID = 'AucIncAccountId';
    public const AUC_INC_BUSINESS_ID = 'AucIncBusinessId';
    public const AUC_INC_DHL = 'AucIncDhl';
    public const AUC_INC_DHL_ACCESS_KEY = 'AucIncDhlAccessKey';
    public const AUC_INC_DHL_POSTAL_CODE = 'AucIncDhlPostalCode';
    public const AUC_INC_DIMENSION_TYPE = 'AucIncDimensionType';
    public const AUC_INC_FEDEX = 'AucIncFedex';
    public const AUC_INC_HEIGHT_CUST_FIELD_ID = 'AucIncHeightCustFieldId';
    public const AUC_INC_LENGTH_CUST_FIELD_ID = 'AucIncLengthCustFieldId';
    public const AUC_INC_METHOD = 'AucIncMethod';
    public const AUC_INC_PICKUP = 'AucIncPickup';
    public const AUC_INC_UPS = 'AucIncUps';
    public const AUC_INC_USPS = 'AucIncUsps';
    public const AUC_INC_WEIGHT_CUST_FIELD_ID = 'AucIncWeightCustFieldId';
    public const AUC_INC_WEIGHT_TYPE = 'AucIncWeightType';
    public const AUC_INC_WIDTH_CUST_FIELD_ID = 'AucIncWidthCustFieldId';
    public const ALLOW_BIDDING_DURING_START_GAP_HYBRID = 'AllowBiddingDuringStartGapHybrid';
    public const AUTO_CREATE_FLOOR_BIDDER_RECORD = 'AutoCreateFloorBidderRecord';
    public const BID_ACCEPTED_SOUND = 'BidAcceptedSound';
    public const BID_SOUND = 'BidSound';
    public const BUY_NOW_RESTRICTION = 'BuyNowRestriction';
    public const CLEAR_MESSAGE_CENTER = 'ClearMessageCenter';
    public const CLEAR_MESSAGE_CENTER_LOG = 'ClearMessageCenterLog';
    public const DEFAULT_IMAGE_PREVIEW = 'DefaultImagePreview';
    public const DELAY_AFTER_BID_ACCEPTED = 'DelayAfterBidAccepted';
    public const DELAY_BLOCK_SELL = 'DelayBlockSell';
    public const DELAY_SOLD_ITEM = 'DelaySoldItem';
    public const ENABLE_CONSIGNOR_COMPANY_CLERKING = 'EnableConsignorCompanyClerking';
    public const EXTEND_TIME_HYBRID = 'ExtendTimeHybrid';
    public const FAIR_WARNING_SOUND = 'FairWarningSound';
    public const FAIR_WARNINGS_HYBRID = 'FairWarningsHybrid';
    public const FLOOR_BIDDERS_FROM_DROPDOWN = 'FloorBiddersFromDropdown';
    public const LIVE_BIDDING_COUNTDOWN = 'LiveBiddingCountdown';
    public const LIVE_CHAT = 'LiveChat';
    public const LIVE_CHAT_VIEW_ALL = 'LiveChatViewAll';
    public const LOT_START_GAP_TIME_HYBRID = 'LotStartGapTimeHybrid';
    public const MULTI_CURRENCY = 'MultiCurrency';
    public const ONLINE_BID_INCOMING_ON_ADMIN_SOUND = 'OnlineBidIncomingOnAdminSound';
    public const ONLINEBID_BUTTON_INFO = 'OnlinebidButtonInfo';
    public const OUT_BID_SOUND = 'OutBidSound';
    public const PASSED_SOUND = 'PassedSound';
    public const PENDING_ACTION_TIMEOUT_HYBRID = 'PendingActionTimeoutHybrid';
    public const PLACE_BID_SOUND = 'PlaceBidSound';
    public const RESET_TIMER_ON_UNDO_HYBRID = 'ResetTimerOnUndoHybrid';
    public const SHOW_PORT_NOTICE = 'ShowPortNotice';
    public const SLIDESHOW_PROJECTOR_ONLY = 'SlideshowProjectorOnly';
    public const SOLD_NOT_WON_SOUND = 'SoldNotWonSound';
    public const SOLD_WON_SOUND = 'SoldWonSound';
    public const SUGGESTED_STARTING_BID = 'SuggestedStartingBid';
    public const SWITCH_FRAME_SECONDS = 'SwitchFrameSeconds';
    public const TWENTY_MESSAGES_MAX = 'TwentyMessagesMax';
    public const ADMIN_DATE_FORMAT = 'AdminDateFormat';
    public const ADMIN_LANGUAGE = 'AdminLanguage';
    public const DEFAULT_COUNTRY = 'DefaultCountry';
    public const DEFAULT_EXPORT_ENCODING = 'DefaultExportEncoding';
    public const DEFAULT_IMPORT_ENCODING = 'DefaultImportEncoding';
    public const EMAIL_FORMAT = 'EmailFormat';
    public const FORCE_MAIN_ACCOUNT_DOMAIN_MODE = 'ForceMainAccountDomainMode';
    public const GRAPHQL_CORS_ALLOWED_ORIGINS = 'GraphqlCorsAllowedOrigins';
    public const LOCALE = 'Locale';
    public const LOT_CATEGORY_GLOBAL_ORDER_AVAILABLE = 'LotCategoryGlobalOrderAvailable';
    public const PAYMENT_REMINDER_EMAIL_FREQUENCY = 'PaymentReminderEmailFrequency';
    public const PICKUP_REMINDER_EMAIL_FREQUENCY = 'PickupReminderEmailFrequency';
    public const PRIMARY_CURRENCY_ID = 'PrimaryCurrencyId';
    public const REG_REMINDER_EMAIL = 'RegReminderEmail';
    public const SUPPORT_EMAIL = 'SupportEmail';
    public const TIMEZONE_ID = 'TimezoneId';
    public const US_NUMBER_FORMATTING = 'UsNumberFormatting';
    public const VIEW_LANGUAGE = 'ViewLanguage';
    public const WAVEBID_ENDPOINT = 'WavebidEndpoint';
    public const WAVEBID_UAT = 'WavebidUat';
    public const ADMIN_CSS_FILE = 'AdminCssFile';
    public const ADMIN_CUSTOM_JS_URL = 'AdminCustomJsUrl';
    public const AUCTION_DATE_IN_SEARCH = 'AuctionDateInSearch';
    public const AUCTION_DETAIL_TEMPLATE = 'AuctionDetailTemplate';
    public const AUCTIONEER_FILTER = 'AuctioneerFilter';
    public const CONSIGNOR_SCHEDULE_HEADER = 'ConsignorScheduleHeader';
    public const CUSTOM_TEMPLATE_HIDE_EMPTY_FIELDS_FOR_ALL_CATEGORIES = 'CustomTemplateHideEmptyFieldsForAllCategories';
    public const CUSTOM_TEMPLATE_HIDE_EMPTY_FIELDS_FOR_NO_CATEGORY_LOT = 'CustomTemplateHideEmptyFieldsForNoCategoryLot';
    public const EXTERNAL_JAVASCRIPT = 'ExternalJavascript';
    public const IMAGE_AUTO_ORIENT = 'ImageAutoOrient';
    public const IMAGE_OPTIMIZE = 'ImageOptimize';
    public const ITEMS_PER_PAGE = 'ItemsPerPage';
    public const LANDING_PAGE = 'LandingPage';
    public const LANDING_PAGE_URL = 'LandingPageUrl';
    public const LOGO_LINK = 'LogoLink';
    public const LOT_ITEM_DETAIL_TEMPLATE = 'LotItemDetailTemplate';
    public const LOT_ITEM_DETAIL_TEMPLATE_FOR_NO_CATEGORY = 'LotItemDetailTemplateForNoCategory';
    public const MAIN_MENU_AUCTION_TARGET = 'MainMenuAuctionTarget';
    public const PAGE_HEADER = 'PageHeader';
    public const PAGE_HEADER_TYPE = 'PageHeaderType';
    public const PAGE_REDIRECTION = 'PageRedirection';
    public const RESPONSIVE_CSS_FILE = 'ResponsiveCssFile';
    public const RESPONSIVE_HEADER_ADDRESS = 'ResponsiveHeaderAddress';
    public const RESPONSIVE_HTML_HEAD_CODE = 'ResponsiveHtmlHeadCode';
    public const RTB_DETAIL_TEMPLATE = 'RtbDetailTemplate';
    public const RTB_DETAIL_TEMPLATE_FOR_NO_CATEGORY = 'RtbDetailTemplateForNoCategory';
    public const SEARCH_RESULTS_FORMAT = 'SearchResultsFormat';
    public const SHOW_MEMBER_MENU_ITEMS = 'ShowMemberMenuItems';
    public const AGENT_OPTION = 'AgentOption';
    public const AUTHORIZATION_USE = 'AuthorizationUse';
    public const AUTO_INCREMENT_CUSTOMER_NUM = 'AutoIncrementCustomerNum';
    public const AUTO_PREFERRED = 'AutoPreferred';
    public const AUTO_PREFERRED_CREDIT_CARD = 'AutoPreferredCreditCard';
    public const DEFAULT_COUNTRY_CODE = 'DefaultCountryCode';
    public const ENABLE_CONSIGNOR_PAYMENT_INFO = 'EnableConsignorPaymentInfo';
    public const ENABLE_RESELLER_REG = 'EnableResellerReg';
    public const ENABLE_USER_COMPANY = 'EnableUserCompany';
    public const ENABLE_USER_RESUME = 'EnableUserResume';
    public const HIDE_COUNTRY_CODE_SELECTION = 'HideCountryCodeSelection';
    public const INCLUDE_ACH_INFO = 'IncludeAchInfo';
    public const INCLUDE_BASIC_INFO = 'IncludeBasicInfo';
    public const INCLUDE_BILLING_INFO = 'IncludeBillingInfo';
    public const INCLUDE_CC_INFO = 'IncludeCcInfo';
    public const INCLUDE_USER_PREFERENCES = 'IncludeUserPreferences';
    public const MAKE_PERMANENT_BIDDER_NUM = 'MakePermanentBidderNum';
    public const MANDATORY_ACH_INFO = 'MandatoryAchInfo';
    public const MANDATORY_BASIC_INFO = 'MandatoryBasicInfo';
    public const MANDATORY_BILLING_INFO = 'MandatoryBillingInfo';
    public const MANDATORY_CC_INFO = 'MandatoryCcInfo';
    public const MANDATORY_USER_PREFERENCES = 'MandatoryUserPreferences';
    public const NEWSLETTER_OPTION = 'NewsletterOption';
    public const NO_AUTO_AUTHORIZATION = 'NoAutoAuthorization';
    public const ON_REGISTRATION = 'OnRegistration';
    public const ON_REGISTRATION_AMOUNT = 'OnRegistrationAmount';
    public const ON_REGISTRATION_EXPIRES = 'OnRegistrationExpires';
    public const PROFILE_BILLING_OPTIONAL = 'ProfileBillingOptional';
    public const PROFILE_SHIPPING_OPTIONAL = 'ProfileShippingOptional';
    public const REGISTRATION_REQUIRE_CC = 'RegistrationRequireCc';
    public const REQUIRE_IDENTIFICATION = 'RequireIdentification';
    public const REVOKE_PREFERRED_BIDDER = 'RevokePreferredBidder';
    public const SAVE_RESELLER_CERT_IN_PROFILE = 'SaveResellerCertInProfile';
    public const SEND_CONFIRMATION_LINK = 'SendConfirmationLink';
    public const SHOW_USER_RESUME = 'ShowUserResume';
    public const SIGNUP_TRACKING_CODE = 'SignupTrackingCode';
    public const SIMPLIFIED_SIGNUP = 'SimplifiedSignup';
    public const STAY_ON_ACCOUNT_DOMAIN = 'StayOnAccountDomain';
    public const VERIFY_EMAIL = 'VerifyEmail';

    /** @var array */
    public static $typeMap = [
        self::AUCTION_LISTING_PAGE_DESC => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUCTION_LISTING_PAGE_DESC,
            'entity' => SettingSeo::class
        ],
        self::AUCTION_LISTING_PAGE_KEYWORD => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUCTION_LISTING_PAGE_KEYWORD,
            'entity' => SettingSeo::class
        ],
        self::AUCTION_LISTING_PAGE_TITLE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUCTION_LISTING_PAGE_TITLE,
            'entity' => SettingSeo::class
        ],
        self::AUCTION_PAGE_DESC => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUCTION_PAGE_DESC,
            'entity' => SettingSeo::class
        ],
        self::AUCTION_PAGE_KEYWORD => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUCTION_PAGE_KEYWORD,
            'entity' => SettingSeo::class
        ],
        self::AUCTION_PAGE_TITLE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUCTION_PAGE_TITLE,
            'entity' => SettingSeo::class
        ],
        self::AUCTION_SEO_URL_TEMPLATE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUCTION_SEO_URL_TEMPLATE,
            'entity' => SettingSeo::class
        ],
        self::LOGIN_DESC => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LOGIN_DESC,
            'entity' => SettingSeo::class
        ],
        self::LOGIN_KEYWORD => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LOGIN_KEYWORD,
            'entity' => SettingSeo::class
        ],
        self::LOGIN_TITLE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LOGIN_TITLE,
            'entity' => SettingSeo::class
        ],
        self::LOT_PAGE_DESC => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LOT_PAGE_DESC,
            'entity' => SettingSeo::class
        ],
        self::LOT_PAGE_KEYWORD => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LOT_PAGE_KEYWORD,
            'entity' => SettingSeo::class
        ],
        self::LOT_PAGE_TITLE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LOT_PAGE_TITLE,
            'entity' => SettingSeo::class
        ],
        self::LOT_SEO_URL_TEMPLATE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LOT_SEO_URL_TEMPLATE,
            'entity' => SettingSeo::class
        ],
        self::SEARCH_RESULTS_PAGE_DESC => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SEARCH_RESULTS_PAGE_DESC,
            'entity' => SettingSeo::class
        ],
        self::SEARCH_RESULTS_PAGE_KEYWORD => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SEARCH_RESULTS_PAGE_KEYWORD,
            'entity' => SettingSeo::class
        ],
        self::SEARCH_RESULTS_PAGE_TITLE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SEARCH_RESULTS_PAGE_TITLE,
            'entity' => SettingSeo::class
        ],
        self::SIGNUP_DESC => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SIGNUP_DESC,
            'entity' => SettingSeo::class
        ],
        self::SIGNUP_KEYWORD => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SIGNUP_KEYWORD,
            'entity' => SettingSeo::class
        ],
        self::SIGNUP_TITLE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SIGNUP_TITLE,
            'entity' => SettingSeo::class
        ],
        self::ABOVE_RESERVE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ABOVE_RESERVE,
            'entity' => SettingAuction::class
        ],
        self::ABOVE_STARTING_BID => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ABOVE_STARTING_BID,
            'entity' => SettingAuction::class
        ],
        self::ABSENTEE_BID_LOT_NOTIFICATION => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ABSENTEE_BID_LOT_NOTIFICATION,
            'entity' => SettingAuction::class
        ],
        self::ABSENTEE_BIDS_DISPLAY => [
            'type' => Constants\Type::T_STRING,
            'property' => self::ABSENTEE_BIDS_DISPLAY,
            'entity' => SettingAuction::class
        ],
        self::ADD_BIDS_TO_WATCHLIST => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ADD_BIDS_TO_WATCHLIST,
            'entity' => SettingAuction::class
        ],
        self::ADD_DESCRIPTION_IN_LOT_NAME_COLUMN => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ADD_DESCRIPTION_IN_LOT_NAME_COLUMN,
            'entity' => SettingAuction::class
        ],
        self::ALL_USER_REQUIRE_CC_AUTH => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ALL_USER_REQUIRE_CC_AUTH,
            'entity' => SettingAuction::class
        ],
        self::ALLOW_ANYONE_TO_TELL_A_FRIEND => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ALLOW_ANYONE_TO_TELL_A_FRIEND,
            'entity' => SettingAuction::class
        ],
        self::ALLOW_FORCE_BID => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ALLOW_FORCE_BID,
            'entity' => SettingAuction::class
        ],
        self::ALLOW_MANUAL_BIDDER_FOR_FLAGGED_BIDDERS => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ALLOW_MANUAL_BIDDER_FOR_FLAGGED_BIDDERS,
            'entity' => SettingAuction::class
        ],
        self::ALLOW_MULTIBIDS => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ALLOW_MULTIBIDS,
            'entity' => SettingAuction::class
        ],
        self::ASSIGNED_LOTS_RESTRICTION => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::ASSIGNED_LOTS_RESTRICTION,
            'entity' => SettingAuction::class
        ],
        self::AUCTION_DOMAIN_MODE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUCTION_DOMAIN_MODE,
            'entity' => SettingAuction::class
        ],
        self::AUCTION_LINKS_TO => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::AUCTION_LINKS_TO,
            'entity' => SettingAuction::class
        ],
        self::BID_TRACKING_CODE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::BID_TRACKING_CODE,
            'entity' => SettingAuction::class
        ],
        self::BLACKLIST_PHRASE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::BLACKLIST_PHRASE,
            'entity' => SettingAuction::class
        ],
        self::BLOCK_SOLD_LOTS => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::BLOCK_SOLD_LOTS,
            'entity' => SettingAuction::class
        ],
        self::BUY_NOW_UNSOLD => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::BUY_NOW_UNSOLD,
            'entity' => SettingAuction::class
        ],
        self::CONDITIONAL_SALES => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::CONDITIONAL_SALES,
            'entity' => SettingAuction::class
        ],
        self::CONFIRM_ADDRESS_SALE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::CONFIRM_ADDRESS_SALE,
            'entity' => SettingAuction::class
        ],
        self::CONFIRM_MULTIBIDS => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::CONFIRM_MULTIBIDS,
            'entity' => SettingAuction::class
        ],
        self::CONFIRM_MULTIBIDS_TEXT => [
            'type' => Constants\Type::T_STRING,
            'property' => self::CONFIRM_MULTIBIDS_TEXT,
            'entity' => SettingAuction::class
        ],
        self::CONFIRM_TERMS_AND_CONDITIONS_SALE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::CONFIRM_TERMS_AND_CONDITIONS_SALE,
            'entity' => SettingAuction::class
        ],
        self::CONFIRM_TIMED_BID => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::CONFIRM_TIMED_BID,
            'entity' => SettingAuction::class
        ],
        self::CONFIRM_TIMED_BID_TEXT => [
            'type' => Constants\Type::T_STRING,
            'property' => self::CONFIRM_TIMED_BID_TEXT,
            'entity' => SettingAuction::class
        ],
        self::DISPLAY_BIDDER_INFO => [
            'type' => Constants\Type::T_STRING,
            'property' => self::DISPLAY_BIDDER_INFO,
            'entity' => SettingAuction::class
        ],
        self::DISPLAY_ITEM_NUM => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::DISPLAY_ITEM_NUM,
            'entity' => SettingAuction::class
        ],
        self::DISPLAY_QUANTITY => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::DISPLAY_QUANTITY,
            'entity' => SettingAuction::class
        ],
        self::ENABLE_SECOND_CHANCE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ENABLE_SECOND_CHANCE,
            'entity' => SettingAuction::class
        ],
        self::EXTEND_TIME_TIMED => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::EXTEND_TIME_TIMED,
            'entity' => SettingAuction::class,
            'nullable' => true
        ],
        self::GA_BID_TRACKING => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::GA_BID_TRACKING,
            'entity' => SettingAuction::class
        ],
        self::HAMMER_PRICE_BP => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::HAMMER_PRICE_BP,
            'entity' => SettingAuction::class
        ],
        self::HIDE_BIDDER_NUMBER => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::HIDE_BIDDER_NUMBER,
            'entity' => SettingAuction::class
        ],
        self::HIDE_MOVETOSALE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::HIDE_MOVETOSALE,
            'entity' => SettingAuction::class
        ],
        self::HIDE_UNSOLD_LOTS => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::HIDE_UNSOLD_LOTS,
            'entity' => SettingAuction::class
        ],
        self::INLINE_BID_CONFIRM => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::INLINE_BID_CONFIRM,
            'entity' => SettingAuction::class
        ],
        self::ITEM_NUM_LOCK => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ITEM_NUM_LOCK,
            'entity' => SettingAuction::class
        ],
        self::LOT_STATUS => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::LOT_STATUS,
            'entity' => SettingAuction::class,
            'nullable' => true
        ],
        self::MAX_STORED_SEARCHES => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::MAX_STORED_SEARCHES,
            'entity' => SettingAuction::class,
            'nullable' => true
        ],
        self::NEXT_BID_BUTTON => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::NEXT_BID_BUTTON,
            'entity' => SettingAuction::class
        ],
        self::NO_LOWER_MAXBID => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::NO_LOWER_MAXBID,
            'entity' => SettingAuction::class
        ],
        self::NOTIFY_ABSENTEE_BIDDERS => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::NOTIFY_ABSENTEE_BIDDERS,
            'entity' => SettingAuction::class
        ],
        self::ON_AUCTION_REGISTRATION => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::ON_AUCTION_REGISTRATION,
            'entity' => SettingAuction::class
        ],
        self::ON_AUCTION_REGISTRATION_AMOUNT => [
            'type' => Constants\Type::T_FLOAT,
            'property' => self::ON_AUCTION_REGISTRATION_AMOUNT,
            'entity' => SettingAuction::class,
            'nullable' => true
        ],
        self::ON_AUCTION_REGISTRATION_AUTO => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ON_AUCTION_REGISTRATION_AUTO,
            'entity' => SettingAuction::class
        ],
        self::ON_AUCTION_REGISTRATION_EXPIRES => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::ON_AUCTION_REGISTRATION_EXPIRES,
            'entity' => SettingAuction::class,
            'nullable' => true
        ],
        self::ONLY_ONE_REG_EMAIL => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ONLY_ONE_REG_EMAIL,
            'entity' => SettingAuction::class
        ],
        self::PAYMENT_TRACKING_CODE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::PAYMENT_TRACKING_CODE,
            'entity' => SettingAuction::class
        ],
        self::PLACE_BID_REQUIRE_CC => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::PLACE_BID_REQUIRE_CC,
            'entity' => SettingAuction::class
        ],
        self::QUANTITY_DIGITS => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::QUANTITY_DIGITS,
            'entity' => SettingAuction::class
        ],
        self::REG_CONFIRM_AUTO_APPROVE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::REG_CONFIRM_AUTO_APPROVE,
            'entity' => SettingAuction::class
        ],
        self::REG_CONFIRM_PAGE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::REG_CONFIRM_PAGE,
            'entity' => SettingAuction::class
        ],
        self::REG_CONFIRM_PAGE_CONTENT => [
            'type' => Constants\Type::T_STRING,
            'property' => self::REG_CONFIRM_PAGE_CONTENT,
            'entity' => SettingAuction::class
        ],
        self::REG_USE_HIGH_BIDDER => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::REG_USE_HIGH_BIDDER,
            'entity' => SettingAuction::class
        ],
        self::REQUIRE_ON_INC_BIDS => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::REQUIRE_ON_INC_BIDS,
            'entity' => SettingAuction::class
        ],
        self::REQUIRE_REENTER_CC => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::REQUIRE_REENTER_CC,
            'entity' => SettingAuction::class
        ],
        self::RESERVE_MET_NOTICE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::RESERVE_MET_NOTICE,
            'entity' => SettingAuction::class
        ],
        self::RESERVE_NOT_MET_NOTICE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::RESERVE_NOT_MET_NOTICE,
            'entity' => SettingAuction::class
        ],
        self::SEND_RESULTS_ONCE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SEND_RESULTS_ONCE,
            'entity' => SettingAuction::class
        ],
        self::SHIPPING_INFO_BOX => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::SHIPPING_INFO_BOX,
            'entity' => SettingAuction::class,
            'nullable' => true
        ],
        self::SHOW_AUCTION_STARTS_ENDING => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SHOW_AUCTION_STARTS_ENDING,
            'entity' => SettingAuction::class
        ],
        self::SHOW_COUNTDOWN_SECONDS => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SHOW_COUNTDOWN_SECONDS,
            'entity' => SettingAuction::class
        ],
        self::SHOW_HIGH_EST => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SHOW_HIGH_EST,
            'entity' => SettingAuction::class
        ],
        self::SHOW_LOW_EST => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SHOW_LOW_EST,
            'entity' => SettingAuction::class
        ],
        self::SHOW_WINNER_IN_CATALOG => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SHOW_WINNER_IN_CATALOG,
            'entity' => SettingAuction::class
        ],
        self::TAKE_MAX_BIDS_UNDER_RESERVE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::TAKE_MAX_BIDS_UNDER_RESERVE,
            'entity' => SettingAuction::class
        ],
        self::TELL_A_FRIEND => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::TELL_A_FRIEND,
            'entity' => SettingAuction::class
        ],
        self::TIMED_ABOVE_RESERVE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::TIMED_ABOVE_RESERVE,
            'entity' => SettingAuction::class
        ],
        self::TIMED_ABOVE_STARTING_BID => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::TIMED_ABOVE_STARTING_BID,
            'entity' => SettingAuction::class
        ],
        self::USE_ALTERNATE_PDF_CATALOG => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::USE_ALTERNATE_PDF_CATALOG,
            'entity' => SettingAuction::class
        ],
        self::VISIBLE_AUCTION_STATUSES => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::VISIBLE_AUCTION_STATUSES,
            'entity' => SettingAuction::class,
            'nullable' => true
        ],
        self::ACH_PAYMENT => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ACH_PAYMENT,
            'entity' => SettingBillingAuthorizeNet::class
        ],
        self::AUTH_NET_ACCOUNT_TYPE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUTH_NET_ACCOUNT_TYPE,
            'entity' => SettingBillingAuthorizeNet::class
        ],
        self::AUTH_NET_CIM => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::AUTH_NET_CIM,
            'entity' => SettingBillingAuthorizeNet::class
        ],
        self::AUTH_NET_LOGIN => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUTH_NET_LOGIN,
            'entity' => SettingBillingAuthorizeNet::class
        ],
        self::AUTH_NET_MODE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUTH_NET_MODE,
            'entity' => SettingBillingAuthorizeNet::class
        ],
        self::AUTH_NET_TRANKEY => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUTH_NET_TRANKEY,
            'entity' => SettingBillingAuthorizeNet::class
        ],
        self::CC_PAYMENT => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::CC_PAYMENT,
            'entity' => SettingBillingAuthorizeNet::class
        ],
        self::CC_PAYMENT_EWAY => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::CC_PAYMENT_EWAY,
            'entity' => SettingBillingEway::class
        ],
        self::EWAY_ACCOUNT_TYPE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::EWAY_ACCOUNT_TYPE,
            'entity' => SettingBillingEway::class
        ],
        self::EWAY_API_KEY => [
            'type' => Constants\Type::T_STRING,
            'property' => self::EWAY_API_KEY,
            'entity' => SettingBillingEway::class
        ],
        self::EWAY_ENCRYPTION_KEY => [
            'type' => Constants\Type::T_STRING,
            'property' => self::EWAY_ENCRYPTION_KEY,
            'entity' => SettingBillingEway::class
        ],
        self::EWAY_PASSWORD => [
            'type' => Constants\Type::T_STRING,
            'property' => self::EWAY_PASSWORD,
            'entity' => SettingBillingEway::class
        ],
        self::ACH_PAYMENT_NMI => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ACH_PAYMENT_NMI,
            'entity' => SettingBillingNmi::class
        ],
        self::CC_PAYMENT_NMI => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::CC_PAYMENT_NMI,
            'entity' => SettingBillingNmi::class
        ],
        self::NMI_MODE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::NMI_MODE,
            'entity' => SettingBillingNmi::class
        ],
        self::NMI_PASSWORD => [
            'type' => Constants\Type::T_STRING,
            'property' => self::NMI_PASSWORD,
            'entity' => SettingBillingNmi::class
        ],
        self::NMI_USERNAME => [
            'type' => Constants\Type::T_STRING,
            'property' => self::NMI_USERNAME,
            'entity' => SettingBillingNmi::class
        ],
        self::NMI_VAULT => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::NMI_VAULT,
            'entity' => SettingBillingNmi::class
        ],
        self::NMI_VAULT_OPTION => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::NMI_VAULT_OPTION,
            'entity' => SettingBillingNmi::class,
            'nullable' => true
        ],
        self::ACH_PAYMENT_OPAYO => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ACH_PAYMENT_OPAYO,
            'entity' => SettingBillingOpayo::class
        ],
        self::CC_PAYMENT_OPAYO => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::CC_PAYMENT_OPAYO,
            'entity' => SettingBillingOpayo::class
        ],
        self::OPAYO_3DSECURE => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::OPAYO_3DSECURE,
            'entity' => SettingBillingOpayo::class,
            'nullable' => true
        ],
        self::OPAYO_AUTH_TRANSACTION_TYPE => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::OPAYO_AUTH_TRANSACTION_TYPE,
            'entity' => SettingBillingOpayo::class
        ],
        self::OPAYO_AVSCV2 => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::OPAYO_AVSCV2,
            'entity' => SettingBillingOpayo::class,
            'nullable' => true
        ],
        self::OPAYO_CURRENCY => [
            'type' => Constants\Type::T_STRING,
            'property' => self::OPAYO_CURRENCY,
            'entity' => SettingBillingOpayo::class
        ],
        self::OPAYO_MODE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::OPAYO_MODE,
            'entity' => SettingBillingOpayo::class
        ],
        self::OPAYO_SEND_EMAIL => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::OPAYO_SEND_EMAIL,
            'entity' => SettingBillingOpayo::class,
            'nullable' => true
        ],
        self::OPAYO_TOKEN => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::OPAYO_TOKEN,
            'entity' => SettingBillingOpayo::class
        ],
        self::OPAYO_VENDOR_NAME => [
            'type' => Constants\Type::T_STRING,
            'property' => self::OPAYO_VENDOR_NAME,
            'entity' => SettingBillingOpayo::class
        ],
        self::ENABLE_PAYPAL_PAYMENTS => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ENABLE_PAYPAL_PAYMENTS,
            'entity' => SettingBillingPaypal::class
        ],
        self::PAYPAL_ACCOUNT_TYPE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::PAYPAL_ACCOUNT_TYPE,
            'entity' => SettingBillingPaypal::class
        ],
        self::PAYPAL_BN_CODE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::PAYPAL_BN_CODE,
            'entity' => SettingBillingPaypal::class
        ],
        self::PAYPAL_EMAIL => [
            'type' => Constants\Type::T_STRING,
            'property' => self::PAYPAL_EMAIL,
            'entity' => SettingBillingPaypal::class
        ],
        self::PAYPAL_IDENTITY_TOKEN => [
            'type' => Constants\Type::T_STRING,
            'property' => self::PAYPAL_IDENTITY_TOKEN,
            'entity' => SettingBillingPaypal::class
        ],
        self::ENABLE_SMART_PAYMENTS => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ENABLE_SMART_PAYMENTS,
            'entity' => SettingBillingSmartPay::class
        ],
        self::SMART_PAY_ACCOUNT_TYPE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SMART_PAY_ACCOUNT_TYPE,
            'entity' => SettingBillingSmartPay::class
        ],
        self::SMART_PAY_MERCHANT_ACCOUNT => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SMART_PAY_MERCHANT_ACCOUNT,
            'entity' => SettingBillingSmartPay::class
        ],
        self::SMART_PAY_MERCHANT_MODE => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::SMART_PAY_MERCHANT_MODE,
            'entity' => SettingBillingSmartPay::class
        ],
        self::SMART_PAY_MODE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SMART_PAY_MODE,
            'entity' => SettingBillingSmartPay::class
        ],
        self::SMART_PAY_SHARED_SECRET => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SMART_PAY_SHARED_SECRET,
            'entity' => SettingBillingSmartPay::class
        ],
        self::SMART_PAY_SKIN_CODE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SMART_PAY_SKIN_CODE,
            'entity' => SettingBillingSmartPay::class
        ],
        self::ACH_PAYMENT_PAY_TRACE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ACH_PAYMENT_PAY_TRACE,
            'entity' => SettingBillingPayTrace::class
        ],
        self::CC_PAYMENT_PAY_TRACE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::CC_PAYMENT_PAY_TRACE,
            'entity' => SettingBillingPayTrace::class
        ],
        self::PAY_TRACE_CIM => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::PAY_TRACE_CIM,
            'entity' => SettingBillingPayTrace::class
        ],
        self::PAY_TRACE_MODE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::PAY_TRACE_MODE,
            'entity' => SettingBillingPayTrace::class
        ],
        self::PAY_TRACE_PASSWORD => [
            'type' => Constants\Type::T_STRING,
            'property' => self::PAY_TRACE_PASSWORD,
            'entity' => SettingBillingPayTrace::class
        ],
        self::PAY_TRACE_USERNAME => [
            'type' => Constants\Type::T_STRING,
            'property' => self::PAY_TRACE_USERNAME,
            'entity' => SettingBillingPayTrace::class
        ],
        self::AUTO_INVOICE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::AUTO_INVOICE,
            'entity' => SettingInvoice::class
        ],
        self::CASH_DISCOUNT => [
            'type' => Constants\Type::T_FLOAT,
            'property' => self::CASH_DISCOUNT,
            'entity' => SettingInvoice::class,
            'nullable' => true
        ],
        self::CATEGORY_IN_INVOICE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::CATEGORY_IN_INVOICE,
            'entity' => SettingInvoice::class
        ],
        self::DEFAULT_INVOICE_NOTES => [
            'type' => Constants\Type::T_STRING,
            'property' => self::DEFAULT_INVOICE_NOTES,
            'entity' => SettingInvoice::class
        ],
        self::DEFAULT_LOT_ITEM_NO_TAX_OOS => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::DEFAULT_LOT_ITEM_NO_TAX_OOS,
            'entity' => SettingInvoice::class
        ],
        self::DEFAULT_POST_AUC_IMPORT_PREMIUM => [
            'type' => Constants\Type::T_FLOAT,
            'property' => self::DEFAULT_POST_AUC_IMPORT_PREMIUM,
            'entity' => SettingInvoice::class,
            'nullable' => true
        ],
        self::INVOICE_BP_TAX_SCHEMA_ID => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::INVOICE_BP_TAX_SCHEMA_ID,
            'entity' => SettingInvoice::class,
            'nullable' => true
        ],
        self::INVOICE_HP_TAX_SCHEMA_ID => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::INVOICE_HP_TAX_SCHEMA_ID,
            'entity' => SettingInvoice::class,
            'nullable' => true
        ],
        self::INVOICE_IDENTIFICATION => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::INVOICE_IDENTIFICATION,
            'entity' => SettingInvoice::class
        ],
        self::INVOICE_ITEM_DESCRIPTION => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::INVOICE_ITEM_DESCRIPTION,
            'entity' => SettingInvoice::class
        ],
        self::INVOICE_ITEM_SALES_TAX => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::INVOICE_ITEM_SALES_TAX,
            'entity' => SettingInvoice::class
        ],
        self::INVOICE_ITEM_SALES_TAX_APPLICATION => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::INVOICE_ITEM_SALES_TAX_APPLICATION,
            'entity' => SettingInvoice::class,
            'nullable' => true
        ],
        self::INVOICE_ITEM_SEPARATE_TAX => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::INVOICE_ITEM_SEPARATE_TAX,
            'entity' => SettingInvoice::class
        ],
        self::INVOICE_LOGO => [
            'type' => Constants\Type::T_STRING,
            'property' => self::INVOICE_LOGO,
            'entity' => SettingInvoice::class
        ],
        self::INVOICE_SERVICES_TAX_SCHEMA_ID => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::INVOICE_SERVICES_TAX_SCHEMA_ID,
            'entity' => SettingInvoice::class,
            'nullable' => true
        ],
        self::INVOICE_TAX_DESIGNATION_STRATEGY => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::INVOICE_TAX_DESIGNATION_STRATEGY,
            'entity' => SettingInvoice::class
        ],
        self::KEEP_DECIMAL_INVOICE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::KEEP_DECIMAL_INVOICE,
            'entity' => SettingInvoice::class
        ],
        self::MULTIPLE_SALE_INVOICE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::MULTIPLE_SALE_INVOICE,
            'entity' => SettingInvoice::class
        ],
        self::ONE_SALE_GROUPED_INVOICE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ONE_SALE_GROUPED_INVOICE,
            'entity' => SettingInvoice::class
        ],
        self::PROCESSING_CHARGE => [
            'type' => Constants\Type::T_FLOAT,
            'property' => self::PROCESSING_CHARGE,
            'entity' => SettingInvoice::class,
            'nullable' => true
        ],
        self::QUANTITY_IN_INVOICE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::QUANTITY_IN_INVOICE,
            'entity' => SettingInvoice::class
        ],
        self::RENDER_LOT_CUSTOM_FIELDS_IN_SEPARATE_ROW_FOR_INVOICE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::RENDER_LOT_CUSTOM_FIELDS_IN_SEPARATE_ROW_FOR_INVOICE,
            'entity' => SettingInvoice::class
        ],
        self::SALES_TAX => [
            'type' => Constants\Type::T_FLOAT,
            'property' => self::SALES_TAX,
            'entity' => SettingInvoice::class,
            'nullable' => true
        ],
        self::SALES_TAX_SERVICES => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SALES_TAX_SERVICES,
            'entity' => SettingInvoice::class
        ],
        self::SAM_TAX => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SAM_TAX,
            'entity' => SettingInvoice::class
        ],
        self::SAM_TAX_DEFAULT_COUNTRY => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SAM_TAX_DEFAULT_COUNTRY,
            'entity' => SettingInvoice::class
        ],
        self::SHIPPING_CHARGE => [
            'type' => Constants\Type::T_FLOAT,
            'property' => self::SHIPPING_CHARGE,
            'entity' => SettingInvoice::class,
            'nullable' => true
        ],
        self::FAILED_LOGIN_ATTEMPT_LOCKOUT_TIMEOUT => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::FAILED_LOGIN_ATTEMPT_LOCKOUT_TIMEOUT,
            'entity' => SettingPassword::class
        ],
        self::FAILED_LOGIN_ATTEMPT_TIME_INCREMENT => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::FAILED_LOGIN_ATTEMPT_TIME_INCREMENT,
            'entity' => SettingPassword::class
        ],
        self::PW_HISTORY_REPEAT => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::PW_HISTORY_REPEAT,
            'entity' => SettingPassword::class
        ],
        self::PW_MAX_CONSEQ_LETTER => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::PW_MAX_CONSEQ_LETTER,
            'entity' => SettingPassword::class
        ],
        self::PW_MAX_CONSEQ_NUM => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::PW_MAX_CONSEQ_NUM,
            'entity' => SettingPassword::class
        ],
        self::PW_MAX_SEQ_LETTER => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::PW_MAX_SEQ_LETTER,
            'entity' => SettingPassword::class
        ],
        self::PW_MAX_SEQ_NUM => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::PW_MAX_SEQ_NUM,
            'entity' => SettingPassword::class
        ],
        self::PW_MIN_LEN => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::PW_MIN_LEN,
            'entity' => SettingPassword::class
        ],
        self::PW_MIN_LETTER => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::PW_MIN_LETTER,
            'entity' => SettingPassword::class
        ],
        self::PW_MIN_NUM => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::PW_MIN_NUM,
            'entity' => SettingPassword::class
        ],
        self::PW_MIN_SPECIAL => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::PW_MIN_SPECIAL,
            'entity' => SettingPassword::class
        ],
        self::PW_RENEW => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::PW_RENEW,
            'entity' => SettingPassword::class
        ],
        self::PW_REQ_MIXED_CASE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::PW_REQ_MIXED_CASE,
            'entity' => SettingPassword::class
        ],
        self::PW_TMP_TIMEOUT => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::PW_TMP_TIMEOUT,
            'entity' => SettingPassword::class
        ],
        self::CHARGE_CONSIGNOR_COMMISSION => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::CHARGE_CONSIGNOR_COMMISSION,
            'entity' => SettingSettlement::class
        ],
        self::CONSIGNOR_COMMISSION_ID => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::CONSIGNOR_COMMISSION_ID,
            'entity' => SettingSettlement::class,
            'nullable' => true
        ],
        self::CONSIGNOR_SOLD_FEE_ID => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::CONSIGNOR_SOLD_FEE_ID,
            'entity' => SettingSettlement::class,
            'nullable' => true
        ],
        self::CONSIGNOR_UNSOLD_FEE_ID => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::CONSIGNOR_UNSOLD_FEE_ID,
            'entity' => SettingSettlement::class,
            'nullable' => true
        ],
        self::MULTIPLE_SALE_SETTLEMENT => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::MULTIPLE_SALE_SETTLEMENT,
            'entity' => SettingSettlement::class
        ],
        self::QUANTITY_IN_SETTLEMENT => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::QUANTITY_IN_SETTLEMENT,
            'entity' => SettingSettlement::class
        ],
        self::RENDER_LOT_CUSTOM_FIELDS_IN_SEPARATE_ROW_FOR_SETTLEMENT => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::RENDER_LOT_CUSTOM_FIELDS_IN_SEPARATE_ROW_FOR_SETTLEMENT,
            'entity' => SettingSettlement::class
        ],
        self::SETTLEMENT_LOGO => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SETTLEMENT_LOGO,
            'entity' => SettingSettlement::class
        ],
        self::SETTLEMENT_UNPAID_LOTS => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SETTLEMENT_UNPAID_LOTS,
            'entity' => SettingSettlement::class
        ],
        self::STLM_CHECK_ADDRESS_COORD_X => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::STLM_CHECK_ADDRESS_COORD_X,
            'entity' => SettingSettlementCheck::class,
            'nullable' => true
        ],
        self::STLM_CHECK_ADDRESS_COORD_Y => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::STLM_CHECK_ADDRESS_COORD_Y,
            'entity' => SettingSettlementCheck::class,
            'nullable' => true
        ],
        self::STLM_CHECK_ADDRESS_TEMPLATE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::STLM_CHECK_ADDRESS_TEMPLATE,
            'entity' => SettingSettlementCheck::class,
            'nullable' => true
        ],
        self::STLM_CHECK_AMOUNT_COORD_X => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::STLM_CHECK_AMOUNT_COORD_X,
            'entity' => SettingSettlementCheck::class,
            'nullable' => true
        ],
        self::STLM_CHECK_AMOUNT_COORD_Y => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::STLM_CHECK_AMOUNT_COORD_Y,
            'entity' => SettingSettlementCheck::class,
            'nullable' => true
        ],
        self::STLM_CHECK_AMOUNT_SPELLING_COORD_X => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::STLM_CHECK_AMOUNT_SPELLING_COORD_X,
            'entity' => SettingSettlementCheck::class,
            'nullable' => true
        ],
        self::STLM_CHECK_AMOUNT_SPELLING_COORD_Y => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::STLM_CHECK_AMOUNT_SPELLING_COORD_Y,
            'entity' => SettingSettlementCheck::class,
            'nullable' => true
        ],
        self::STLM_CHECK_DATE_COORD_X => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::STLM_CHECK_DATE_COORD_X,
            'entity' => SettingSettlementCheck::class,
            'nullable' => true
        ],
        self::STLM_CHECK_DATE_COORD_Y => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::STLM_CHECK_DATE_COORD_Y,
            'entity' => SettingSettlementCheck::class,
            'nullable' => true
        ],
        self::STLM_CHECK_ENABLED => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::STLM_CHECK_ENABLED,
            'entity' => SettingSettlementCheck::class
        ],
        self::STLM_CHECK_FILE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::STLM_CHECK_FILE,
            'entity' => SettingSettlementCheck::class
        ],
        self::STLM_CHECK_HEIGHT => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::STLM_CHECK_HEIGHT,
            'entity' => SettingSettlementCheck::class,
            'nullable' => true
        ],
        self::STLM_CHECK_MEMO_COORD_X => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::STLM_CHECK_MEMO_COORD_X,
            'entity' => SettingSettlementCheck::class,
            'nullable' => true
        ],
        self::STLM_CHECK_MEMO_COORD_Y => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::STLM_CHECK_MEMO_COORD_Y,
            'entity' => SettingSettlementCheck::class,
            'nullable' => true
        ],
        self::STLM_CHECK_MEMO_TEMPLATE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::STLM_CHECK_MEMO_TEMPLATE,
            'entity' => SettingSettlementCheck::class,
            'nullable' => true
        ],
        self::STLM_CHECK_NAME_COORD_X => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::STLM_CHECK_NAME_COORD_X,
            'entity' => SettingSettlementCheck::class,
            'nullable' => true
        ],
        self::STLM_CHECK_NAME_COORD_Y => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::STLM_CHECK_NAME_COORD_Y,
            'entity' => SettingSettlementCheck::class,
            'nullable' => true
        ],
        self::STLM_CHECK_PAYEE_TEMPLATE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::STLM_CHECK_PAYEE_TEMPLATE,
            'entity' => SettingSettlementCheck::class,
            'nullable' => true
        ],
        self::STLM_CHECK_PER_PAGE => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::STLM_CHECK_PER_PAGE,
            'entity' => SettingSettlementCheck::class
        ],
        self::STLM_CHECK_REPEAT_COUNT => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::STLM_CHECK_REPEAT_COUNT,
            'entity' => SettingSettlementCheck::class
        ],
        self::ALLOW_ACCOUNT_ADMIN_ADD_FLOOR_BIDDER => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ALLOW_ACCOUNT_ADMIN_ADD_FLOOR_BIDDER,
            'entity' => SettingAccessPermission::class
        ],
        self::ALLOW_ACCOUNT_ADMIN_MAKE_BIDDER_PREFERRED => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ALLOW_ACCOUNT_ADMIN_MAKE_BIDDER_PREFERRED,
            'entity' => SettingAccessPermission::class
        ],
        self::ALLOW_CONSIGNOR_DELETE_ITEM => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ALLOW_CONSIGNOR_DELETE_ITEM,
            'entity' => SettingAccessPermission::class
        ],
        self::AUCTION_CATALOG_ACCESS => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUCTION_CATALOG_ACCESS,
            'entity' => SettingAccessPermission::class,
            'knownSet' => ['ADMIN', 'CONSIGNOR', 'BIDDER', 'USER', 'VISITOR']
        ],
        self::AUCTION_INFO_ACCESS => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUCTION_INFO_ACCESS,
            'entity' => SettingAccessPermission::class,
            'knownSet' => ['ADMIN', 'CONSIGNOR', 'BIDDER', 'USER', 'VISITOR']
        ],
        self::AUCTION_VISIBILITY_ACCESS => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUCTION_VISIBILITY_ACCESS,
            'entity' => SettingAccessPermission::class,
            'knownSet' => ['ADMIN', 'CONSIGNOR', 'BIDDER', 'USER', 'VISITOR']
        ],
        self::AUTO_ASSIGN_ACCOUNT_ADMIN_PRIVILEGES => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::AUTO_ASSIGN_ACCOUNT_ADMIN_PRIVILEGES,
            'entity' => SettingAccessPermission::class
        ],
        self::AUTO_CONSIGNOR_PRIVILEGES => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::AUTO_CONSIGNOR_PRIVILEGES,
            'entity' => SettingAccessPermission::class
        ],
        self::DONT_MAKE_USER_BIDDER => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::DONT_MAKE_USER_BIDDER,
            'entity' => SettingAccessPermission::class
        ],
        self::LIVE_VIEW_ACCESS => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LIVE_VIEW_ACCESS,
            'entity' => SettingAccessPermission::class,
            'knownSet' => ['ADMIN', 'CONSIGNOR', 'BIDDER', 'USER', 'VISITOR']
        ],
        self::LOT_BIDDING_HISTORY_ACCESS => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LOT_BIDDING_HISTORY_ACCESS,
            'entity' => SettingAccessPermission::class,
            'knownSet' => ['ADMIN', 'CONSIGNOR', 'BIDDER', 'USER', 'VISITOR']
        ],
        self::LOT_BIDDING_INFO_ACCESS => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LOT_BIDDING_INFO_ACCESS,
            'entity' => SettingAccessPermission::class,
            'knownSet' => ['ADMIN', 'CONSIGNOR', 'BIDDER', 'USER', 'VISITOR']
        ],
        self::LOT_DETAILS_ACCESS => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LOT_DETAILS_ACCESS,
            'entity' => SettingAccessPermission::class,
            'knownSet' => ['ADMIN', 'CONSIGNOR', 'BIDDER', 'USER', 'VISITOR']
        ],
        self::LOT_STARTING_BID_ACCESS => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LOT_STARTING_BID_ACCESS,
            'entity' => SettingAccessPermission::class,
            'knownSet' => ['ADMIN', 'CONSIGNOR', 'BIDDER', 'USER', 'VISITOR']
        ],
        self::LOT_WINNING_BID_ACCESS => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LOT_WINNING_BID_ACCESS,
            'entity' => SettingAccessPermission::class,
            'knownSet' => ['ADMIN', 'CONSIGNOR', 'BIDDER', 'USER', 'VISITOR']
        ],
        self::SHARE_USER_INFO => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::SHARE_USER_INFO,
            'entity' => SettingAccessPermission::class,
            'nullable' => true
        ],
        self::SHARE_USER_STATS => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SHARE_USER_STATS,
            'entity' => SettingAccessPermission::class
        ],
        self::TEXT_MSG_API_NOTIFICATION => [
            'type' => Constants\Type::T_STRING,
            'property' => self::TEXT_MSG_API_NOTIFICATION,
            'entity' => SettingSms::class
        ],
        self::TEXT_MSG_API_OUTBID_NOTIFICATION => [
            'type' => Constants\Type::T_STRING,
            'property' => self::TEXT_MSG_API_OUTBID_NOTIFICATION,
            'entity' => SettingSms::class
        ],
        self::TEXT_MSG_API_POST_VAR => [
            'type' => Constants\Type::T_STRING,
            'property' => self::TEXT_MSG_API_POST_VAR,
            'entity' => SettingSms::class
        ],
        self::TEXT_MSG_API_URL => [
            'type' => Constants\Type::T_STRING,
            'property' => self::TEXT_MSG_API_URL,
            'entity' => SettingSms::class
        ],
        self::TEXT_MSG_ENABLED => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::TEXT_MSG_ENABLED,
            'entity' => SettingSms::class
        ],
        self::SMTP_AUTH => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SMTP_AUTH,
            'entity' => SettingSmtp::class
        ],
        self::SMTP_PASSWORD => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SMTP_PASSWORD,
            'entity' => SettingSmtp::class
        ],
        self::SMTP_PORT => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::SMTP_PORT,
            'entity' => SettingSmtp::class,
            'nullable' => true
        ],
        self::SMTP_SERVER => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SMTP_SERVER,
            'entity' => SettingSmtp::class
        ],
        self::SMTP_SSL_TYPE => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::SMTP_SSL_TYPE,
            'entity' => SettingSmtp::class,
            'nullable' => true
        ],
        self::SMTP_USERNAME => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SMTP_USERNAME,
            'entity' => SettingSmtp::class
        ],
        self::AUC_INC_ACCOUNT_ID => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUC_INC_ACCOUNT_ID,
            'entity' => SettingShippingAuctionInc::class
        ],
        self::AUC_INC_BUSINESS_ID => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUC_INC_BUSINESS_ID,
            'entity' => SettingShippingAuctionInc::class
        ],
        self::AUC_INC_DHL => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::AUC_INC_DHL,
            'entity' => SettingShippingAuctionInc::class
        ],
        self::AUC_INC_DHL_ACCESS_KEY => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::AUC_INC_DHL_ACCESS_KEY,
            'entity' => SettingShippingAuctionInc::class,
            'nullable' => true
        ],
        self::AUC_INC_DHL_POSTAL_CODE => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::AUC_INC_DHL_POSTAL_CODE,
            'entity' => SettingShippingAuctionInc::class,
            'nullable' => true
        ],
        self::AUC_INC_DIMENSION_TYPE => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::AUC_INC_DIMENSION_TYPE,
            'entity' => SettingShippingAuctionInc::class
        ],
        self::AUC_INC_FEDEX => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::AUC_INC_FEDEX,
            'entity' => SettingShippingAuctionInc::class
        ],
        self::AUC_INC_HEIGHT_CUST_FIELD_ID => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::AUC_INC_HEIGHT_CUST_FIELD_ID,
            'entity' => SettingShippingAuctionInc::class,
            'nullable' => true
        ],
        self::AUC_INC_LENGTH_CUST_FIELD_ID => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::AUC_INC_LENGTH_CUST_FIELD_ID,
            'entity' => SettingShippingAuctionInc::class,
            'nullable' => true
        ],
        self::AUC_INC_METHOD => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUC_INC_METHOD,
            'entity' => SettingShippingAuctionInc::class
        ],
        self::AUC_INC_PICKUP => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::AUC_INC_PICKUP,
            'entity' => SettingShippingAuctionInc::class
        ],
        self::AUC_INC_UPS => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::AUC_INC_UPS,
            'entity' => SettingShippingAuctionInc::class
        ],
        self::AUC_INC_USPS => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::AUC_INC_USPS,
            'entity' => SettingShippingAuctionInc::class
        ],
        self::AUC_INC_WEIGHT_CUST_FIELD_ID => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::AUC_INC_WEIGHT_CUST_FIELD_ID,
            'entity' => SettingShippingAuctionInc::class,
            'nullable' => true
        ],
        self::AUC_INC_WEIGHT_TYPE => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::AUC_INC_WEIGHT_TYPE,
            'entity' => SettingShippingAuctionInc::class
        ],
        self::AUC_INC_WIDTH_CUST_FIELD_ID => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::AUC_INC_WIDTH_CUST_FIELD_ID,
            'entity' => SettingShippingAuctionInc::class,
            'nullable' => true
        ],
        self::ALLOW_BIDDING_DURING_START_GAP_HYBRID => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ALLOW_BIDDING_DURING_START_GAP_HYBRID,
            'entity' => SettingRtb::class
        ],
        self::AUTO_CREATE_FLOOR_BIDDER_RECORD => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::AUTO_CREATE_FLOOR_BIDDER_RECORD,
            'entity' => SettingRtb::class
        ],
        self::BID_ACCEPTED_SOUND => [
            'type' => Constants\Type::T_STRING,
            'property' => self::BID_ACCEPTED_SOUND,
            'entity' => SettingRtb::class
        ],
        self::BID_SOUND => [
            'type' => Constants\Type::T_STRING,
            'property' => self::BID_SOUND,
            'entity' => SettingRtb::class
        ],
        self::BUY_NOW_RESTRICTION => [
            'type' => Constants\Type::T_STRING,
            'property' => self::BUY_NOW_RESTRICTION,
            'entity' => SettingRtb::class
        ],
        self::CLEAR_MESSAGE_CENTER => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::CLEAR_MESSAGE_CENTER,
            'entity' => SettingRtb::class
        ],
        self::CLEAR_MESSAGE_CENTER_LOG => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::CLEAR_MESSAGE_CENTER_LOG,
            'entity' => SettingRtb::class
        ],
        self::DEFAULT_IMAGE_PREVIEW => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::DEFAULT_IMAGE_PREVIEW,
            'entity' => SettingRtb::class
        ],
        self::DELAY_AFTER_BID_ACCEPTED => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::DELAY_AFTER_BID_ACCEPTED,
            'entity' => SettingRtb::class,
            'nullable' => true
        ],
        self::DELAY_BLOCK_SELL => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::DELAY_BLOCK_SELL,
            'entity' => SettingRtb::class,
            'nullable' => true
        ],
        self::DELAY_SOLD_ITEM => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::DELAY_SOLD_ITEM,
            'entity' => SettingRtb::class,
            'nullable' => true
        ],
        self::ENABLE_CONSIGNOR_COMPANY_CLERKING => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ENABLE_CONSIGNOR_COMPANY_CLERKING,
            'entity' => SettingRtb::class
        ],
        self::EXTEND_TIME_HYBRID => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::EXTEND_TIME_HYBRID,
            'entity' => SettingRtb::class,
            'nullable' => true
        ],
        self::FAIR_WARNING_SOUND => [
            'type' => Constants\Type::T_STRING,
            'property' => self::FAIR_WARNING_SOUND,
            'entity' => SettingRtb::class
        ],
        self::FAIR_WARNINGS_HYBRID => [
            'type' => Constants\Type::T_STRING,
            'property' => self::FAIR_WARNINGS_HYBRID,
            'entity' => SettingRtb::class
        ],
        self::FLOOR_BIDDERS_FROM_DROPDOWN => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::FLOOR_BIDDERS_FROM_DROPDOWN,
            'entity' => SettingRtb::class
        ],
        self::LIVE_BIDDING_COUNTDOWN => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LIVE_BIDDING_COUNTDOWN,
            'entity' => SettingRtb::class
        ],
        self::LIVE_CHAT => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::LIVE_CHAT,
            'entity' => SettingRtb::class
        ],
        self::LIVE_CHAT_VIEW_ALL => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::LIVE_CHAT_VIEW_ALL,
            'entity' => SettingRtb::class
        ],
        self::LOT_START_GAP_TIME_HYBRID => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::LOT_START_GAP_TIME_HYBRID,
            'entity' => SettingRtb::class
        ],
        self::MULTI_CURRENCY => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::MULTI_CURRENCY,
            'entity' => SettingRtb::class
        ],
        self::ONLINE_BID_INCOMING_ON_ADMIN_SOUND => [
            'type' => Constants\Type::T_STRING,
            'property' => self::ONLINE_BID_INCOMING_ON_ADMIN_SOUND,
            'entity' => SettingRtb::class
        ],
        self::ONLINEBID_BUTTON_INFO => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::ONLINEBID_BUTTON_INFO,
            'entity' => SettingRtb::class,
            'nullable' => true
        ],
        self::OUT_BID_SOUND => [
            'type' => Constants\Type::T_STRING,
            'property' => self::OUT_BID_SOUND,
            'entity' => SettingRtb::class
        ],
        self::PASSED_SOUND => [
            'type' => Constants\Type::T_STRING,
            'property' => self::PASSED_SOUND,
            'entity' => SettingRtb::class
        ],
        self::PENDING_ACTION_TIMEOUT_HYBRID => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::PENDING_ACTION_TIMEOUT_HYBRID,
            'entity' => SettingRtb::class,
            'nullable' => true
        ],
        self::PLACE_BID_SOUND => [
            'type' => Constants\Type::T_STRING,
            'property' => self::PLACE_BID_SOUND,
            'entity' => SettingRtb::class
        ],
        self::RESET_TIMER_ON_UNDO_HYBRID => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::RESET_TIMER_ON_UNDO_HYBRID,
            'entity' => SettingRtb::class
        ],
        self::SHOW_PORT_NOTICE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SHOW_PORT_NOTICE,
            'entity' => SettingRtb::class
        ],
        self::SLIDESHOW_PROJECTOR_ONLY => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SLIDESHOW_PROJECTOR_ONLY,
            'entity' => SettingRtb::class
        ],
        self::SOLD_NOT_WON_SOUND => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SOLD_NOT_WON_SOUND,
            'entity' => SettingRtb::class
        ],
        self::SOLD_WON_SOUND => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SOLD_WON_SOUND,
            'entity' => SettingRtb::class
        ],
        self::SUGGESTED_STARTING_BID => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SUGGESTED_STARTING_BID,
            'entity' => SettingRtb::class
        ],
        self::SWITCH_FRAME_SECONDS => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::SWITCH_FRAME_SECONDS,
            'entity' => SettingRtb::class,
            'nullable' => true
        ],
        self::TWENTY_MESSAGES_MAX => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::TWENTY_MESSAGES_MAX,
            'entity' => SettingRtb::class
        ],
        self::ADMIN_DATE_FORMAT => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::ADMIN_DATE_FORMAT,
            'entity' => SettingSystem::class,
            'nullable' => true
        ],
        self::ADMIN_LANGUAGE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::ADMIN_LANGUAGE,
            'entity' => SettingSystem::class
        ],
        self::DEFAULT_COUNTRY => [
            'type' => Constants\Type::T_STRING,
            'property' => self::DEFAULT_COUNTRY,
            'entity' => SettingSystem::class
        ],
        self::DEFAULT_EXPORT_ENCODING => [
            'type' => Constants\Type::T_STRING,
            'property' => self::DEFAULT_EXPORT_ENCODING,
            'entity' => SettingSystem::class
        ],
        self::DEFAULT_IMPORT_ENCODING => [
            'type' => Constants\Type::T_STRING,
            'property' => self::DEFAULT_IMPORT_ENCODING,
            'entity' => SettingSystem::class
        ],
        self::EMAIL_FORMAT => [
            'type' => Constants\Type::T_STRING,
            'property' => self::EMAIL_FORMAT,
            'entity' => SettingSystem::class
        ],
        self::FORCE_MAIN_ACCOUNT_DOMAIN_MODE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::FORCE_MAIN_ACCOUNT_DOMAIN_MODE,
            'entity' => SettingSystem::class
        ],
        self::GRAPHQL_CORS_ALLOWED_ORIGINS => [
            'type' => Constants\Type::T_STRING,
            'property' => self::GRAPHQL_CORS_ALLOWED_ORIGINS,
            'entity' => SettingSystem::class
        ],
        self::LOCALE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LOCALE,
            'entity' => SettingSystem::class
        ],
        self::LOT_CATEGORY_GLOBAL_ORDER_AVAILABLE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::LOT_CATEGORY_GLOBAL_ORDER_AVAILABLE,
            'entity' => SettingSystem::class
        ],
        self::PAYMENT_REMINDER_EMAIL_FREQUENCY => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::PAYMENT_REMINDER_EMAIL_FREQUENCY,
            'entity' => SettingSystem::class
        ],
        self::PICKUP_REMINDER_EMAIL_FREQUENCY => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::PICKUP_REMINDER_EMAIL_FREQUENCY,
            'entity' => SettingSystem::class
        ],
        self::PRIMARY_CURRENCY_ID => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::PRIMARY_CURRENCY_ID,
            'entity' => SettingSystem::class,
            'nullable' => true
        ],
        self::REG_REMINDER_EMAIL => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::REG_REMINDER_EMAIL,
            'entity' => SettingSystem::class,
            'nullable' => true
        ],
        self::SUPPORT_EMAIL => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SUPPORT_EMAIL,
            'entity' => SettingSystem::class
        ],
        self::TIMEZONE_ID => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::TIMEZONE_ID,
            'entity' => SettingSystem::class,
            'nullable' => true
        ],
        self::US_NUMBER_FORMATTING => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::US_NUMBER_FORMATTING,
            'entity' => SettingSystem::class
        ],
        self::VIEW_LANGUAGE => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::VIEW_LANGUAGE,
            'entity' => SettingSystem::class,
            'nullable' => true
        ],
        self::WAVEBID_ENDPOINT => [
            'type' => Constants\Type::T_STRING,
            'property' => self::WAVEBID_ENDPOINT,
            'entity' => SettingSystem::class
        ],
        self::WAVEBID_UAT => [
            'type' => Constants\Type::T_STRING,
            'property' => self::WAVEBID_UAT,
            'entity' => SettingSystem::class
        ],
        self::ADMIN_CSS_FILE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::ADMIN_CSS_FILE,
            'entity' => SettingUi::class
        ],
        self::ADMIN_CUSTOM_JS_URL => [
            'type' => Constants\Type::T_STRING,
            'property' => self::ADMIN_CUSTOM_JS_URL,
            'entity' => SettingUi::class
        ],
        self::AUCTION_DATE_IN_SEARCH => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::AUCTION_DATE_IN_SEARCH,
            'entity' => SettingUi::class
        ],
        self::AUCTION_DETAIL_TEMPLATE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUCTION_DETAIL_TEMPLATE,
            'entity' => SettingUi::class
        ],
        self::AUCTIONEER_FILTER => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::AUCTIONEER_FILTER,
            'entity' => SettingUi::class
        ],
        self::CONSIGNOR_SCHEDULE_HEADER => [
            'type' => Constants\Type::T_STRING,
            'property' => self::CONSIGNOR_SCHEDULE_HEADER,
            'entity' => SettingUi::class
        ],
        self::CUSTOM_TEMPLATE_HIDE_EMPTY_FIELDS_FOR_ALL_CATEGORIES => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::CUSTOM_TEMPLATE_HIDE_EMPTY_FIELDS_FOR_ALL_CATEGORIES,
            'entity' => SettingUi::class
        ],
        self::CUSTOM_TEMPLATE_HIDE_EMPTY_FIELDS_FOR_NO_CATEGORY_LOT => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::CUSTOM_TEMPLATE_HIDE_EMPTY_FIELDS_FOR_NO_CATEGORY_LOT,
            'entity' => SettingUi::class
        ],
        self::EXTERNAL_JAVASCRIPT => [
            'type' => Constants\Type::T_STRING,
            'property' => self::EXTERNAL_JAVASCRIPT,
            'entity' => SettingUi::class
        ],
        self::IMAGE_AUTO_ORIENT => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::IMAGE_AUTO_ORIENT,
            'entity' => SettingUi::class
        ],
        self::IMAGE_OPTIMIZE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::IMAGE_OPTIMIZE,
            'entity' => SettingUi::class
        ],
        self::ITEMS_PER_PAGE => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::ITEMS_PER_PAGE,
            'entity' => SettingUi::class,
            'nullable' => true
        ],
        self::LANDING_PAGE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LANDING_PAGE,
            'entity' => SettingUi::class
        ],
        self::LANDING_PAGE_URL => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LANDING_PAGE_URL,
            'entity' => SettingUi::class
        ],
        self::LOGO_LINK => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LOGO_LINK,
            'entity' => SettingUi::class
        ],
        self::LOT_ITEM_DETAIL_TEMPLATE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LOT_ITEM_DETAIL_TEMPLATE,
            'entity' => SettingUi::class
        ],
        self::LOT_ITEM_DETAIL_TEMPLATE_FOR_NO_CATEGORY => [
            'type' => Constants\Type::T_STRING,
            'property' => self::LOT_ITEM_DETAIL_TEMPLATE_FOR_NO_CATEGORY,
            'entity' => SettingUi::class
        ],
        self::MAIN_MENU_AUCTION_TARGET => [
            'type' => Constants\Type::T_STRING,
            'property' => self::MAIN_MENU_AUCTION_TARGET,
            'entity' => SettingUi::class
        ],
        self::PAGE_HEADER => [
            'type' => Constants\Type::T_STRING,
            'property' => self::PAGE_HEADER,
            'entity' => SettingUi::class
        ],
        self::PAGE_HEADER_TYPE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::PAGE_HEADER_TYPE,
            'entity' => SettingUi::class
        ],
        self::PAGE_REDIRECTION => [
            'type' => Constants\Type::T_STRING,
            'property' => self::PAGE_REDIRECTION,
            'entity' => SettingUi::class
        ],
        self::RESPONSIVE_CSS_FILE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::RESPONSIVE_CSS_FILE,
            'entity' => SettingUi::class
        ],
        self::RESPONSIVE_HEADER_ADDRESS => [
            'type' => Constants\Type::T_STRING,
            'property' => self::RESPONSIVE_HEADER_ADDRESS,
            'entity' => SettingUi::class
        ],
        self::RESPONSIVE_HTML_HEAD_CODE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::RESPONSIVE_HTML_HEAD_CODE,
            'entity' => SettingUi::class
        ],
        self::RTB_DETAIL_TEMPLATE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::RTB_DETAIL_TEMPLATE,
            'entity' => SettingUi::class
        ],
        self::RTB_DETAIL_TEMPLATE_FOR_NO_CATEGORY => [
            'type' => Constants\Type::T_STRING,
            'property' => self::RTB_DETAIL_TEMPLATE_FOR_NO_CATEGORY,
            'entity' => SettingUi::class
        ],
        self::SEARCH_RESULTS_FORMAT => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SEARCH_RESULTS_FORMAT,
            'entity' => SettingUi::class
        ],
        self::SHOW_MEMBER_MENU_ITEMS => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SHOW_MEMBER_MENU_ITEMS,
            'entity' => SettingUi::class
        ],
        self::AGENT_OPTION => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::AGENT_OPTION,
            'entity' => SettingUser::class
        ],
        self::AUTHORIZATION_USE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::AUTHORIZATION_USE,
            'entity' => SettingUser::class
        ],
        self::AUTO_INCREMENT_CUSTOMER_NUM => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::AUTO_INCREMENT_CUSTOMER_NUM,
            'entity' => SettingUser::class
        ],
        self::AUTO_PREFERRED => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::AUTO_PREFERRED,
            'entity' => SettingUser::class
        ],
        self::AUTO_PREFERRED_CREDIT_CARD => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::AUTO_PREFERRED_CREDIT_CARD,
            'entity' => SettingUser::class
        ],
        self::DEFAULT_COUNTRY_CODE => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::DEFAULT_COUNTRY_CODE,
            'entity' => SettingUser::class
        ],
        self::ENABLE_CONSIGNOR_PAYMENT_INFO => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ENABLE_CONSIGNOR_PAYMENT_INFO,
            'entity' => SettingUser::class
        ],
        self::ENABLE_RESELLER_REG => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ENABLE_RESELLER_REG,
            'entity' => SettingUser::class
        ],
        self::ENABLE_USER_COMPANY => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ENABLE_USER_COMPANY,
            'entity' => SettingUser::class
        ],
        self::ENABLE_USER_RESUME => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::ENABLE_USER_RESUME,
            'entity' => SettingUser::class
        ],
        self::HIDE_COUNTRY_CODE_SELECTION => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::HIDE_COUNTRY_CODE_SELECTION,
            'entity' => SettingUser::class
        ],
        self::INCLUDE_ACH_INFO => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::INCLUDE_ACH_INFO,
            'entity' => SettingUser::class
        ],
        self::INCLUDE_BASIC_INFO => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::INCLUDE_BASIC_INFO,
            'entity' => SettingUser::class
        ],
        self::INCLUDE_BILLING_INFO => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::INCLUDE_BILLING_INFO,
            'entity' => SettingUser::class
        ],
        self::INCLUDE_CC_INFO => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::INCLUDE_CC_INFO,
            'entity' => SettingUser::class
        ],
        self::INCLUDE_USER_PREFERENCES => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::INCLUDE_USER_PREFERENCES,
            'entity' => SettingUser::class
        ],
        self::MAKE_PERMANENT_BIDDER_NUM => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::MAKE_PERMANENT_BIDDER_NUM,
            'entity' => SettingUser::class
        ],
        self::MANDATORY_ACH_INFO => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::MANDATORY_ACH_INFO,
            'entity' => SettingUser::class
        ],
        self::MANDATORY_BASIC_INFO => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::MANDATORY_BASIC_INFO,
            'entity' => SettingUser::class
        ],
        self::MANDATORY_BILLING_INFO => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::MANDATORY_BILLING_INFO,
            'entity' => SettingUser::class
        ],
        self::MANDATORY_CC_INFO => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::MANDATORY_CC_INFO,
            'entity' => SettingUser::class
        ],
        self::MANDATORY_USER_PREFERENCES => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::MANDATORY_USER_PREFERENCES,
            'entity' => SettingUser::class
        ],
        self::NEWSLETTER_OPTION => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::NEWSLETTER_OPTION,
            'entity' => SettingUser::class
        ],
        self::NO_AUTO_AUTHORIZATION => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::NO_AUTO_AUTHORIZATION,
            'entity' => SettingUser::class
        ],
        self::ON_REGISTRATION => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::ON_REGISTRATION,
            'entity' => SettingUser::class
        ],
        self::ON_REGISTRATION_AMOUNT => [
            'type' => Constants\Type::T_FLOAT,
            'property' => self::ON_REGISTRATION_AMOUNT,
            'entity' => SettingUser::class,
            'nullable' => true
        ],
        self::ON_REGISTRATION_EXPIRES => [
            'type' => Constants\Type::T_INTEGER,
            'property' => self::ON_REGISTRATION_EXPIRES,
            'entity' => SettingUser::class,
            'nullable' => true
        ],
        self::PROFILE_BILLING_OPTIONAL => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::PROFILE_BILLING_OPTIONAL,
            'entity' => SettingUser::class
        ],
        self::PROFILE_SHIPPING_OPTIONAL => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::PROFILE_SHIPPING_OPTIONAL,
            'entity' => SettingUser::class
        ],
        self::REGISTRATION_REQUIRE_CC => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::REGISTRATION_REQUIRE_CC,
            'entity' => SettingUser::class
        ],
        self::REQUIRE_IDENTIFICATION => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::REQUIRE_IDENTIFICATION,
            'entity' => SettingUser::class
        ],
        self::REVOKE_PREFERRED_BIDDER => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::REVOKE_PREFERRED_BIDDER,
            'entity' => SettingUser::class
        ],
        self::SAVE_RESELLER_CERT_IN_PROFILE => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SAVE_RESELLER_CERT_IN_PROFILE,
            'entity' => SettingUser::class
        ],
        self::SEND_CONFIRMATION_LINK => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SEND_CONFIRMATION_LINK,
            'entity' => SettingUser::class
        ],
        self::SHOW_USER_RESUME => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SHOW_USER_RESUME,
            'entity' => SettingUser::class
        ],
        self::SIGNUP_TRACKING_CODE => [
            'type' => Constants\Type::T_STRING,
            'property' => self::SIGNUP_TRACKING_CODE,
            'entity' => SettingUser::class
        ],
        self::SIMPLIFIED_SIGNUP => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::SIMPLIFIED_SIGNUP,
            'entity' => SettingUser::class
        ],
        self::STAY_ON_ACCOUNT_DOMAIN => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::STAY_ON_ACCOUNT_DOMAIN,
            'entity' => SettingUser::class
        ],
        self::VERIFY_EMAIL => [
            'type' => Constants\Type::T_BOOL,
            'property' => self::VERIFY_EMAIL,
            'entity' => SettingUser::class
        ]
    ];
}
