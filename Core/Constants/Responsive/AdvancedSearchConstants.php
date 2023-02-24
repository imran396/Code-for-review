<?php
/**
 * SAM-4696: Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @author        Vahagn Hovsepyan
 * @since         May 29, 2019
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Core\Constants\Responsive;

/**
 * Class AdvancedSearchConstants
 */
class AdvancedSearchConstants
{
    public const CIF_ICON = 'icon';
    public const CIF_NONE = 'none';
    /** @var string[] */
    public static array $compactImageFormats = [self::CIF_ICON, self::CIF_NONE];

    public const ESQ_NO_QUERY = 'no-query';
    public const ESQ_DEFAULT = 'default';
    /** @var string[] */
    public static array $emptySearchQueries = [self::ESQ_NO_QUERY, self::ESQ_DEFAULT];

    public const ESQPS_CLOSED = 'closed';
    public const ESQPS_OPEN = 'open';
    public const ESQPS_OPEN_FORM = 'open-form';
    /** @var string[] */
    public static array $emptySearchQueryPanelStates = [self::ESQPS_CLOSED, self::ESQPS_OPEN, self::ESQPS_OPEN_FORM];

    public const CID_DTR_LOT_ITEMS = 'lac5';
    public const CID_PNL_SEARCH = 'ads01';
    public const CID_LBL_MY_LOT_ITEM_SUMMARY = 'totalAmount';
    public const CID_LBL_BELOW_RESERVE = 'oai23';
    public const CID_BTN_PLACE_MULTI_BIDS = 'lac24';
    public const CID_DLG_PLACE_BID_LIVE_ON_LINE_ITEM = 'lac29';
    public const CID_BTN_VERIFIED_TPL = 'btnV%s';
    public const CID_BTN_NEXT_BID_TPL = 'bNext%s';
    public const CID_BTN_WAIT_TPL = 'nWait%s';
    public const CID_BTN_PLACE_BID_TPL = 'bPbid%s';
    public const CID_BTN_FORCE_BID_TPL = 'bFbid%s';
    public const CID_TXT_FORCE_BID_TPL = 'tfbid%s';
    public const CID_HID_VISIBLE_ASKING_BID_TPL = 'hidVisAskBid%s';
    public const CID_TXT_MAX_BID_TPL = 'tmbid%s';
    public const CID_TXT_OR_BID_TPL = 'tobid%s';
    public const CID_BTN_BUY_NOW_TPL = 'bBnow%s';
    public const CID_ICO_WAIT_TPL = '%soWait%s';
    public const CID_BTN_DELETE_TPL = '%sbDelete%s';
    public const CID_BIDDING_CELL_TPL = '%sali%s';
    public const CID_CHK_ON_LOT_TPL = '%scLot%s';
    public const CID_CHK_WATCHLIST_TPL = 'W%s';
    public const CID_CHK_AUCTION_LOT_ITEM_TPL = 'A%sLi%s';
    public const CID_LBL_RESERVE_MET_TPL = 'reserve-met-%s';
    public const CID_LBL_RESERVE_NOT_MET_TPL = 'reserve-not-met-%s';
    public const CID_LBL_BIDDING_STATUS_TPL = 'yh%s';
    public const CID_BLK_REGULAR_BID_TPL = 'blkRegularBid%s';
    public const CID_BLK_FORCE_BID_TPL = 'blkForceBid%s';
    public const CID_LNK_BUY_NOW_TPL = 'buy-now%s';
    public const CID_LBL_PRICE_INFO_TPL = 'scur%s';
    public const CID_BTN_LOGIN_TPL = 'bLogin%s';
    public const CID_BTN_LOGIN_BUY_NOW_TPL = 'bBuyNowLogin%s';
    public const CID_BTN_AUCTION_REGISTRATION_TPL = 'bAucReg%s';
    public const CID_BTN_BUY_NOW_REGISTER_TPL = 'bBuyNowRegister%s';
    public const CID_BTN_VIEW_DETAILS_TPL = 'bViewDet%s';
    public const CID_BLK_AUCTION_LOT_ITEM_TPL = 'ali%s';
    public const CID_BLK_ABSENTEE_TPL = 'ABSENTEE%s';
    public const CID_BLK_TOP_PAGINATOR = 'toppaginator';
    public const CID_BLK_MY_ITEMS_MOBILE_MENU = 'my-items-mobile-menu';
    public const CID_BLK_MY_ITEMS_LIST_NAV = 'my-items-listnav';
    public const CID_BLK_TAB_NAV = 'tabnav';
    public const CID_BLK_WRAPPER = 'wrapper';
    public const CID_LST_PAGE_TOP = 'lstPageTop';
    public const CID_LST_PAGE_BOTTOM = 'lstPageBottom';
    public const CID_BID_CELL_TPL = 'bidcell%s';
    public const CID_BLK_AUC_LOT_TPL = 'auc-lot%s';
    public const CID_BLK_AUC_LOT_TIME_TPL = 'auc-lot-time%s';
    public const CID_BLK_CONSOLE = 'console';
    public const CID_BLK_PAGINATOR_TOP = 'blkPaginatorTop';
    public const CID_BLK_PAGINATOR_BOTTOM = 'blkPaginatorBottom';
    public const CID_BLK_LOT_ITEM_MAIN_TPL = 'blkLotItemMain%s';

    /** @var string[] */
    public static array $compactImageFormatsNames = [
        self::CIF_ICON => 'icon',
        self::CIF_NONE => 'none'
    ];

    /** @var string[] */
    public static array $emptySearchQueriesNames = [
        self::ESQ_NO_QUERY => 'no-query',
        self::ESQ_DEFAULT => 'default'
    ];

