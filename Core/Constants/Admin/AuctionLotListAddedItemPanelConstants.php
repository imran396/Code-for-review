<?php
/**
 * SAM-6780: Move sections' logic to separate Panel classes at Manage auction lots page (/admin/manage-auctions/lots/id/%s)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-04, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Core\Constants\Admin;


/**
 * Class AuctionLotListAddedItemPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class AuctionLotListAddedItemPanelConstants
{
    public const CID_AUCTION_LOT_PAGINATOR_ID = 'alDtgPaginator';
    public const CID_AUCTION_LOT_PER_PAGE_SELECTOR_ID = 'alPerPageSelector';
    public const CID_BLK_AL_BOTTOM_QUICK_EDIT_CONTROLS = 'albottom';
    public const CID_BLK_AL_TOP_QUICK_EDIT_CONTROLS = 'altop';
    public const CID_BLK_AUCTION_LIST_CONTAINER = 'auction-list-container';
    public const CID_BLK_DIV_TPL = 'div_%s';
    public const CID_BLK_LOT_IMAGE_TPL = 'lot_image%s';
    public const CID_BLK_LOT_TPL = 'lot%s';
    public const CID_BLK_STAGGERED_CLOSING = 'div_staggered_closing';
    public const CID_BLK_TOTALS = 'div_totals';
    public const CID_BTN_ADDED_ITEMS = 'section_items1';
    public const CID_BTN_ADD_TO_BULK = 'alf97';
    public const CID_BTN_APPLY_DATE_TIME = 'alf50';
    public const CID_BTN_ASSIGNED_LOT_DELETE_TPL = 'alfdelli%s';
    public const CID_BTN_ASSIGNED_LOT_RESET_VIEW_TPL = 'alfresviewli%s';
    public const CID_BTN_CHANGE_STATUS = 'alf150';
    public const CID_BTN_CLONE = 'alf103';
    public const CID_BTN_DELETE_LOT_IMAGES = 'alf121';
    public const CID_BTN_GO_TO_LOT = 'alf111';
    public const CID_BTN_LOT_ITEM_NUM = 'alf113';
    public const CID_BTN_MOVE = 'alf12';
    public const CID_BTN_QUICK_CANCEL_AL_BOTTOM = 'alf100qc';
    public const CID_BTN_QUICK_CANCEL_AL_TOP = 'alf99qc';
    public const CID_BTN_QUICK_EDIT_AL_BOTTOM = 'alf100qe';
    public const CID_BTN_QUICK_EDIT_AL_TOP = 'alf99qe';
    public const CID_BTN_QUICK_EDIT_LOT = 'alf64';
    public const CID_BTN_QUICK_SAVE_AL_BOTTOM = 'alf100qs';
    public const CID_BTN_QUICK_SAVE_AL_TOP = 'alf99qs';
    public const CID_BTN_REMOVE = 'alf32';
    public const CID_BTN_RESET_ADDED = 'al120';
    public const CID_BTN_RE_CACHE_IMAGES = 'alf62';
    public const CID_BTN_SEARCH_ADDED = 'al119';
    public const CID_BTN_SECTION_SEARCH_FILTER = 'section_search1';
    public const CID_CAL_ASSIGNED_LOT_DATE_END_DATE_TPL = 'alfqeed%s';
    public const CID_CAL_START_CLOSING_DATE = 'alf45';
    public const CID_CAL_START_DATE = 'alf40';
    public const CID_CHK_ASSIGNED_LOT_INTERNET_BID_TPL = 'alfqeib%s';
    public const CID_CHK_CHOOSE_ALL = 'alf9';
    public const CID_CHK_CHOOSE_ITEM_TPL = 'addedChooseLotChk%s';
    public const CID_CUSTOM_FIELD_GROUP_FOR_ADDED_LOTS = 'alf87';
    public const CID_DLG_CONFIRM_ADD_LOT_TO_BULK = 'alf98';
    public const CID_DLG_CONFIRM_CHANGE_LOT_STATUS = 'alf151';
    public const CID_DLG_CONFIRM_MOVE_BID_ALONG_LOT = 'alf86';
    public const CID_DLG_PLACE_BID = 'dialog-admin-auctionlotslist-placebid';
    public const CID_DTG_AUCTION_LOT_ITEMS = 'alf5';
    public const CID_DLG_CONFIRM_AUCTION_LOT_DATETIME = 'alf37';
    public const CID_FOCUSED_ROW_TPL = 'alf5row%s';
    public const CID_HID_ASSIGNED_LOT_CONSIGNOR = 'alf94';
    public const CID_HID_ASSIGNED_LOT_WINNING_BIDDER_TPL = 'alfqewh%s';
    public const CID_HID_ASSIGNED_LOT_WINNING_BIDDER_PREFIX = 'alfqewh';
    public const CID_HID_AUCTION_LOT_CONSIGNOR_TPL = 'alfqech%s';
    public const CID_HID_AUCTION_LOT_CONSIGNOR_PREFIX = 'alfqech';
    public const CID_HID_AUCTION_MOVE = 'alf10';
    public const CID_HID_LOT_IMAGE_ORDER_POSITIONS = 'alf101';
    public const CID_ICO_WAIT_ACTION = 'alf4';
    public const CID_ICO_WAIT_AUCTION_LOT_ACTION = 'alf96';
    public const CID_IMAGES_FOR_LOT_TPL = 'images_for_lot%s';
    public const CID_LBL_ABS_TOTAL = 'afl69';
    public const CID_LBL_AUCTION_LOT_REPORT = 'alf28';
    public const CID_LBL_AUCTION_WARNING = 'afl73';
    public const CID_LBL_CHOOSE_ALL = 'alf8';
    public const CID_LBL_CONSIGNOR_SCHEDULE_LINK = 'alf82';
    public const CID_LBL_CURRENT_TOTAL = 'afl70';
    public const CID_LBL_DATE_GENERAL_ERROR = 'alf54';
    public const CID_LBL_ERR_DATE = 'alf52';
    public const CID_LBL_ERR_DATE2 = 'alf53';
    public const CID_LBL_MAX_TOTAL = 'afl71';
    public const CID_LBL_MOVE = 'alf11';
    public const CID_LBL_QUICK_EDIT_AL_BOTTOM = 'alf100';
    public const CID_LBL_QUICK_EDIT_AL_TOP = 'alf99';
    public const CID_LBL_RESERVE_TOTAL = 'afl72';
    public const CID_LST_ASSIGNED_LOT_BIDDING_USER_FILTER = 'alf67';
    public const CID_LST_ASSIGNED_LOT_CATEGORY_TPL = 'alfqeca%s';
    public const CID_LST_ASSIGNED_LOT_CATEGORY_TPL_WARNING = 'alfqecaw%s';
    public const CID_LST_AUCTION_LOT_BID_COUNT_FILTER = 'alf124';
    public const CID_LST_AUCTION_LOT_CATEGORY_FILTER = 'alf102';
    public const CID_LST_AUCTION_LOT_RESERVE_MEET_FILTER = 'alf125';
    public const CID_LST_AUCTION_LOT_STATUS_FILTER = 'alf107';
    public const CID_LST_AUCTION_LOT_STATUS_TPL = 'alfqest%s';
    public const CID_LST_KEYWORD_OPTION = 'alf181';
    public const CID_LST_STAGGER_CLOSING = 'alf56';
    public const CID_LST_START_CLOSING_HOUR = 'alf46';
    public const CID_LST_START_CLOSING_MERIDIEM = 'alf48';
    public const CID_LST_START_CLOSING_MINUTE = 'alf47';
    public const CID_LST_START_HOUR = 'alf41';
    public const CID_LST_START_MERIDIEM = 'alf43';
    public const CID_LST_START_MINUTE = 'alf42';
    public const CID_LST_WINNING_BIDDER_FILTER = 'alf65';
    public const CID_MIXED_CONTROL_NEEDLE = 'alfqe';
    public const CID_TXT_ASSIGNED_BID_HAMMER_PRICE_TPL = 'alfqehp%s';
    public const CID_TXT_ASSIGNED_GROUP_ID_TPL = 'alfqepi%s';
    public const CID_TXT_ASSIGNED_ITEM_NO_CONCATENATED_TPL = 'alfqeifn%s';
    public const CID_TXT_ASSIGNED_ITEM_NO_EXTENSION_TPL = 'alfqeinx%s';
    public const CID_TXT_ASSIGNED_ITEM_NO_TPL = 'alfqein%s';
    public const CID_TXT_ASSIGNED_LOT_CONSIGNOR_FILTER = 'alf93';
    public const CID_TXT_ASSIGNED_LOT_DATE_END_TIMEZONE_TPL = 'alfqeet%s';
    public const CID_TXT_ASSIGNED_LOT_HIGH_ESTIMATE_TPL = 'alfqehe%s';
    public const CID_TXT_ASSIGNED_LOT_LOW_ESTIMATE_TPL = 'alfqele%s';
    public const CID_TXT_ASSIGNED_LOT_NAME_TPL = 'alfqena%s';
    public const CID_TXT_ASSIGNED_LOT_NO_EXTENSION_TPL = 'alfqelne%s';
    public const CID_TXT_ASSIGNED_LOT_NO_PREFIX_TPL = 'alfqelnp%s';
    public const CID_TXT_ASSIGNED_LOT_NO_TPL = 'alfqelfnu%s';
    public const CID_TXT_ASSIGNED_LOT_NUMBER_TPL = 'alfqelnu%s';
    public const CID_TXT_ASSIGNED_LOT_QUANTITY_TPL = 'alfqeqt%s';
    public const CID_TXT_ASSIGNED_LOT_RESERVE_PRICE_TPL = 'alfqerp%s';
    public const CID_TXT_ASSIGNED_LOT_STARTING_BID_TPL = 'alfqesb%s';
    public const CID_TXT_ASSIGNED_LOT_VIEW_COUNT_TPL = 'alfqevi%s';
    public const CID_TXT_ASSIGNED_LOT_WINNING_BIDDER_TPL = 'alfqewt%s';
    public const CID_TXT_ASSIGNED_LOT_WINNING_BIDDER_PREFIX = 'alfqewt';
    public const CID_TXT_AUCTION_LOT_CONSIGNOR_TPL = 'alfqect%s';
    public const CID_TXT_AUCTION_LOT_CONSIGNOR_PREFIX = 'alfqect';
    public const CID_TXT_AUCTION_LOT_CUSTOM_INTEGER_TPL = 'alfqecf%sid%s';
    public const CID_TXT_GO_TO_LOT = 'alf110';
    public const CID_TXT_KEYWORD = 'alf180';
    public const CID_TXT_LOT_INTERVAL = 'alf58';
    public const CID_TXT_LOT_ITEM_NUM = 'alf112';
    public const CID_TXT_QUICK_EDIT_LOT_EXT = 'alf85';
    public const CID_TXT_QUICK_EDIT_LOT_FULL_NUM = 'alf115';
    public const CID_TXT_QUICK_EDIT_LOT_NUM = 'alf63';
    public const CID_TXT_QUICK_EDIT_LOT_PREFIX = 'alf84';
    public const CID_TXT_TIMEZONE = 'alf44';
    public const DTG_AUCTION_LOT_ITEMS_URL_QUERY_PREFIX = 'al';


    // Css classes for data grid columns at Added lots
    public const CSS_CLASS_DTG_LOTS_COL_ABS = 'ali-current-abs';
    public const CSS_CLASS_DTG_LOTS_COL_ACTIONS = 'li-actions';
    public const CSS_CLASS_DTG_LOTS_COL_CONSIGNOR_COMPANY = 'li-consignor-company';
    public const CSS_CLASS_DTG_LOTS_COL_CONSIGNOR_EMAIL = 'li-consignor-email';
    public const CSS_CLASS_DTG_LOTS_COL_CURRENT_BID = 'ali-current-bid';
    public const CSS_CLASS_DTG_LOTS_COL_GROUP = 'li-group';
    public const CSS_CLASS_DTG_LOTS_COL_HIGH_BIDDER = 'ali-current-high-bidder';
    public const CSS_CLASS_DTG_LOTS_COL_HIGH_BIDDER_COMPANY = 'ali-current-high-bidder-company';
    public const CSS_CLASS_DTG_LOTS_COL_HIGH_BIDDER_EMAIL = 'ali-current-high-bidder-email';
    public const CSS_CLASS_DTG_LOTS_COL_INTERNET_BID = 'li-internet-bid';
    public const CSS_CLASS_DTG_LOTS_COL_LAST_TIME_BID = 'li-last-time-bid';
    public const CSS_CLASS_DTG_LOTS_COL_LOT = 'li-lot-num';
    public const CSS_CLASS_DTG_LOTS_COL_MIN_MAX = 'ali-current-min-max';
    public const CSS_CLASS_DTG_LOTS_COL_NUMBER_OF_DIBS = 'ali-bid-count';
    public const CSS_CLASS_DTG_LOTS_COL_PLACEBID = 'li-placebid';
    public const CSS_CLASS_DTG_LOTS_COL_QTY = 'li-qty';
    public const CSS_CLASS_DTG_LOTS_COL_RESERVE = 'li-reserve';
    public const CSS_CLASS_DTG_LOTS_COL_STATUS = 'ali-status';
    public const CSS_CLASS_DTG_LOTS_COL_TIME_END = 'li-time-end';
    public const CSS_CLASS_DTG_LOTS_COL_TIME_LEFT = 'li-time-left';
    public const CSS_CLASS_DTG_LOTS_COL_VIEWS = 'ali-views';
    public const CSS_CLASS_DTG_LOTS_COL_WINNER_COMPANY = 'li-winner-company';
    public const CSS_CLASS_DTG_LOTS_COL_WINNER_EMAIL = 'li-winner-email';

    // General css classes
    public const CSS_CLASS_DTG_LOTS_CHK_CHOOSE_LOT = 'auc-lot';

    public const CLASS_BTN_ADMIN_LOT_PLACE_BID = 'admin_lot_place_bid_button';
    public const CLASS_BTN_CANCEL_BUTTON = 'cancel-button';
    public const CLASS_BLK_AUC_LOT_TPL = 'auc_lot%s';
    public const CLASS_BLK_LOT_IMAGE_DIV = 'lot-image-div';
    public const CLASS_BLK_LOT_IMAGE_SORTABLE = 'lotImageSortable';
    public const CLASS_BLK_PLACE_BID = 'place-bid';
    public const CLASS_BLK_PLACE_BID_INPUT = 'place-bid-input';
    public const CLASS_BLK_PLACE_NEXT_BID_BUTTON = 'place-next-bid-button';
    public const CLASS_BLK_STAY_ON_PLACE = 'stay-on-place';
    public const CLASS_BLK_BIDDER_COMPANY = 'bidder-company';
    public const CLASS_BLK_BIDDER_FIRSTNAME = 'bidder-firstname';
    public const CLASS_BLK_BIDDER_LASTNAME = 'bidder-lastname';
    public const CLASS_BLK_BIDDER_NUM = 'bidder-num';
    public const CLASS_BLK_BIDDER_USERNAME = 'bidder-username';
    public const CLASS_BLK_CURRENT_BID = 'current-bid';
    public const CLASS_BLK_CURRENT_HOUSE = 'current-house';
    public const CLASS_BLK_LABEL = 'label';
    public const CLASS_BLK_LOT_NAME = 'lot-name';
    public const CLASS_BLK_LOT_NUM = 'lot-num';
    public const CLASS_BLK_MAX_BID_VALUE = 'max-bid-value';
    public const CLASS_BLK_MAX_GT_CURRENT = 'max-gt-current';
    public const CLASS_BLK_RESERVE = 'reserve';
    public const CLASS_BLK_RESERVE_MESSAGE = 'reserve-message';
    public const CLASS_BLK_RESERVE_MET = 'reserve-met';
    public const CLASS_BLK_STATUS = 'status';
    public const CLASS_BLK_TD_LOT_ITEM_RESERVE = 'td-lot-item-reserve';
    public const CLASS_BLK_TIMER = 'timer';
    public const CLASS_BLK_TIME_LEFT_URGENT = 'time-left-urgent';
    public const CLASS_BLK_VALUE = 'value';
    public const CLASS_BLK_WINNING_STATUS = 'winning-status';
}
