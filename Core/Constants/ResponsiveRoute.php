<?php
/**
 * SAM-6734: Apply constants for controller and action names
 */

namespace Sam\Core\Constants;

/**
 * Class Url
 * @package Sam\Core\Constants
 * Controller constants stars with C_. Example: C_ACCESS_ERROR - AccessError controller.
 * Action constants starts with A + controller abbreviation + _. Example: AAE_INDEX - "index" action from AccessError controller.
 */
class ResponsiveRoute
{
    // This is default controller, when absent in route. I.e. in /
    public const DEFAULT_CONTROLLER = self::C_INDEX;
    public const DEFAULT_ACTION = self::AIND_INDEX;

    // controllers
    public const C_ACCESS_ERROR = 'access-error';
    public const C_ACCOUNTS = 'accounts';
    public const C_API = 'api';
    public const C_AUCTIONS = 'auctions';
    public const C_AUDIO = 'audio';
    public const C_BILLING_OPAYO = 'billing-opayo';
    public const C_CHANGE_PASSWORD = 'change-password';
    public const C_CROSS_DOMAIN_AJAX_REQUEST_PROXY = 'cross-domain-ajax-request-proxy';
    public const C_DOWNLOAD = 'download';
    public const C_ERROR_REPORT = 'error-report';
    public const C_FEED = 'feed';
    public const C_FORGOT_PASSWORD = 'forgot-password';
    public const C_FORGOT_USERNAME = 'forgot-username';
    public const C_HEALTH = 'health';
    public const C_IMAGE = 'image';
    public const C_INDEX = 'index';
    public const C_LANGUAGE = 'language';
    public const C_LOCATION = 'location';
    public const C_LOGIN = 'login';
    public const C_LOGOUT = 'logout';
    public const C_LOT_DETAILS = 'lot-details';
    public const C_LOT_ITEM = 'lot-item';
    public const C_MY_ALERTS = 'my-alerts';
    public const C_MY_INVOICES = 'my-invoices';
    public const C_MY_ITEMS = 'my-items';
    public const C_MY_SETTLEMENTS = 'my-settlements';
    public const C_NOTIFICATIONS = 'notifications';
    public const C_OTHER_LOTS = 'other-lots';
    public const C_PROFILE = 'profile';
    public const C_PROJECTOR = 'projector';
    public const C_REGISTER = 'register';
    public const C_REPORT_PROBLEMS = 'report-problems';
    public const C_RESET = 'reset';
    public const C_RESET_PASSWORD = 'reset-password';
    public const C_SEARCH = 'search';
    public const C_SIGNUP = 'signup';
    public const C_SITEMAP = 'sitemap';
    public const C_SSO = 'sso';
    public const C_SYNC = 'sync';
    public const C_WATCHLIST = 'watchlist';
    public const C_STACKED_TAX_INVOICE = 'stacked-tax-invoice';

    public const CONTROLLERS = [
        self::C_ACCESS_ERROR,
        self::C_ACCOUNTS,
        self::C_API,
        self::C_AUCTIONS,
        self::C_AUDIO,
        self::C_BILLING_OPAYO,
        self::C_CHANGE_PASSWORD,
        self::C_CROSS_DOMAIN_AJAX_REQUEST_PROXY,
        self::C_DOWNLOAD,
        self::C_ERROR_REPORT,
        self::C_FEED,
        self::C_FORGOT_PASSWORD,
        self::C_FORGOT_USERNAME,
        self::C_HEALTH,
        self::C_IMAGE,
        self::C_INDEX,
        self::C_LANGUAGE,
        self::C_LOCATION,
        self::C_LOGIN,
        self::C_LOGOUT,
        self::C_LOT_DETAILS,
        self::C_MY_ALERTS,
        self::C_MY_INVOICES,
        self::C_MY_ITEMS,
        self::C_MY_SETTLEMENTS,
        self::C_NOTIFICATIONS,
        self::C_OTHER_LOTS,
        self::C_PROFILE,
        self::C_PROJECTOR,
        self::C_REGISTER,
        self::C_RESET,
        self::C_SEARCH,
        self::C_SIGNUP,
        self::C_SITEMAP,
        self::C_SSO,
        self::C_SYNC,
        self::C_WATCHLIST,
        self::C_STACKED_TAX_INVOICE,
    ];

