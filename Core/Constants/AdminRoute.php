<?php
/**
 * SAM-6734: Apply constants for controller and action names
 */

namespace Sam\Core\Constants;

/**
 * Class AdminRoute
 * @package Sam\Core\Constants
 * Controller constants stars with C_. Example: C_ACCESS_ERROR - AccessError controller.
 * Action constants starts with A + controller abbreviation + _. Example: AAE_INDEX - "index" action from AccessError controller.
 */
class AdminRoute
{
    // This is default controller, when absent in route. I.e. in /admin
    public const DEFAULT_CONTROLLER = self::C_INDEX;
    public const DEFAULT_ACTION = self::AIN_INDEX;

    public const C_ACCESS_ERROR = 'access-error';
    public const C_INDEX = 'index';
    public const C_INSTALLATION_SETTING = 'installation-setting';
    public const C_LOGIN = 'login';
    public const C_MANAGE_ACCOUNT = 'manage-account';
    public const C_MANAGE_TAX_DEFINITION = 'manage-tax-definition';
    public const C_MANAGE_TAX_SCHEMA = 'manage-tax-schema';
    public const C_MANAGE_AUCTIONEER = 'manage-auctioneer';
    public const C_MANAGE_AUCTIONS = 'manage-auctions';
    public const C_MANAGE_AUCTION_LOCATION = 'manage-auction-location';
    public const C_MANAGE_BID_INCREMENT = 'manage-bid-increment';
    public const C_MANAGE_BUYERS_PREMIUM = 'manage-buyers-premium';
    public const C_MANAGE_BUYER_GROUP = 'manage-buyer-group';
    public const C_MANAGE_CATEGORY = 'manage-category';
    public const C_MANAGE_CHANGE_PASSWORD = 'manage-change-password';
    public const C_MANAGE_CONSIGNOR_COMMISSION_FEE = 'manage-consignor-commission-fee';
    public const C_MANAGE_COUPON = 'manage-coupon';
    public const C_MANAGE_CSV_IMPORT = 'manage-csv-import';
    public const C_MANAGE_CUSTOM_FIELD = 'manage-custom-field';
    public const C_MANAGE_CUSTOM_TEMPLATE = 'manage-custom-template';
    public const C_MANAGE_EMAIL_TEMPLATE = 'manage-email-template';
    public const C_MANAGE_FEED = 'manage-feed';
    public const C_MANAGE_HOME = 'manage-home';
    public const C_MANAGE_INVENTORY = 'manage-inventory';
    public const C_MANAGE_INVOICES = 'manage-invoices';
    public const C_MANAGE_STACKED_TAX_INVOICE = 'manage-stacked-tax-invoice';
    public const C_MANAGE_LOCATION = 'manage-location';
    public const C_MANAGE_LOGOUT = 'manage-logout';
    public const C_MANAGE_LOT_IMAGES = 'manage-lot-images';
    public const C_MANAGE_LOT_ITEM = 'manage-lot-item';
    public const C_MANAGE_MAILING_LIST_REPORT = 'manage-mailing-list-report';
    public const C_MANAGE_PLACE_BID = 'manage-place-bid';
    public const C_MANAGE_REPORTS = 'manage-reports';
    public const C_MANAGE_SALES_STAFF_REPORT = 'manage-sales-staff-report';
    public const C_MANAGE_SEARCH = 'manage-search';
    public const C_MANAGE_SETTLEMENTS = 'manage-settlements';
    public const C_MANAGE_SETTLEMENT_CHECK = 'manage-settlement-check';
    public const C_MANAGE_SITE_CONTENT = 'manage-site-content';
    public const C_MANAGE_SYNC = 'manage-sync';
    public const C_MANAGE_SYSTEM_PARAMETER = 'manage-system-parameter';
    public const C_MANAGE_TRANSLATION = 'manage-translation';
    public const C_MANAGE_USERS = 'manage-users';
    public const C_RTB_INCREMENT = 'rtb-increment';
    public const C_RTB_MESSAGE = 'rtb-message';

    public const CONTROLLERS = [
        self::C_ACCESS_ERROR,
        self::C_INDEX,
        self::C_INSTALLATION_SETTING,
        self::C_LOGIN,
        self::C_MANAGE_ACCOUNT,
        self::C_MANAGE_AUCTIONEER,
        self::C_MANAGE_AUCTIONS,
        self::C_MANAGE_BID_INCREMENT,
        self::C_MANAGE_BUYERS_PREMIUM,
        self::C_MANAGE_BUYER_GROUP,
        self::C_MANAGE_CATEGORY,
        self::C_MANAGE_CHANGE_PASSWORD,
        self::C_MANAGE_CONSIGNOR_COMMISSION_FEE,
        self::C_MANAGE_COUPON,
        self::C_MANAGE_CSV_IMPORT,
        self::C_MANAGE_CUSTOM_FIELD,
        self::C_MANAGE_CUSTOM_TEMPLATE,
        self::C_MANAGE_EMAIL_TEMPLATE,
        self::C_MANAGE_FEED,
        self::C_MANAGE_HOME,
        self::C_MANAGE_INVENTORY,
        self::C_MANAGE_INVOICES,
        self::C_MANAGE_STACKED_TAX_INVOICE,
        self::C_MANAGE_LOCATION,
        self::C_MANAGE_LOGOUT,
        self::C_MANAGE_LOT_IMAGES,
        self::C_MANAGE_LOT_ITEM,
        self::C_MANAGE_MAILING_LIST_REPORT,
        self::C_MANAGE_PLACE_BID,
        self::C_MANAGE_REPORTS,
        self::C_MANAGE_SALES_STAFF_REPORT,
        self::C_MANAGE_SEARCH,
        self::C_MANAGE_SETTLEMENTS,
        self::C_MANAGE_SETTLEMENT_CHECK,
        self::C_MANAGE_SITE_CONTENT,
        self::C_MANAGE_SYNC,
        self::C_MANAGE_SYSTEM_PARAMETER,
        self::C_MANAGE_TAX_DEFINITION,
        self::C_MANAGE_TAX_SCHEMA,
        self::C_MANAGE_TRANSLATION,
        self::C_MANAGE_USERS,
        self::C_RTB_INCREMENT,
        self::C_RTB_MESSAGE,
    ];

