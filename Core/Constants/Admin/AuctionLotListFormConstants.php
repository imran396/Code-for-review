<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/13/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class AuctionLotListFormConstants
 */
class AuctionLotListFormConstants
{
    public const CID_BLK_MORE_ACTIONS_EXPAND = 'more-actions-expand';
    public const CID_BLK_MORE_ACTIONS_LABEL = 'more-actions-label';
    public const CID_BTN_CACHE_IMAGES = 'alf51';
    public const CID_BTN_CREATE_PDF = 'alf38';
    public const CID_BTN_DELETE_ALL_IMAGES = 'alf114';
    public const CID_BTN_QUICK_ADD = 'alf3';
    public const CID_BTN_QUICK_IMPORT_TPL = 'section_qim%s';
    public const CID_BTN_RANDOMIZE = 'alf73';
    public const CID_BTN_REALIZED_PRICES_PDF = 'alf90';
    public const CID_BTN_REASSIGN_LOT = 'alf89';
    public const CID_BTN_REFRESH_CACHE = 'alf182';
    public const CID_BTN_REMOTE_IMAGE_IMPORT = 'section_reming';
    public const CID_BTN_REORDER_LOTS = 'alf83';
    public const CID_BTN_RESET_ALL_VIEWS = 'alf123';
    public const CID_BTN_SEND_SMS = 'alf81';
    public const CID_BTN_SORT_BY_CONSIGNOR = 'alf61';
    public const CID_BTN_UNASSIGNED_LOT_SECTION = 'sectionItems2';
    public const CID_BTN_UPLOAD = 'alf27';
    public const CID_BTN_WAVE_BID_POST = 'alfWavebidPost';
    public const CID_DLG_CONFIRM_AUCTION_LOT_DATETIME = 'alf57';
    public const CID_DLG_CONFIRM_CLONE_LOT_FIELDS = 'alf104';
    public const CID_ICO_WAIT_QUICK_ADD = 'alf2';
    public const CID_ICO_WAIT_UPLOAD = 'alf26';
    public const CID_ICO_WAIT_UPLOAD_TIMED = 'alf34';
    public const CID_LNK_DELETE_REALIZED_PRICES_PDF = 'alf173';
    public const CID_LNK_PDF_CATALOG_DELETE = 'alf174';
    public const CID_PNL_AUCTION_LOT_LIST_ADDED_ITEM = 'alfPnlAuctionLotListAddedItem';
    public const CID_PNL_AUCTION_LOT_LIST_AVAILABLE_ITEM = 'alfpnlAuctionLotListAvailableItem';
    public const CID_PNL_AUCTION_LOT_LIST_POST_AUCTION_IMPORT = 'alfPnlAuctionLotListPostAuctionImport';
    public const CID_PNL_LOT_IMPORT_LIVE = 'alf25';
    public const CID_PNL_LOT_IMPORT_TIMED = 'alf33';
    public const CID_PNL_REMOTE_IMAGE_IMPORT = 'alf79';
    public const CID_AUCTION_LOT_LIST_FORM = 'AuctionLotListForm';


    // Css classes for data grid columns at Added and Available lots
    public const CSS_CLASS_DTG_LOTS_COL_CATEGORY = 'li-cat';
    public const CSS_CLASS_DTG_LOTS_COL_CONSIGNOR = 'li-consignor';
    public const CSS_CLASS_DTG_LOTS_COL_CREATOR = 'li-creator';
    public const CSS_CLASS_DTG_LOTS_COL_CUSTOM_FIELDS_PREFIX = 'li-cf-';
    public const CSS_CLASS_DTG_LOTS_COL_EST = 'li-est';
    public const CSS_CLASS_DTG_LOTS_COL_ID = 'li-item-num';
    public const CSS_CLASS_DTG_LOTS_COL_IMG = 'li-img';
    public const CSS_CLASS_DTG_LOTS_COL_NAME = 'li-name';
    public const CSS_CLASS_DTG_LOTS_COL_PRICE = 'li-hprice';
    public const CSS_CLASS_DTG_LOTS_COL_SELECT = 'li-select';
    public const CSS_CLASS_DTG_LOTS_COL_START = 'li-start';
    public const CSS_CLASS_DTG_LOTS_COL_WINNER = 'li-winner';
    public const CSS_CLASS_SYNC = 'sync';

    public const CLASS_BTN_ADD_LINK = 'addlink';
    public const CLASS_BLK_ALERT_ERROR = 'alert-error';
    public const CLASS_BLK_ALERT_INFO = 'alert-info';
    public const CLASS_BLK_AUCTION_LOT_IMPORT = 'auction-lot-import';
    public const CLASS_BLK_AUCTION_LOT_IMPORT_2 = 'auction-lot-import2';
    public const CLASS_BLK_AUCTION_LOT_IMPORT_3 = 'auction-lot-import3';
    public const CLASS_BLK_AUCTION_LOT_IMPORT_4 = 'auction-lot-import4';
    public const CLASS_BLK_ERROR = 'error';
    public const CLASS_BLK_EXTENDABLE = 'extendable';
    public const CLASS_BLK_DATA_GRID = 'datagrid';
    public const CLASS_BLK_Q_DATE_TIME_PICKER_CTL = 'qdatetimepicker-ctl';
    public const CLASS_BLK_SEARCH_OPTIONS = 'search-options';
    public const CLASS_BLK_SEARCH_OPTIONS_2 = 'search-options2';
    public const CLASS_BLK_TOT_CURR = 'totCurr';
    public const CLASS_BLK_TOT_HIGH_EST = 'totHighEst';
    public const CLASS_BLK_TOT_LOW_EST = 'totLowEst';
    public const CLASS_BLK_TOT_MAX_BID = 'totMaxBid';
    public const CLASS_BLK_TOT_RES = 'totRes';
    public const CLASS_BLK_TOT_STR_BID = 'totStrBid';
    public const CLASS_BLK_TOT_VIEW = 'totView';
    public const CLASS_BLK_TOT_WIN_BID = 'totWinBid';
    public const CLASS_BLK_TOT_WIN_BID_INTER = 'totWinBidInter';
    public const CLASS_LNK_MORE_ACTIONS = 'more-actions-a';
    public const CLASS_PNL_NEW_FS = 'newfs';

    // Place bid dialog elements selectors
    // We use them at JS.
    // Implemented at dev@42822. (SAM-6481: Add bidding options on the admin - auction - lots - added lots table - reorganize code to use consts)
    public const CTN_PLACE_BID = '.place-bid';
    public const TXT_PLACE_BID_INPUT = '.place-bid-input';
    public const BTN_PLACE_BID = '.place-bid-button';
    public const CTN_PLACE_NEXT_BID = '.place-next-bid-button';

    public const SEARCH_INTO_ADDED = 1;
    public const SEARCH_INTO_AVAILABLE = 2;

    //Reverse tag will be used for reverse translation
    public const REVERSE_SUFFIX = ".reverse";
}