    public const CONTROLLER_ACTIONS = [
        self::C_ACCESS_ERROR => self::AAE_ACTIONS,
        self::C_ACCOUNTS => self::AACC_ACTIONS,
        self::C_API => self::AAP_ACTIONS,
        self::C_AUCTIONS => self::AA_ACTIONS,
        self::C_AUDIO => self::AAU_ACTIONS,
        self::C_BILLING_OPAYO => self::ABS_ACTIONS,
        self::C_CHANGE_PASSWORD => self::ACP_ACTIONS,
        self::C_CROSS_DOMAIN_AJAX_REQUEST_PROXY => self::ACDPARP_ACTIONS,
        self::C_ERROR_REPORT => self::AER_ACTIONS,
        self::C_DOWNLOAD => self::AD_ACTIONS,
        self::C_FEED => self::AF_ACTIONS,
        self::C_FORGOT_PASSWORD => self::AFP_ACTIONS,
        self::C_FORGOT_USERNAME => self::AFU_ACTIONS,
        self::C_HEALTH => self::AH_ACTIONS,
        self::C_IMAGE => self::AIM_ACTIONS,
        self::C_LANGUAGE => self::ALANG_ACTIONS,
        self::C_LOCATION => self::ALOC_ACTIONS,
        self::C_LOGIN => self::AL_ACTIONS,
        self::C_LOGOUT => self::ALO_ACTIONS,
        self::C_LOT_DETAILS => self::ALD_ACTIONS,
        self::C_LOT_ITEM => self::ALI_ACTIONS,
        self::C_MY_ALERTS => self::AALR_ACTIONS,
        self::C_MY_INVOICES => self::AINV_ACTIONS,
        self::C_MY_ITEMS => self::AIT_ACTIONS,
        self::C_MY_SETTLEMENTS => self::ASTL_ACTIONS,
        self::C_NOTIFICATIONS => self::AN_ACTIONS,
        self::C_OTHER_LOTS => self::AOL_ACTIONS,
        self::C_PROFILE => self::APR_ACTIONS,
        self::C_PROJECTOR => self::APRO_ACTIONS,
        self::C_REGISTER => self::AR_ACTIONS,
        self::C_REPORT_PROBLEMS => self::ARPO_ACTIONS,
        self::C_RESET_PASSWORD => self::ARP_ACTIONS,
        self::C_SEARCH => self::ASRCH_ACTIONS,
        self::C_SIGNUP => self::ASI_ACTIONS,
        self::C_SITEMAP => self::ASM_ACTIONS,
        self::C_SSO => self::ASSO_ACTIONS,
        self::C_SYNC => self::AS_ACTIONS,
        self::C_WATCHLIST => self::AW_ACTIONS,
        self::C_STACKED_TAX_INVOICE => self::ASTI_ACTIONS,
        self::C_INDEX => self::AI_ACTIONS
    ];

    // Index

    public const AIND_INDEX = 'index';

    // Access error

    public const AAE_INDEX = 'index';

    public const AAE_ACTIONS = [
        self::AAE_INDEX
    ];

    // Accounts

    public const AACC_INDEX = 'index';

    public const AACC_ACTIONS = [
        self::AACC_INDEX
    ];

    // Api

    public const AAP_FILE = 'file';
    public const AAP_GRAPHQL = 'graphql';
    public const AAP_JSON = 'json';
    public const AAP_PAYPAL = 'paypal';
    public const AAP_SMARTPAY = 'smartpay';
    public const AAP_SOAP12 = 'soap12';

    public const AAP_ACTIONS = [
        self::AAP_FILE,
        self::AAP_GRAPHQL,
        self::AAP_JSON,
        self::AAP_PAYPAL,
        self::AAP_SMARTPAY,
        self::AAP_SOAP12,
    ];

    // Auctions

    public const AA_ABSENTEE_BIDS = 'absentee-bids';
    public const AA_ASK_QUESTION = 'ask-question';
    public const AA_BIDDING_HISTORY = 'bidding-history';
    public const AA_CATALOG = 'catalog';
    public const AA_CONFIRM_BID = 'confirm-bid';
    public const AA_CONFIRM_BUY = 'confirm-buy';
    public const AA_FIRST_LOT = 'first-lot';
    public const AA_GET_IMAGE_INFO = 'get-image-info';
    public const AA_INDEX = 'index';
    public const AA_INFO = 'info';
    public const AA_LIST = 'list';
    public const AA_LIVE_SALE = 'live-sale';
    public const AA_PERMISSION_REQUIRED = 'permission-required';
    public const AA_PRINT_CATALOG = 'print-catalog';
    public const AA_PRINT_REALIZED_PRICES = 'print-realized-prices';
    public const AA_REGISTER = 'register';
    public const AA_REGISTRATION_PENDING = 'registration-pending';
    public const AA_REGISTRATION_REQUIRED = 'registration-required';
    public const AA_TELL_FRIEND = 'tell-friend';