    public const CONTROLLER_ACTIONS = [
        self::C_ACCESS_ERROR => self::AAE_ACTIONS,
        self::C_INDEX => self::AIN_ACTIONS,
        self::C_INSTALLATION_SETTING => self::AMIS_ACTIONS,
        self::C_LOGIN => self::AL_ACTIONS,
        self::C_MANAGE_ACCOUNT => self::AMACC_ACTIONS,
        self::C_MANAGE_AUCTIONEER => self::AMAEER_ACTIONS,
        self::C_MANAGE_AUCTIONS => self::AMA_ACTIONS,
        self::C_MANAGE_AUCTION_LOCATION => self::AMAL_ACTIONS,
        self::C_MANAGE_BID_INCREMENT => self::AMBI_ACTIONS,
        self::C_MANAGE_BUYERS_PREMIUM => self::AMBP_ACTIONS,
        self::C_MANAGE_BUYER_GROUP => self::AMBG_ACTIONS,
        self::C_MANAGE_CATEGORY => self::AMCAT_ACTIONS,
        self::C_MANAGE_CHANGE_PASSWORD => self::AMCP_ACTIONS,
        self::C_MANAGE_CONSIGNOR_COMMISSION_FEE => self::AMCCF_ACTIONS,
        self::C_MANAGE_COUPON => self::AMCOUPON_ACTIONS,
        self::C_MANAGE_CSV_IMPORT => self::AMCI_ACTIONS,
        self::C_MANAGE_CUSTOM_FIELD => self::AMCF_ACTIONS,
        self::C_MANAGE_CUSTOM_TEMPLATE => self::AMCT_ACTIONS,
        self::C_MANAGE_EMAIL_TEMPLATE => self::AMETPL_ACTIONS,
        self::C_MANAGE_FEED => self::AMFEED_ACTIONS,
        self::C_MANAGE_HOME => self::AMH_ACTIONS,
        self::C_MANAGE_INVENTORY => self::AMIN_ACTIONS,
        self::C_MANAGE_INVOICES => self::AMI_ACTIONS,
        self::C_MANAGE_STACKED_TAX_INVOICE => self::AMSTI_ACTIONS,
        self::C_MANAGE_LOCATION => self::AML_ACTIONS,
        self::C_MANAGE_LOGOUT => self::ALO_ACTIONS,
        self::C_MANAGE_LOT_IMAGES => self::AMLIMG_ACTIONS,
        self::C_MANAGE_LOT_ITEM => self::AMLI_ACTIONS,
        self::C_MANAGE_MAILING_LIST_REPORT => self::AMMLR_ACTIONS,
        self::C_MANAGE_PLACE_BID => self::AMPB_ACTIONS,
        self::C_MANAGE_REPORTS => self::AMR_ACTIONS,
        self::C_MANAGE_SALES_STAFF_REPORT => self::AMSSR_ACTIONS,
        self::C_MANAGE_SEARCH => self::AMSEA_ACTIONS,
        self::C_MANAGE_SETTLEMENTS => self::AMS_ACTIONS,
        self::C_MANAGE_SETTLEMENT_CHECK => self::AMSCH_ACTIONS,
        self::C_MANAGE_SITE_CONTENT => self::AMSC_ACTIONS,
        self::C_MANAGE_SYNC => self::AMSYNC_ACTIONS,
        self::C_MANAGE_SYSTEM_PARAMETER => self::AMSP_ACTIONS,
        self::C_MANAGE_TAX_DEFINITION => self::AMTD_ACTIONS,
        self::C_MANAGE_TAX_SCHEMA => self::AMTS_ACTIONS,
        self::C_MANAGE_TRANSLATION => self::AMT_ACTIONS,
        self::C_MANAGE_USERS => self::AMU_ACTIONS,
        self::C_RTB_INCREMENT => self::ARTBINC_ACTIONS,
        self::C_RTB_MESSAGE => self::ARTBMES_ACTIONS,
    ];

    // Access error

    public const AAE_INDEX = 'index';

    public const AAE_ACTIONS = [
        self::AAE_INDEX
    ];

    // Index

    public const AIN_INDEX = 'index';

    public const AIN_ACTIONS = [
        self::AIN_INDEX
    ];

    //Manage system parameter
    public const AMSP_ADMIN_OPTION = 'admin-option';
    public const AMSP_AUCTION = 'auction';
    public const AMSP_HYBRID_AUCTION = 'hybrid-auction';
    public const AMSP_INDEX = self::DEFAULT_ACTION;
    public const AMSP_INTEGRATION = 'integration';
    public const AMSP_INVOICING = 'invoicing';
    public const AMSP_PAYMENT = 'payment';
    public const AMSP_LAYOUT_AND_SITE_CUSTOMIZATION = 'layout-and-site-customization';
    public const AMSP_LIVE_HYBRID_AUCTION = 'live-hybrid-auction';
    public const AMSP_SYSTEM = 'system';
    public const AMSP_TIMED_ONLINE_AUCTION = 'timed-online-auction';
    public const AMSP_USER_OPTION = 'user-option';
    public const AMSP_UPLOAD_TMP_FAVICON = 'upload-tmp-favicon';
    public const AMSP_REMOVE_FAVICON = 'remove-favicon';

    public const AMSP_ACTIONS = [
        self::AMSP_ADMIN_OPTION,
        self::AMSP_AUCTION,
        self::AMSP_HYBRID_AUCTION,
        self::AMSP_INDEX,
        self::AMSP_INTEGRATION,
        self::AMSP_INVOICING,
        self::AMSP_PAYMENT,
        self::AMSP_LAYOUT_AND_SITE_CUSTOMIZATION,
        self::AMSP_LIVE_HYBRID_AUCTION,
        self::AMSP_SYSTEM,
        self::AMSP_TIMED_ONLINE_AUCTION,
        self::AMSP_USER_OPTION,
        self::AMSP_UPLOAD_TMP_FAVICON,
        self::AMSP_REMOVE_FAVICON
    ];

    // Manage translation
    public const AMT_INDEX = self::DEFAULT_ACTION;
    public const AMT_EDIT = 'edit';

    public const AMT_LANG_SET_MAINMENU = 'mainmenu'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_AUCTIONS = 'auctions'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_CATALOG = 'catalog'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_SEARCH = 'search'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_ITEM = 'item'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_MYINVOICES = 'myinvoices'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_MYITEMS = 'myitems'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_MYSETTLEMENTS = 'mysettlements'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_USER = 'user'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_LOGIN = 'login'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_BIDDERCLIENT = 'bidderclient'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_HYBRIDCLIENT = 'hybridclient'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_GENERAL = 'general'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_POPUPS = 'popups'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_USERCUSTOMFIELDS = 'usercustomfields'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_CUSTOMFIELDS = 'customfields'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_AUCTIONCUSTOMFIELDS = 'auctioncustomfields'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_AUCTION_DETAILS = 'auction_details'; // for breadcrumbs and 'set' route param only.
    public const AMT_LANG_SET_LOT_DETAILS = 'lot_details'; // for breadcrumbs and 'set' route param only.