    /** @var string[] */
    public static array $emptySearchQueryPanelStatesNames = [
        self::ESQPS_CLOSED => 'closed',
        self::ESQPS_OPEN => 'open',
        self::ESQPS_OPEN_FORM => 'open-form'
    ];

    public const CLASS_BTN_BUY_NOW_WITH_QTY = 'buy-now-with-qty';
    public const CLASS_BTN_SEARCH_TOGGLE = 'search_toggle_btn';
    public const CLASS_BLK_ACTIVE = 'active';
    public const CLASS_BLK_AUCTION_BID = 'aucbid';
    public const CLASS_BLK_AUCTION_LIST = 'auclist';
    public const CLASS_BLK_AUCTION_LIST_BTN = 'auclistbtn';
    public const CLASS_BLK_BDINFO_TPL = 'bdinfo-%s';
    public const CLASS_BLK_BD_CHK = 'bd-chk';
    public const CLASS_BLK_BD_INFO_TPL = 'bd-info-%s';
    public const CLASS_BLK_BID_ACTION = 'bid_action';
    public const CLASS_BLK_BID_POP = 'bid_pop';
    public const CLASS_BLK_COMPACT_CONTAINER = 'compact_container';
    public const CLASS_BLK_CURRENT_BID = 'curr_bid';
    public const CLASS_BLK_CURRENT_BID_WRAP = 'curr_bid_wrap';
    public const CLASS_BLK_CURRENT_REG_BID = 'curr_reg_bid';
    public const CLASS_BLK_ENDED = 'ended';
    public const CLASS_BLK_GRID_BIDS = 'grid_bids';
    public const CLASS_BLK_IMAGE_OVERLAY = 'image_overlay';
    public const CLASS_BLK_IS_OPEN = 'is-open';
    public const CLASS_BLK_ITEM_BID_HISTORY = 'item-bidhistory';
    public const CLASS_BLK_ITEM_STARTING_BID = 'item-starting-bid';
    public const CLASS_BLK_ITEM_STATUS = 'item-status';
    public const CLASS_BLK_FIGURE_COL = 'figure-col';
    public const CLASS_BLK_FLATPICKR_CALENDAR = 'flatpickr-calendar';
    public const CLASS_BLK_FORCE_BID_WARNING = 'farce-bid-warning';
    public const CLASS_BLK_SELECTED = 'selected';
    public const CLASS_BLK_SELECTOR = 'selector';
    public const CLASS_BLK_OFF_INCREMENT = 'off-increment';
    public const CLASS_BLK_OUTBID = 'outbid';
    public const CLASS_BLK_LABEL = 'label';
    public const CLASS_BLK_LABEL_VALUE = 'label-value';
    public const CLASS_BLK_LABEL_VALUE_ASK = 'label-value-ask';
    public const CLASS_BLK_LABEL_VALUE_BULK_ASK = 'label-value-bulk-ask';
    public const CLASS_BLK_LABEL_VALUE_CURRENT = 'label-value-curr';
    public const CLASS_BLK_LABEL_VALUE_HP = 'label-value-hp';
    public const CLASS_BLK_LISTING_SINGLE = 'listing_single';
    public const CLASS_BLK_LOT_IMAGE_HOVER = 'lot-image-hover';
    public const CLASS_BLK_MAX_BID_WARNING = 'max-bid-warning';
    public const CLASS_BLK_MY_ITEMS_MOBILE_HAMBURGER_MENU = 'my-items-mobile-hamburger-menu';
    public const CLASS_BLK_ONE_ROW = 'onerow';
    public const CLASS_BLK_ONE_ROW_BOLD = 'onerow-bold';
    public const CLASS_BLK_OPEN_IMAGE = 'open_image';
    public const CLASS_BLK_QFORM_CONTROL_MOBILE_CHECKBOX_CTL = 'sam_qform_control_mobilecheckbox-ctl';
    public const CLASS_BLK_RESERVE_MET = 'reserve-met';
    public const CLASS_BLK_RESERVE_NOT_MET = 'reserve-not-met';
    public const CLASS_BLK_SEARCH_BTN = 'search_btn';
    public const CLASS_BLK_SHOW = 'show';
    public const CLASS_BLK_TITLE = 'title';
    public const CLASS_BLK_UI_ICON = 'ui-icon';
    public const CLASS_BLK_UI_MENU_ITEM_WRAPPER = 'ui-menu-item-wrapper';
    public const CLASS_BLK_UNI_BTN = 'unibtn';
    public const CLASS_BLK_VALUE = 'value';
    public const CLASS_BLK_YOUARE_WINNNIG = 'youre-winning';
    public const CLASS_BLK_WARNING = 'warning';
    public const CLASS_BLK_WINNING = 'winning';
    public const CLASS_LNK_BUTTON = 'button';
    public const CLASS_LNK_CLOSE_ACTION = 'close_action';
    public const CLASS_LNK_ITEM_CURRENT_BID = 'item-currentbid';
    public const CLASS_LNK_ORNG = 'orng';
    public const CLASS_LST_ITEM_ABSENTEE_BIDS = 'item-absentee-bids';
    public const CLASS_LST_ITEM_ASKING_BID = 'item-askingbid';
    public const CLASS_LST_ITEM_BULK_PIECEMEAL = 'item-bulk-piecemeal';
    public const CLASS_LST_ITEM_MAX_BID = 'item-maxbid';
    public const CLASS_LST_ITEM_STATUS = 'item-status';
    public const CLASS_LST_ITEM_WIN_BID = 'item-win-bid';
    public const CLASS_LST_PAGE_SEL_MOBILE = 'pagesel-mobile';
    public const CLASS_LST_PRICE_INFO = 'price-info';
    public const CLASS_SEC_COMPACT_ADVANCED_SEARCH = 'compact_advance_search';
}