    public const AA_ACTIONS = [
        self::AA_ABSENTEE_BIDS,
        self::AA_ASK_QUESTION,
        self::AA_BIDDING_HISTORY,
        self::AA_CATALOG,
        self::AA_CONFIRM_BID,
        self::AA_CONFIRM_BUY,
        self::AA_FIRST_LOT,
        self::AA_GET_IMAGE_INFO,
        self::AA_INDEX,
        self::AA_INFO,
        self::AA_LIST,
        self::AA_LIVE_SALE,
        self::AA_PERMISSION_REQUIRED,
        self::AA_PRINT_CATALOG,
        self::AA_PRINT_REALIZED_PRICES,
        self::AA_REGISTER,
        self::AA_REGISTRATION_PENDING,
        self::AA_REGISTRATION_REQUIRED,
        self::AA_TELL_FRIEND,
    ];

    // Audio

    public const AAU_SOUND = 'sound';

    public const AAU_ACTIONS = [
        self::AAU_SOUND
    ];

    // Billing Sagepay

    public const ABS_OPAYO_START_THREE_D = 'start-three-d';
    public const ABS_OPAYO_COMPLETE_THREE_D = 'complete-three-d';

    public const ABS_ACTIONS = [
        self::ABS_OPAYO_START_THREE_D,
        self::ABS_OPAYO_COMPLETE_THREE_D
    ];

    // Change password

    public const ACP_INDEX = 'index';

    public const ACP_ACTIONS = [
        self::ACP_INDEX
    ];

    // Cross Domain Ajax Request Proxy
    public const ACDARP_INDEX = 'index';

    public const ACDPARP_ACTIONS = [
        self::ACDARP_INDEX,
    ];

    // Error report

    public const AER_CONFIRMATION = 'confirmation';
    public const AER_INDEX = 'index';

    public const AER_ACTIONS = [
        self::AER_CONFIRMATION,
        self::AER_INDEX,
    ];

    // Download

    public const AD_AUCTION_CUSTOM_FIELD = 'auction-custom-field';
    public const AD_LOT_CUSTOM_FIELD = 'lot-custom-field';
    public const AD_RESELLER_CERT = 'reseller-cert';
    public const AD_SETTLEMENT_CHECK = 'settlement-check';
    public const AD_USER_CUSTOM_FIELD = 'user-custom-field';

    public const AD_ACTIONS = [
        self::AD_AUCTION_CUSTOM_FIELD,
        self::AD_LOT_CUSTOM_FIELD,
        self::AD_RESELLER_CERT,
        self::AD_SETTLEMENT_CHECK,
        self::AD_USER_CUSTOM_FIELD,
    ];

    // Feed

    public const AF_INDEX = 'index';

    public const AF_ACTIONS = [
        self::AF_INDEX
    ];

    // Forgot password

    public const AFP_INDEX = 'index';

    public const AFP_ACTIONS = [
        self::AFP_INDEX,
    ];

    // Forgot username

    public const AFU_INDEX = 'index';

    public const AFU_ACTIONS = [
        self::AFU_INDEX,
    ];

    // Health

    public const AH_INDEX = 'index';
    public const AH_FULL = 'full';

    public const AH_ACTIONS = [
        self::AH_INDEX,
        self::AH_FULL,
    ];

    // Image

    public const AIM_VIEW = 'view';
    public const AIM_CAPTCHA = 'captcha';
    public const AIM_BARCODE = 'barcode';

    public const AIM_ACTIONS = [
        self::AIM_VIEW,
        self::AIM_CAPTCHA,
        self::AIM_BARCODE,
    ];

    // Index

    public const AIN_INDEX = 'index';

    // Language

    public const ALANG_INDEX = 'index';

    public const ALANG_ACTIONS = [
        self::ALANG_INDEX,
    ];

    // Location

    public const ALOC_ALL_COUNTRIES_CSV = 'all-countries-csv';

    public const ALOC_ACTIONS = [
        self::ALOC_ALL_COUNTRIES_CSV,
    ];