    public const AMT_ACTIONS = [
        self::AMT_INDEX,
        self::AMT_EDIT
    ];

    //Manage custom template
    public const AMCT_AUCTION_INFO = 'auction-info';
    public const AMCT_AUCTION_CAPTION = 'auction-caption';
    public const AMCT_LOT_ITEM_DETAILS = 'lot-item-details';
    public const AMCT_RTB_DETAILS = 'rtb-details';

    public const AMCT_ACTIONS = [
        self::AMCT_AUCTION_INFO,
        self::AMCT_AUCTION_CAPTION,
        self::AMCT_LOT_ITEM_DETAILS,
        self::AMCT_RTB_DETAILS
    ];

    // Installation setting

    public const AMIS_DELETE = 'delete';
    public const AMIS_EDIT = 'edit';
    public const AMIS_INDEX = 'index';
    public const AMIS_INFO = 'info';
    public const AMIS_LOGIN = 'login';
    public const AMIS_LOGOUT = 'logout';

    public const AMIS_ACTIONS = [
        self::AMIS_DELETE,
        self::AMIS_EDIT,
        self::AMIS_INDEX,
        self::AMIS_INFO,
        self::AMIS_LOGIN,
        self::AMIS_LOGOUT,
    ];

    // Login

    public const AL_INDEX = 'index';

    public const AL_ACTIONS = [
        self::AL_INDEX
    ];

    // Manage accounts
    public const AMACC_INDEX = self::DEFAULT_ACTION;
    public const AMACC_LIST = 'list';
    public const AMACC_EDIT = 'edit';
    public const AMACC_CREATE = 'create';

    public const AMACC_ACTIONS = [
        self::AMACC_INDEX,
        self::AMACC_LIST,
        self::AMACC_EDIT,
        self::AMACC_CREATE,
    ];

    // Manage auctions

    public const AMA_ADD_LOT = 'add-lot';
    public const AMA_ADD_NEW_BIDDER = 'add-new-bidder';
    public const AMA_ADJOINING_LOT = 'adjoining-lot';
    public const AMA_APPROVE_RESELLER = 'approve-reseller';
    public const AMA_AUCTIONEER = 'auctioneer';
    public const AMA_AUCTION_INVOICE = 'auction-invoice';
    public const AMA_AUCTION_STACKED_TAX_INVOICE = 'auction-stacked-tax-invoice';
    public const AMA_AUCTION_INVOICE_BIDDER_AUTOCOMPLETE = 'auction-invoice-bidder-autocomplete';
    public const AMA_AUCTION_LOT = 'auction-lot';
    public const AMA_AUCTION_LOT_CSV = 'auction-lot-csv';
    public const AMA_AUCTION_REPORTS = 'auction-reports';
    public const AMA_BIDDERS = 'bidders';
    public const AMA_BIDDERS_ABSENTEE = 'bidders-absentee';
    public const AMA_BIDDERS_ABSENTEE_LOT = 'bidders-absentee-lot';
    public const AMA_BIDDER_EXPORT_BIDMASTER = 'bidder-export-bidmaster';
    public const AMA_BIDDER_EXPORT_CSV = 'bidder-export-csv';
    public const AMA_BIDDER_EXPORT_PACTS = 'bidder-export-pacts';
    public const AMA_BIDDER_INTEREST = 'bidder-interest';
    public const AMA_BIDDER_OVERVIEW = 'bidder-overview';
    public const AMA_BID_BOOK = 'bid-book';
    public const AMA_BID_BOOK_PRINT = 'bid-book-print';
    public const AMA_BID_INCREMENTS = 'bid-increments';
    public const AMA_BID_MASTER_REPORT = 'bid-master-report';
    public const AMA_BID_REPORT_RENDER = 'bid-report-render';
    public const AMA_BID_REPORT_VALIDATE = 'bid-report-validate';
    public const AMA_BUYERS_PREMIUM = 'buyers-premium';
    public const AMA_CONSIGNOR_SCHEDULE = 'consignor-schedule';
    public const AMA_CREATE = 'create';
    public const AMA_CUSTOM_CSV_EXPORT = 'custom-csv-export';
    public const AMA_EDIT = 'edit';
    public const AMA_DELETE = 'delete';
    public const AMA_EDIT_LOT = 'edit-lot';
    public const AMA_EDIT_LOT_AUCTION_BIDDER_AUTOCOMPLETE = 'edit-lot-auction-bidder-autocomplete';
    public const AMA_EDIT_LOT_CONSIGNOR_AUTOCOMPLETE = 'edit-lot-consignor-autocomplete';
    public const AMA_EDIT_LOT_POPULATE_TAX_SCHEMA = 'edit-lot-populate-tax-schema';
    public const AMA_EMAIL = 'email';
    public const AMA_ENCODE = 'encode';
    public const AMA_ENTER_BIDS = 'enter-bids';
    public const AMA_HISTORY = 'history';
    public const AMA_IMPORT_SAMPLE = 'import-sample';
    public const AMA_INDEX = 'index';
    public const AMA_LAST_BIDS_REPORT = 'last-bids-report';
    public const AMA_LIVE_TRAIL = 'live-trail';
    public const AMA_LOCATION_AUTOCOMPLETE = 'location-autocomplete';
    public const AMA_LOTS = 'lots';
    public const AMA_LOT_BID_HISTORY = 'lot-bid-history';
    public const AMA_LOT_LIST_QUICK_EDIT_AUCTION_BIDDER_AUTOCOMPLETE = 'lot-list-quick-edit-auction-bidder-autocomplete';
    public const AMA_LOT_LIST_QUICK_EDIT_CONSIGNOR_AUTOCOMPLETE = 'lot-list-quick-edit-consignor-autocomplete';
    public const AMA_LOT_PRESALE = 'lot-presale';
    public const AMA_LOT_SYNC = 'lot-sync';
    public const AMA_PERMISSIONS = 'permissions';
    public const AMA_PHONE_BIDDERS = 'phone-bidders';
    public const AMA_PHONE_BIDDER_AUCTION_BIDDER_AUTOCOMPLETE = 'phone-bidder-auction-bidder-autocomplete';
    public const AMA_PHONE_BIDDER_CSV = 'phone-bidder-csv';
    public const AMA_PRESALE_CSV = 'presale-csv';
    public const AMA_PROJECTOR = 'projector';
    public const AMA_PROJECTOR_POP = 'projector-pop';
    public const AMA_PROJECTOR_POP_SIMPLE = 'projector-pop-simple';
    public const AMA_REMOVE_LOT = 'remove-lot';
    public const AMA_RENDER_BIDDER_DASHBOARD = 'render-bidder-dashboard';
    public const AMA_REOPEN = 'reopen';
    public const AMA_RESET = 'reset';
    public const AMA_RESET_ALL_VIEWS = 'reset-all-views';
    public const AMA_RESULT = 'result';
    public const AMA_RTB_BIDDER_AUTOCOMPLETE = 'rtb-bidder-autocomplete';
    public const AMA_RTB_USERS = 'rtb-users';
    public const AMA_RUN = 'run';
    public const AMA_SETTINGS = 'settings';
    public const AMA_SHOW_IMPORT = 'show-import';
    public const AMA_SMS = 'sms';
    public const AMA_SORT_BY_CONSIGNOR = 'sort-by-consignor';
    public const AMA_SPENDING_REPORT = 'spending-report';
    public const AMA_UNSOLD_LOTS = 'unsold-lots';
    public const AMA_UPDATE_BIDDER_DASHBOARD = 'update-bidder-dashboard';
    public const AMA_UPLOAD_AUCTION_IMAGE = 'upload-auction-image';
    public const AMA_WITHDRAW_RESELLER = 'withdraw-reseller';

    public const AMA_ACTIONS = [
        self::AMA_ADD_LOT,
        self::AMA_ADD_NEW_BIDDER,
        self::AMA_ADJOINING_LOT,
        self::AMA_APPROVE_RESELLER,
        self::AMA_AUCTIONEER,
        self::AMA_AUCTION_INVOICE,
        self::AMA_AUCTION_STACKED_TAX_INVOICE,
        self::AMA_AUCTION_INVOICE_BIDDER_AUTOCOMPLETE,
        self::AMA_AUCTION_LOT,
        self::AMA_AUCTION_LOT_CSV,
        self::AMA_AUCTION_REPORTS,
        self::AMA_BIDDERS,
        self::AMA_BIDDERS_ABSENTEE,
        self::AMA_BIDDERS_ABSENTEE_LOT,
        self::AMA_BIDDER_EXPORT_BIDMASTER,
        self::AMA_BIDDER_EXPORT_CSV,
        self::AMA_BIDDER_EXPORT_PACTS,
        self::AMA_BIDDER_INTEREST,
        self::AMA_BIDDER_OVERVIEW,
        self::AMA_BID_BOOK,
        self::AMA_BID_BOOK_PRINT,
        self::AMA_BID_INCREMENTS,
        self::AMA_BID_MASTER_REPORT,
        self::AMA_BID_REPORT_RENDER, // Auction List > Reports > Download all bids
        self::AMA_BID_REPORT_VALIDATE, // Auction List > Reports > Download all bids
        self::AMA_BUYERS_PREMIUM,
        self::AMA_CONSIGNOR_SCHEDULE,
        self::AMA_CREATE,
        self::AMA_CUSTOM_CSV_EXPORT,
        self::AMA_EDIT,
        self::AMA_DELETE,
        self::AMA_EDIT_LOT,
        self::AMA_EDIT_LOT_AUCTION_BIDDER_AUTOCOMPLETE,
        self::AMA_EDIT_LOT_CONSIGNOR_AUTOCOMPLETE,
        self::AMA_EDIT_LOT_POPULATE_TAX_SCHEMA,
        self::AMA_EMAIL,
        self::AMA_ENCODE,
        self::AMA_ENTER_BIDS,
        self::AMA_HISTORY,
        self::AMA_IMPORT_SAMPLE,
        self::AMA_INDEX,
        self::AMA_LAST_BIDS_REPORT,
        self::AMA_LIVE_TRAIL,
        self::AMA_LOCATION_AUTOCOMPLETE,
        self::AMA_LOTS,
        self::AMA_LOT_BID_HISTORY,
        self::AMA_LOT_LIST_QUICK_EDIT_AUCTION_BIDDER_AUTOCOMPLETE,
        self::AMA_LOT_LIST_QUICK_EDIT_CONSIGNOR_AUTOCOMPLETE,
        self::AMA_LOT_PRESALE,
        self::AMA_LOT_SYNC,
        self::AMA_PERMISSIONS,
        self::AMA_PHONE_BIDDERS,
        self::AMA_PHONE_BIDDER_AUCTION_BIDDER_AUTOCOMPLETE,
        self::AMA_PHONE_BIDDER_CSV,
        self::AMA_PRESALE_CSV,
        self::AMA_PROJECTOR,
        self::AMA_PROJECTOR_POP,
        self::AMA_PROJECTOR_POP_SIMPLE,
        self::AMA_REMOVE_LOT,
        self::AMA_RENDER_BIDDER_DASHBOARD,
        self::AMA_REOPEN,
        self::AMA_RESET,
        self::AMA_RESET_ALL_VIEWS,
        self::AMA_RESULT,
        self::AMA_RTB_BIDDER_AUTOCOMPLETE,
        self::AMA_RTB_USERS,
        self::AMA_RUN,
        self::AMA_SETTINGS,
        self::AMA_SHOW_IMPORT,
        self::AMA_SMS,
        self::AMA_SORT_BY_CONSIGNOR,
        self::AMA_SPENDING_REPORT,
        self::AMA_UNSOLD_LOTS,
        self::AMA_UPDATE_BIDDER_DASHBOARD,
        self::AMA_UPLOAD_AUCTION_IMAGE,
        self::AMA_WITHDRAW_RESELLER,
    ];

    //Manage auction location
    public const AMAL_ADD = 'add';
    public const AMAL_ACTIONS = [
        self::AMAL_ADD,
    ];

    // Manage auctioneer
    public const AMAEER_INDEX = self::DEFAULT_ACTION;
    public const AMAEER_LIST = 'list';

    public const AMAEER_ACTIONS = [
        self::AMAEER_INDEX,
        self::AMAEER_LIST,
    ];

    // Manage buyers premium
    public const AMBP_INDEX = self::DEFAULT_ACTION;
    public const AMBP_CREATE = 'create';
    public const AMBP_EDIT = 'edit';
    public const AMBP_LIST = 'list';

    public const AMBP_ACTIONS = [
        self::AMBP_CREATE,
        self::AMBP_EDIT,
        self::AMBP_INDEX,
        self::AMBP_LIST,

    ];

    // Manage category

    public const AMCAT_DELETE = 'delete';
    public const AMCAT_GET = 'get';
    public const AMCAT_INDEX = self::DEFAULT_ACTION;
    public const AMCAT_MOVE = 'move';

    public const AMCAT_ACTIONS = [
        self::AMCAT_DELETE,
        self::AMCAT_GET,
        self::AMCAT_INDEX,
        self::AMCAT_MOVE,
    ];