    // Login

    public const AL_INDEX = 'index';
    public const AL_LOGIN = 'login';
    public const AL_REDIRECT_FEED = 'redirect-feed';
    public const AL_REFRESH_JWT = 'refresh-jwt';
    public const AL_REVERT_IMPERSONATE = 'revert-impersonate';

    public const AL_ACTIONS = [
        self::AL_INDEX,
        self::AL_LOGIN,
        self::AL_REDIRECT_FEED,
        self::AL_REFRESH_JWT,
        self::AL_REVERT_IMPERSONATE,
    ];


    // Logout

    public const ALO_INDEX = 'index';

    public const ALO_ACTIONS = [
        self::ALO_INDEX
    ];

    // Lot Details

    public const ALD_INDEX = 'index';

    public const ALD_ACTIONS = [
        self::ALD_INDEX,
    ];

    // Lot Item

    public const ALI_BUYER = 'buyer';
    public const ALI_GROUP = 'group';
    public const ALI_INDEX = 'index';
    public const ALI_PREVIEW = 'preview';

    public const ALI_ACTIONS = [
        self::ALI_BUYER,
        self::ALI_GROUP,
        self::ALI_INDEX,
        self::ALI_PREVIEW,
    ];

    // My Alerts

    public const AALR_DELETE = 'delete';
    public const AALR_INDEX = 'index';

    public const AALR_ACTIONS = [
        self::AALR_DELETE,
        self::AALR_INDEX,
    ];

    // My Invoices

    public const AINV_INDEX = 'index';
    public const AINV_PAYPAL = 'paypal';
    public const AINV_PDF = 'pdf';
    public const AINV_PRINT = 'print';
    public const AINV_SMARTPAY = 'smartpay';
    public const AINV_VIEW = 'view';

    public const AINV_ACTIONS = [
        self::AINV_INDEX,
        self::AINV_PAYPAL,
        self::AINV_PDF,
        self::AINV_PRINT,
        self::AINV_SMARTPAY,
        self::AINV_VIEW,
    ];

    // My Items

    public const AIT_ALL = 'all';
    public const AIT_BIDDING = 'bidding';
    public const AIT_CONSIGNED = 'consigned';
    public const AIT_INDEX = 'index';
    public const AIT_NOT_WON = 'not-won';
    public const AIT_WATCHLIST = 'watchlist';
    public const AIT_WON = 'won';

    public const AIT_ACTIONS = [
        self::AIT_ALL,
        self::AIT_BIDDING,
        self::AIT_CONSIGNED,
        self::AIT_INDEX,
        self::AIT_NOT_WON,
        self::AIT_WATCHLIST,
        self::AIT_WON,
    ];

    // My Settlements

    public const ASTL_INDEX = 'index';
    public const ASTL_VIEW = 'view';
    public const ASTL_PRINT = 'print';

    public const ASTL_ACTIONS = [
        self::ASTL_INDEX,
        self::ASTL_VIEW,
        self::ASTL_PRINT,
    ];

    // Notifications

    public const AN_INDEX = 'index';

    public const AN_ACTIONS = [
        self::AN_INDEX,
    ];

    // Other lots

    public const AOL_ITEMS = 'items';

    public const AOL_ACTIONS = [
        self::AOL_ITEMS
    ];

    // Profile

    public const APR_CHANGE_PASSWORD = 'change-password';
    public const APR_DELETE_RESELLER_CERT_FILE = 'delete-reseller-cert-file';
    public const APR_INDEX = 'index';
    public const APR_VIEW = 'view';

    public const APR_ACTIONS = [
        self::APR_CHANGE_PASSWORD,
        self::APR_DELETE_RESELLER_CERT_FILE,
        self::APR_INDEX,
        self::APR_VIEW,
    ];

    // Projector

    public const APRO_IMAGES = 'images';

    public const APRO_ACTIONS = [
        self::APRO_IMAGES,
    ];

    // Register

    public const AR_AUCTION_LOT_ITEM_CHANGES = 'auction-lot-item-changes';
    public const AR_CONFIRM_BIDDER_OPTIONS = 'confirm-bidder-options';
    public const AR_CONFIRM_SHIPPING = 'confirm-shipping';
    public const AR_INDEX = 'index';
    public const AR_REGISTRATION_CONFIRM = 'registration-confirm';
    public const AR_REVISE_BILLING = 'revise-billing';
    public const AR_SAVE_CONFIRM_SHIPPING = 'save-confirm-shipping';
    public const AR_SPECIAL_TERMS_AND_CONDITIONS = 'special-terms-and-conditions';
    public const AR_TERMS_AND_CONDITIONS = 'terms-and-conditions';