    // Manage change password

    public const AMCP_INDEX = 'index';

    public const AMCP_ACTIONS = [
        self::AMCP_INDEX
    ];

    // Manage csv import

    public const AMCI_UPLOAD_ABORT = 'upload-abort';
    public const AMCI_UPLOAD_FILES = 'upload-files';
    public const AMCI_UPLOAD_ROWS = 'upload-rows';

    public const AMCI_ACTIONS = [
        self::AMCI_UPLOAD_ABORT,
        self::AMCI_UPLOAD_FILES,
        self::AMCI_UPLOAD_ROWS,
    ];

    // Manage custom fields

    public const AMCF_EDIT_AUCTION = 'edit-auction';
    public const AMCF_EDIT_LOT_ITEM = 'edit-lot-item';
    public const AMCF_EDIT_USER = 'edit-user';
    public const AMCF_INDEX = 'index';
    public const AMCF_LIST_AUCTION = 'list-auction';
    public const AMCF_LIST_LOT_ITEM = 'list-lot-item';
    public const AMCF_LIST_USER = 'list-user';
    public const AMCF_USER_CUSTOM_FIELD_DELETE = 'user-custom-field-delete';
    public const AMCF_LOT_ITEM_CUSTOM_FIELD_DELETE = 'lot-item-custom-field-delete';
    public const AMCF_AUCTION_CUSTOM_FIELD_DELETE = 'auction-custom-field-delete';

    public const AMCF_ACTIONS = [
        self::AMCF_EDIT_AUCTION,
        self::AMCF_EDIT_LOT_ITEM,
        self::AMCF_EDIT_USER,
        self::AMCF_INDEX,
        self::AMCF_LIST_AUCTION,
        self::AMCF_LIST_LOT_ITEM,
        self::AMCF_LIST_USER,
        self::AMCF_USER_CUSTOM_FIELD_DELETE,
        self::AMCF_LOT_ITEM_CUSTOM_FIELD_DELETE,
        self::AMCF_AUCTION_CUSTOM_FIELD_DELETE
    ];

    // Manage Email templates
    public const AMETPL_INDEX = self::DEFAULT_ACTION;
    public const AMETPL_LIST = 'list';

    public const AMETPL_ACTIONS = [
        self::AMETPL_INDEX,
        self::AMETPL_LIST
    ];

    // Manage home

    public const AMH_ACTIVE_AUCTIONS = 'active-auctions';
    public const AMH_CLOSED_AUCTIONS = 'closed-auctions';
    public const AMH_DASHBOARD = 'dashboard';
    public const AMH_INDEX = 'index';
    public const AMH_INVOICE_OVERVIEW = 'invoice-overview';
    public const AMH_MESSAGE_CLEAR = 'message-clear';
    public const AMH_MESSAGE_READ = 'message-read';
    public const AMH_MESSAGE_SAVE = 'message-save';
    public const AMH_MESSAGE_UPDATE = 'message-update';
    public const AMH_SETTLEMENT_OVERVIEW = 'settlement-overview';
    public const AMH_ENABLE_CUSTOM_LOOK_AND_FEEL = 'enable-custom-look-and-feel';

    public const AMH_ACTIONS = [
        self::AMH_ACTIVE_AUCTIONS,
        self::AMH_CLOSED_AUCTIONS,
        self::AMH_DASHBOARD,
        self::AMH_INDEX,
        self::AMH_INVOICE_OVERVIEW,
        self::AMH_MESSAGE_CLEAR,
        self::AMH_MESSAGE_READ,
        self::AMH_MESSAGE_SAVE,
        self::AMH_MESSAGE_UPDATE,
        self::AMH_SETTLEMENT_OVERVIEW,
        self::AMH_ENABLE_CUSTOM_LOOK_AND_FEEL,
    ];

    // Manage inventory

    public const AMIN_ADD = 'add';
    public const AMIN_BARCODE_OPERATIONS = 'barcode-operations';
    public const AMIN_CSV_EXPORT = 'csv-export';
    public const AMIN_DELETE = 'delete';
    public const AMIN_EDIT = 'edit';
    public const AMIN_EDIT_AUCTION_BIDDER_AUTOCOMPLETE = 'edit-auction-bidder-autocomplete';
    public const AMIN_EDIT_CONSIGNOR_AUTOCOMPLETE = 'edit-consignor-autocomplete';
    public const AMIN_EDIT_POPULATE_TAX_SCHEMA = 'edit-populate-tax-schema';
    public const AMIN_IMPORT_SAMPLE = 'import-sample';
    public const AMIN_INDEX = 'index';
    public const AMIN_ITEMS = 'items';
    public const AMIN_LIST_FILTER_CONSIGNOR_AUTOCOMPLETE = 'list-filter-consignor-autocomplete';
    public const AMIN_PREVIEW_IN_AUCTION = 'preview-in-auction';

    public const AMIN_ACTIONS = [
        self::AMIN_ADD,
        self::AMIN_BARCODE_OPERATIONS,
        self::AMIN_CSV_EXPORT,
        self::AMIN_DELETE,
        self::AMIN_EDIT,
        self::AMIN_EDIT_AUCTION_BIDDER_AUTOCOMPLETE,
        self::AMIN_EDIT_CONSIGNOR_AUTOCOMPLETE,
        self::AMIN_EDIT_POPULATE_TAX_SCHEMA,
        self::AMIN_IMPORT_SAMPLE,
        self::AMIN_INDEX,
        self::AMIN_ITEMS,
        self::AMIN_LIST_FILTER_CONSIGNOR_AUTOCOMPLETE,
        self::AMIN_PREVIEW_IN_AUCTION,
    ];

    // Manage invoices

    public const AMI_BIDDER_AUTOCOMPLETE = 'bidder-autocomplete';
    public const AMI_EXPORT = 'export';
    public const AMI_EXPORT_CUSTOM = 'export-custom';
    public const AMI_GENERATE = 'generate';
    public const AMI_INDEX = 'index';
    public const AMI_ITEMS_SOLD = 'items-sold';
    public const AMI_MULTIPLEPRINT = 'multipleprint';
    public const AMI_PACKING_SLIP = 'packing-slip';
    public const AMI_PDF = 'pdf';
    public const AMI_PRINT = 'print';
    public const AMI_VIEW = 'view';
    public const AMI_DELETE = 'delete';

    public const AMI_ACTIONS = [
        self::AMI_BIDDER_AUTOCOMPLETE,
        self::AMI_EXPORT,
        self::AMI_EXPORT_CUSTOM,
        self::AMI_GENERATE,
        self::AMI_INDEX,
        self::AMI_ITEMS_SOLD,
        self::AMI_MULTIPLEPRINT,
        self::AMI_PACKING_SLIP,
        self::AMI_PDF,
        self::AMI_PRINT,
        self::AMI_VIEW,
        self::AMI_DELETE,
    ];

    // Manage Stacked Tax Invoice
    public const AMSTI_BIDDER_AUTOCOMPLETE = 'bidder-autocomplete';
    public const AMSTI_CREATE_PAYMENT = 'create-payment';
    public const AMSTI_CREATE_SERVICE_FEE = 'create-service-fee';
    public const AMSTI_DELETE = 'delete';
    public const AMSTI_EDIT = 'edit';
    public const AMSTI_EDIT_ITEM = 'edit-item';
    public const AMSTI_EDIT_PAYMENT = 'edit-payment';
    public const AMSTI_EDIT_SERVICE_FEE = 'edit-service-fee';
    public const AMSTI_GENERATE = 'generate';
    public const AMSTI_LIST = 'list';
    public const AMSTI_MULTIPLEPRINT = 'multipleprint';
    public const AMSTI_PRINT = 'print';
    public const AMSTI_EXPORT = 'export';
    public const AMSTI_ITEMS_SOLD = 'items-sold';

    public const AMSTI_ACTIONS = [
        self::AMSTI_BIDDER_AUTOCOMPLETE,
        self::AMSTI_CREATE_PAYMENT,
        self::AMSTI_CREATE_SERVICE_FEE,
        self::AMSTI_DELETE,
        self::AMSTI_EDIT,
        self::AMSTI_EDIT_ITEM,
        self::AMSTI_EDIT_PAYMENT,
        self::AMSTI_EDIT_SERVICE_FEE,
        self::AMSTI_GENERATE,
        self::AMSTI_LIST,
        self::AMSTI_MULTIPLEPRINT,
        self::AMSTI_PRINT,
        self::AMSTI_EXPORT,
        self::AMSTI_ITEMS_SOLD,
    ];

    // Manage location
    public const AML_CREATE = 'create';
    public const AML_EDIT = 'edit';
    public const AML_IMPORT_SAMPLE = 'import-sample';
    public const AML_INDEX = self::DEFAULT_ACTION;
    public const AML_LIST = 'list';
    public const AML_LIST_TABULATOR = 'list-tabulator';
    public const AML_GET_LOCATIONS = 'get-locations';
    public const AML_UPDATE = 'update';
    public const AML_DELETE = 'delete';

    public const AML_ACTIONS = [
        self::AML_CREATE,
        self::AML_EDIT,
        self::AML_IMPORT_SAMPLE,
        self::AML_INDEX,
        self::AML_LIST,
        self::AML_LIST_TABULATOR,
        self::AML_GET_LOCATIONS,
        self::AML_UPDATE,
        self::AML_DELETE,
    ];

    // Manage logout

    public const ALO_INDEX = 'index';

    public const ALO_ACTIONS = [
        self::ALO_INDEX
    ];

    // Manage lot images

    public const AMLIMG_ASSOCIATE = 'associate';
    public const AMLIMG_GET_LOT_IMAGES = 'get-lot-images';
    public const AMLIMG_INDEX = 'index';
    public const AMLIMG_ORDER_BY_FILENAME = 'order-by-filename';
    public const AMLIMG_REMOVE_IMAGE_FROM_BUCKET = 'remove-image-from-bucket';
    public const AMLIMG_REMOVE_IMAGE_FROM_LOT = 'remove-image-from-lot';
    public const AMLIMG_REMOVE_LOT_IMAGE_TEMP = 'remove-lot-image-temp';
    public const AMLIMG_REMOVE_ALL_IMAGES_FROM_BUCKET = 'remove-all-images-from-bucket';
    public const AMLIMG_SAVE_LOT_IMAGES = 'save-lot-images';
    public const AMLIMG_SAVE_LOT_IMAGES_ORDER = 'save-lot-images-order';
    public const AMLIMG_SAVE_ORDER = 'save-order';
    public const AMLIMG_SHOW_IMPORT = 'show-import';
    public const AMLIMG_UPLOAD_IN_BUCKET = 'upload-in-bucket';
    public const AMLIMG_UPLOAD_LOT_IMAGE_FROM_URL = 'upload-lot-image-from-url';
    public const AMLIMG_UPLOAD_LOT_IMAGE_TEMP = 'upload-lot-image-temp';

    public const AMLIMG_ACTIONS = [
        self::AMLIMG_ASSOCIATE,
        self::AMLIMG_GET_LOT_IMAGES,
        self::AMLIMG_INDEX,
        self::AMLIMG_ORDER_BY_FILENAME,
        self::AMLIMG_REMOVE_ALL_IMAGES_FROM_BUCKET,
        self::AMLIMG_REMOVE_IMAGE_FROM_BUCKET,
        self::AMLIMG_REMOVE_IMAGE_FROM_LOT,
        self::AMLIMG_REMOVE_LOT_IMAGE_TEMP,
        self::AMLIMG_SAVE_LOT_IMAGES,
        self::AMLIMG_SAVE_LOT_IMAGES_ORDER,
        self::AMLIMG_SAVE_ORDER,
        self::AMLIMG_SHOW_IMPORT,
        self::AMLIMG_UPLOAD_IN_BUCKET,
        self::AMLIMG_UPLOAD_LOT_IMAGE_FROM_URL,
        self::AMLIMG_UPLOAD_LOT_IMAGE_TEMP,
    ];

    // Manage lot item

    public const AMLI_CATALOG = 'catalog';
    public const AMLI_CLONE_LOT = 'clone-lot';

    public const AMLI_ACTIONS = [
        self::AMLI_CATALOG,
        self::AMLI_CLONE_LOT,
    ];

    // Manage Mailing lists report
    public const AMMLR_CREATE = 'create';
    public const AMMLR_CSV = 'csv';
    public const AMMLR_EDIT = 'edit';
    public const AMMLR_INDEX = self::DEFAULT_ACTION;
    public const AMMLR_VIEW_REPORT = 'view-report';

    public const AMMLR_ACTIONS = [
        self::AMMLR_CREATE,
        self::AMMLR_CSV,
        self::AMMLR_EDIT,
        self::AMMLR_INDEX,
        self::AMMLR_VIEW_REPORT,
    ];


    // Manage place bid

    public const AMPB_INDEX = 'index';

    public const AMPB_ACTIONS = [
        self::AMPB_INDEX
    ];

    // Manage sales staff report

    public const AMSSR_INDEX = 'index';
    public const AMSSR_CSV = 'csv';
    public const AMSSR_USER_AUTOCOMPLETE = 'user-autocomplete';