    /** @var string[] */
    public const AR_ACTIONS = [
        self::AR_INDEX,
        self::AR_AUCTION_LOT_ITEM_CHANGES,
        self::AR_CONFIRM_BIDDER_OPTIONS,
        self::AR_CONFIRM_SHIPPING,
        self::AR_REGISTRATION_CONFIRM,
        self::AR_REVISE_BILLING,
        self::AR_SAVE_CONFIRM_SHIPPING,
        self::AR_SPECIAL_TERMS_AND_CONDITIONS,
        self::AR_TERMS_AND_CONDITIONS,
    ];

    // Report problems

    public const ARPO_GET_REPORT_FORM = 'get-report-form';
    public const ARPO_LIVE_SALE = 'live-sale';

    public const ARPO_ACTIONS = [
        self::ARPO_GET_REPORT_FORM,
        self::ARPO_LIVE_SALE,
    ];

    // Reset password

    public const ARP_INDEX = 'index';

    public const ARP_ACTIONS = [
        self::ARP_INDEX
    ];

    // Search

    public const ASRCH_CHECK_USERNAME_EXISTENCE = 'check-username-existence';
    public const ASRCH_GET_ACCOUNTS = 'get-accounts';
    public const ASRCH_GET_AUCTIONEER = 'get-auctioneer';
    public const ASRCH_GET_AUCTIONS = 'get-auctions';
    public const ASRCH_GET_CATEGORIES = 'get-categories';
    public const ASRCH_GET_CHILD_CATEGORIES = 'get-child-categories';
    public const ASRCH_INDEX = 'index';

    public const ASRCH_ACTIONS = [
        self::ASRCH_CHECK_USERNAME_EXISTENCE,
        self::ASRCH_GET_ACCOUNTS,
        self::ASRCH_GET_AUCTIONEER,
        self::ASRCH_GET_AUCTIONS,
        self::ASRCH_GET_CATEGORIES,
        self::ASRCH_GET_CHILD_CATEGORIES,
        self::ASRCH_INDEX,
    ];

    // Signup

    public const ASI_INDEX = 'index';
    public const ASI_SSO_REGISTER = 'sso-register';
    public const ASI_VERIFY_EMAIL = 'verify-email';

    public const ASI_ACTIONS = [
        self::ASI_INDEX,
        self::ASI_SSO_REGISTER,
        self::ASI_VERIFY_EMAIL,
    ];

    // Sitemap

    public const ASM_GENERATE_AUCTION = 'generate-auction';
    public const ASM_GENERATE_INDEX = 'generate-index';

    public const ASM_ACTIONS = [
        self::ASM_GENERATE_AUCTION,
        self::ASM_GENERATE_INDEX,
    ];

    // SSO

    public const ASSO_INDEX = 'index';
    public const ASSO_AUTH = 'auth';
    public const ASSO_LOGIN = 'login';
    public const ASSO_LOGOUT = 'logout';
    public const ASSO_REGISTER = 'register';

    public const ASSO_ACTIONS = [
        self::ASSO_INDEX,
        self::ASSO_AUTH,
        self::ASSO_LOGIN,
        self::ASSO_LOGOUT,
        self::ASSO_REGISTER,
    ];

    // Sync

    public const AS_AUCTION = 'auction';
    public const AS_LOT = 'lot';

    public const AS_ACTIONS = [
        self::AS_AUCTION,
        self::AS_LOT,
    ];

    // Watchlist

    public const AW_ADD = 'add';
    public const AW_REMOVE = 'remove';

    public const AW_ACTIONS = [
        self::AW_ADD,
        self::AW_REMOVE
    ];

    //Stacked tax my invoice
    public const ASTI_VIEW = 'view';
    public const ASTI_PRINT = 'print';

    public const ASTI_ACTIONS = [
        self::ASTI_VIEW,
        self::ASTI_PRINT
    ];

    public const AI_ENABLE_CUSTOM_LOOK_AND_FEEL = 'enable-custom-look-and-feel';
    public const AI_ACTIONS = [
        self::AI_ENABLE_CUSTOM_LOOK_AND_FEEL
    ];
}