    public const AMSSR_ACTIONS = [
        self::AMSSR_INDEX,
        self::AMSSR_CSV,
        self::AMSSR_USER_AUTOCOMPLETE,
    ];

    // Manage reports

    public const AMR_AUCTIONS = 'auctions';
    public const AMR_AUCTIONS_CSV = 'auctions-csv';
    public const AMR_AUDIT_TRAIL = 'audit-trail';
    public const AMR_AUDIT_TRAIL_CSV = 'audit-trail-csv';
    public const AMR_BASIC = 'basic';
    public const AMR_CONSIGNORS = 'consignors';
    public const AMR_CONSIGNORS_FILTER_CONSIGNOR_AUTOCOMPLETE = 'consignors-filter-consignor-autocomplete';
    public const AMR_CONSIGNORS_PDF = 'consignors-pdf';
    public const AMR_CONSIGNORS_PDF_REPORT = 'consignors-pdf-report';
    public const AMR_CONSIGNORS_PRINT = 'consignors-print';
    public const AMR_CUSTOM_LOTS = 'custom-lots';
    public const AMR_CUSTOM_LOTS_CSV = 'custom-lots-csv';
    public const AMR_CUSTOM_LOTS_PRINT = 'custom-lots-print';
    public const AMR_CUSTOM_LOTS_TEMPLATE = 'custom-lots-template';
    public const AMR_DOCUMENT_VIEWS = 'document-views';
    public const AMR_DOCVIEWS_CSV = 'docviews-csv';
    public const AMR_IMPORT_SAMPLE = 'import-sample';
    public const AMR_INDEX = 'index';
    public const AMR_INTERNAL_NOTE = 'internal-note';
    public const AMR_INTERNAL_NOTE_CSV = 'internal-note-csv';
    public const AMR_PAYMENT = 'payment';
    public const AMR_PAYMENT_CSV = 'payment-csv';
    public const AMR_REFERRERS = 'referrers';
    public const AMR_REFERRER_DETAILS = 'referrer-details';
    public const AMR_SPECIAL_TERMS = 'special-terms';
    public const AMR_SPECIAL_TERMS_CSV = 'special-terms-csv';
    public const AMR_TAX = 'tax';
    public const AMR_TAX_CSV = 'tax-csv';
    public const AMR_UNDER_BIDDERS = 'under-bidders';
    public const AMR_UNDER_BIDDERS_CSV = 'under-bidders-csv';

    public const AMR_ACTIONS = [
        self::AMR_AUCTIONS,
        self::AMR_AUCTIONS_CSV,
        self::AMR_AUDIT_TRAIL,
        self::AMR_AUDIT_TRAIL_CSV,
        self::AMR_BASIC,
        self::AMR_CONSIGNORS,
        self::AMR_CONSIGNORS_FILTER_CONSIGNOR_AUTOCOMPLETE,
        self::AMR_CONSIGNORS_PDF,
        self::AMR_CONSIGNORS_PDF_REPORT,
        self::AMR_CONSIGNORS_PRINT,
        self::AMR_CUSTOM_LOTS,
        self::AMR_CUSTOM_LOTS_CSV,
        self::AMR_CUSTOM_LOTS_PRINT,
        self::AMR_CUSTOM_LOTS_TEMPLATE,
        self::AMR_DOCUMENT_VIEWS,
        self::AMR_DOCVIEWS_CSV,
        self::AMR_IMPORT_SAMPLE,
        self::AMR_INDEX,
        self::AMR_INTERNAL_NOTE,
        self::AMR_INTERNAL_NOTE_CSV,
        self::AMR_PAYMENT,
        self::AMR_PAYMENT_CSV,
        self::AMR_REFERRERS,
        self::AMR_REFERRER_DETAILS,
        self::AMR_SPECIAL_TERMS,
        self::AMR_SPECIAL_TERMS_CSV,
        self::AMR_TAX,
        self::AMR_TAX_CSV,
        self::AMR_UNDER_BIDDERS,
        self::AMR_UNDER_BIDDERS_CSV,
    ];

    // Manage search

    public const AMSEA_AUCTION_LOTS = 'auction-lots';
    public const AMSEA_CONSIGNOR_USER_ACTIVE = 'consignor-user-active';
    public const AMSEA_CUSTOM_FIELDS = 'custom-fields';
    public const AMSEA_GET_AUCTIONS = 'get-auctions';
    public const AMSEA_INDEX = 'index';
    public const AMSEA_TIMEZONE = 'timezone';
    public const AMSEA_USERS_LIST = 'users-list';
    public const AMSEA_USER_ADDED_BY = 'user-added-by';

    public const AMSEA_ACTIONS = [
        self::AMSEA_AUCTION_LOTS,
        self::AMSEA_CONSIGNOR_USER_ACTIVE,
        self::AMSEA_CUSTOM_FIELDS,
        self::AMSEA_GET_AUCTIONS,
        self::AMSEA_INDEX,
        self::AMSEA_TIMEZONE,
        self::AMSEA_USERS_LIST,
        self::AMSEA_USER_ADDED_BY,
    ];

    // Manage site content
    public const AMSC_INDEX = self::DEFAULT_ACTION;

    public const AMSC_ACTIONS = [
        self::AMSC_INDEX
    ];

    //Manage consignor commission and fee
    public const AMCCF_INDEX = 'index';
    public const AMCCF_EDIT = 'edit';
    public const AMCCF_CREATE = 'create';

    public const AMCCF_ACTIONS = [
        self::AMCCF_INDEX,
        self::AMCCF_EDIT,
        self::AMCCF_CREATE,
    ];

    // Manage tax definition
    public const AMTD_CREATE = 'create';
    public const AMTD_EDIT = 'edit';
    public const AMTD_INDEX = self::DEFAULT_ACTION;
    public const AMTD_LIST = 'list';

    public const AMTD_ACTIONS = [
        self::AMTD_CREATE,
        self::AMTD_EDIT,
        self::AMTD_INDEX,
        self::AMTD_LIST,
    ];

    // Manage tax schema
    public const AMTS_CREATE = 'create';
    public const AMTS_EDIT = 'edit';
    public const AMTS_LIST = 'list';

    public const AMTS_ACTIONS = [
        self::AMTS_CREATE,
        self::AMTS_EDIT,
        self::AMTS_LIST,
    ];

    // Manage feed
    public const AMFEED_CREATE = 'create';
    public const AMFEED_EDIT = 'edit';
    public const AMFEED_INDEX = self::DEFAULT_ACTION;
    public const AMFEED_LIST = 'list';

    public const AMFEED_ACTIONS = [
        self::AMFEED_CREATE,
        self::AMFEED_EDIT,
        self::AMFEED_INDEX,
        self::AMFEED_LIST,
    ];

    // Manage coupon
    public const AMCOUPON_CREATE = 'create';
    public const AMCOUPON_EDIT = 'edit';
    public const AMCOUPON_INDEX = self::DEFAULT_ACTION;
    public const AMCOUPON_LIST = 'list';

    public const AMCOUPON_ACTIONS = [
        self::AMCOUPON_CREATE,
        self::AMCOUPON_EDIT,
        self::AMCOUPON_INDEX,
        self::AMCOUPON_LIST,
    ];

    // Manage buyer group
    public const AMBG_CREATE = 'create';
    public const AMBG_EDIT = 'edit';
    public const AMBG_INDEX = 'index';
    public const AMBG_LIST = 'list';
    public const AMBG_ADD_USER = 'add-user';

    public const AMBG_ACTIONS = [
        self::AMBG_CREATE,
        self::AMBG_EDIT,
        self::AMBG_INDEX,
        self::AMBG_LIST,
        self::AMBG_ADD_USER,
    ];

    // Manage bid increments
    public const AMBI_HYBRID_AUCTION = 'hybrid-auction';
    public const AMBI_LIVE_AUCTION = 'live-auction';
    public const AMBI_TIMED_AUCTION = 'timed-auction';
    public const AMBI_INDEX = self::DEFAULT_ACTION;

    public const AMBI_ACTIONS = [
        self::AMBI_INDEX,
        self::AMBI_HYBRID_AUCTION,
        self::AMBI_LIVE_AUCTION,
        self::AMBI_TIMED_AUCTION,
    ];

    // Manage settlements
    public const AMS_LIST_GENERATE_CONSIGNOR_AUTOCOMPLETE = 'list-generate-consignor-autocomplete';
    public const AMS_EXPORT = 'export';
    public const AMS_INDEX = 'index';
    public const AMS_LINE_ITEM_EXPORT = 'line-item-export';
    public const AMS_MULTIPLEPRINT = 'multipleprint';
    public const AMS_PRINT = 'print';
    public const AMS_VIEW = 'view';
    public const AMS_DELETE = 'delete';

    public const AMS_ACTIONS = [
        self::AMS_LIST_GENERATE_CONSIGNOR_AUTOCOMPLETE,
        self::AMS_EXPORT,
        self::AMS_INDEX,
        self::AMS_LINE_ITEM_EXPORT,
        self::AMS_MULTIPLEPRINT,
        self::AMS_PRINT,
        self::AMS_VIEW,
        self::AMS_DELETE,
    ];

    // Manage settlement check
    public const AMSCH_CREATE = 'create';
    public const AMSCH_CREATE_BATCH = 'create-batch';
    public const AMSCH_DELETE = 'delete';
    public const AMSCH_EDIT = 'edit';
    public const AMSCH_EXPORT_ALL = 'export-all';
    public const AMSCH_INDEX = self::DEFAULT_ACTION;
    public const AMSCH_LIST = 'list';
    public const AMSCH_LIST_ALL = 'list-all';
    public const AMSCH_LIST_SELECTED = 'list-selected';
    public const AMSCH_PAYEE_AUTOCOMPLETE = 'payee-autocomplete';
    public const AMSCH_PRINT = 'print';
    public const AMSCH_SETTLEMENT_AUTOCOMPLETE = 'settlement-autocomplete';

    public const AMSCH_ACTIONS = [
        self::AMSCH_CREATE,
        self::AMSCH_CREATE_BATCH,
        self::AMSCH_DELETE,
        self::AMSCH_EDIT,
        self::AMSCH_EXPORT_ALL,
        self::AMSCH_INDEX,
        self::AMSCH_LIST,
        self::AMSCH_LIST_ALL,
        self::AMSCH_LIST_SELECTED,
        self::AMSCH_PAYEE_AUTOCOMPLETE,
        self::AMSCH_PRINT,
        self::AMSCH_SETTLEMENT_AUTOCOMPLETE,
    ];

    // Manage sync
    public const AMSYNC_INDEX = self::DEFAULT_ACTION;
    public const AMSYNC_LIST = 'list';

    public const AMSYNC_ACTIONS = [
        self::AMSYNC_INDEX,
        self::AMSYNC_LIST,
    ];

    // Manage users

    public const AMU_CHANGE_RESELLER_CERT_STATUS = 'change-reseller-cert-status';
    public const AMU_CREATE = 'create';
    public const AMU_CSV_EXPORT = 'csv-export';
    public const AMU_DASHBOARD = 'dashboard';
    public const AMU_DELETE = 'delete';
    public const AMU_EDIT = 'edit';
    public const AMU_GENERATE_PASSWORD = 'generate-password';
    public const AMU_GET_USER_BY_NAME = 'get-user-by-name';
    public const AMU_IMPORT_SAMPLE = 'import-sample';
    public const AMU_INDEX = 'index';
    public const AMU_LOCATION_AUTOCOMPLETE = 'location-autocomplete';
    public const AMU_USER_ADDED_BY = 'user-added-by';
    public const AMU_VIEW = 'view';

    public const AMU_ACTIONS = [
        self::AMU_CHANGE_RESELLER_CERT_STATUS,
        self::AMU_CREATE,
        self::AMU_CSV_EXPORT,
        self::AMU_DASHBOARD,
        self::AMU_DELETE,
        self::AMU_EDIT,
        self::AMU_GENERATE_PASSWORD,
        self::AMU_GET_USER_BY_NAME,
        self::AMU_IMPORT_SAMPLE,
        self::AMU_INDEX,
        self::AMU_LOCATION_AUTOCOMPLETE,
        self::AMU_USER_ADDED_BY,
        self::AMU_VIEW,
    ];

    // Rtb increment

    public const ARTBINC_ADD = 'add';
    public const ARTBINC_DEL = 'del';
    public const ARTBINC_INDEX = 'index';
    public const ARTBINC_LOAD = 'load';

    public const ARTBINC_ACTIONS = [
        self::ARTBINC_ADD,
        self::ARTBINC_DEL,
        self::ARTBINC_INDEX,
        self::ARTBINC_LOAD,
    ];

    // Rtb message

    public const ARTBMES_ADD = 'add';
    public const ARTBMES_CENTER = 'center';
    public const ARTBMES_DEL = 'del';
    public const ARTBMES_INDEX = 'index';

    public const ARTBMES_ACTIONS = [
        self::ARTBMES_ADD,
        self::ARTBMES_CENTER,
        self::ARTBMES_DEL,
        self::ARTBMES_INDEX,
    ];
}
