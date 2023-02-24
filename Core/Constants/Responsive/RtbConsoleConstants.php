<?php
/**
 * Constants file for public side consoles:
 * Live/Hybrid viewer and bidder consoles
 * Projector screen at admin side is based on viewer console
 *
 * SAM-5200: Apply page dependent constants in rtb console code
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           03.07.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\Core\Constants\Responsive;

/**
 * Class RtbConsoleConstants
 * @package Sam\Core\Constants\Responsive
 */
class RtbConsoleConstants
{
    public const CID_LBL_LOT_COUNT = 'lblLotCount';
    public const CID_LNK_ITEMS_WON = 'lnkItemsWon';
    public const CID_LBL_LOT_NO = 'lblLotNo';
    public const CID_LBL_LOT_NAME = 'lblLotName';
    public const CID_LBL_LOT_CATEGORY = 'lblLotCat';
    public const CID_LBL_LOT_IMAGE = 'lblLotImg';
    public const CID_LBL_LOT_IMAGE_BIG = 'lblLotImgBig'; // used at projector screen (projector is based on viewer console)
    public const CID_LBL_LOT_IMAGE_BIG_THUMB = 'lblLotImgsBig'; // used at projector screen (projector is based on viewer console)
    public const CID_LBL_LOW_ESTIMATE = 'lblLowEst';
    public const CID_LBL_HIGH_ESTIMATE = 'lblHighEst';
    public const CID_LBL_CURRENT_BID = 'lblCurrent';
    public const CID_LBL_ASKING_BID = 'lblAsking';
    public const CID_LBL_RESERVE_MET = 'lblResMet';
    public const CID_LBL_LOT_DESCRIPTION = 'lblLotDesc';
    public const CID_BTN_PLACE_BID = 'btnPlaceBid';

    public const CID_LBL_CATALOG = 'lblUpcoming'; // ?
    public const CID_CHK_CATALOG_FOLLOW = 'chkFollow';
    public const CID_RAD_CATALOG_UPCOMING = 'radUpcoming';
    public const CID_RAD_CATALOG_PAST = 'radPast';
    public const CID_LST_CATALOG_FILTER_STATUS = 'lstShow';

    public const CID_BTN_GROUP_ADD = 'btnAddConf';
    public const CID_BTN_GROUP_CANCEL = 'btnCanConf';
    public const CID_LST_GROUP_QTY = 'lstQtyConf';
    public const CID_BNT_GROUP_OK = 'btnOkConf';
    public const CID_LBL_GROUP_LOTS = 'lblLotsConf';
    public const CID_LBL_GROUP_LOT_PREVIEW = 'lblLotPreview';
    public const CID_LBL_GROUP_PRICE = 'lblPriceConf';

    public const CID_LBL_MESSAGE = 'lblMessage';
    public const CID_TXT_MESSAGE = 'txtMessage';
    public const CID_BTN_SEND_MESSAGE = 'btnSendMessage';

    public const CID_LBL_LOT_CHANGES = 'lblLotChanges';
    public const CID_BTN_LOT_CHANGES_OK = 'btnConfLotChanges';
    public const CID_BTN_LOT_CHANGES_CANCEL = 'btnCancLotChanges';
    public const CID_CHK_LOT_CHANGES_AGREE = 'chkConfLotChanges';
    public const CID_TXT_LOT_CHANGES = 'txtLotChanges';

    public const CID_LST_SELECT_BUYER = 'lstSelectBuyer';
    public const CID_LBL_SELECT_BUYER = 'lblSelectBuyer';
    public const CID_BTN_SUB_BUYER = 'btnSubBuyer';

    public const CID_LST_CURRENCY = 'lstCurrency';
    public const CID_LBL_GROUP_BY = 'lblGrpBy';
    public const CID_LBL_MESSAGE_2 = 'lblMessage2';
    public const CID_CHK_SOUND_2 = 'chkSound2';
    public const CID_RAD_AUCTION_1 = 'radAuction1';
    public const CID_RAD_AUCTION_2 = 'radAuction2';
    public const CID_LBL_BID_COUNTDOWN = 'lblBidCountd';
    public const CID_CHK_SOUND = 'chkSound';

    public const CID_LBL_STREAM = 'lblStream';
    public const CID_SCR_STREAM = 'scrStream';

    public const CID_LBL_CONSOLE = 'lblConsole';
    public const CID_LNK_SHOWCONSOLE = 'lnkShowConsole';

    public const CID_LBL_COUNTDOWN = 'lblCountdown';
    public const CID_LBL_PENDING_TIMEOUT_SELECT_LOTS = 'lblPendingTimeoutSelectLots';
    public const CID_LBL_PENDING_TIMEOUT_SELECT_BUYER = 'lblPendingTimeoutSelectBuyer';

    public const CID_BLK_CHAT_MESSAGES = 'chat-messages';
    public const CID_BLK_GROUP_SLIDESHOW = 'group-slideshow';
    public const CID_BLK_DIALOG_MESSAGE = 'dialog-message';
    public const CID_BLK_BTN_OUTBID = 'btn-outbid';

    public const CID_IMG_LOT_IMAGE_TPL = 'limg%s';
    public const CID_IMG_LOT_STANDARD_IMAGE_TPL = 'limgb%s';
    public const CID_BLK_LOT_IMAGE_NOTES = 'lot-img-notes';
    public const CID_BLK_BIDDER_NUM = 'bidder-num';
    public const CID_BLK_WAIT_BIDDER_NUM = 'wait-bidder-num';
    public const CID_BLK_WAIT_ADD_LOTS = 'wait-add-lots';
    public const CID_BLK_PREV_SP = 'prevSp';
    public const CID_BLK_NEXT_SP = 'nextSp';
    public const CID_BLK_PROJECTOR_POP_SIMPLE_WRAPPER = 'projector-pop-simple-wrapper';
    public const CID_BLK_SUB_CONTENT = 'sub-content';
    public const CID_BLK_MAIN = 'main';
    public const CID_BLK_LOT_CHANGES = 'lot-changes';
    public const CID_BLK_PENDING_ACTION_RESET = 'paReset';

    // Css classes
    public const CSS_CLASS_WITH_SIMULTANEOUS_AUCTION = 'with-simultaneous-auction';

    public const CLASS_BLK_AUCTION_DATE = 'auction-date';
    public const CLASS_BLK_BID_TYPE = 'bid-type';
    public const CLASS_BLK_BOTTOM_MOCK_PAGE = 'bottom-mock-page';
    public const CLASS_BLK_CURRENCY_CONT = 'currency-cont';
    public const CLASS_BLK_DATA_PAGE = 'data-page';
    public const CLASS_BLK_DISABLED = 'disabled';
    public const CLASS_BLK_EST_AMOUNT = 'est-amount';
    public const CLASS_BLK_EST_LABEL = 'est-label';
    public const CLASS_BTN_GREY = 'grey';
    public const CLASS_BLK_GRP_TITLE = 'grp-title';
    public const CLASS_BLK_HIDE_CLOSE = 'hide-close';
    public const CLASS_BLK_HIGHEST = 'highest';
    public const CLASS_BTN_SOLD = 'sold';
    public const CLASS_BLK_LOT_BIDDING = 'lot-bidding';
    public const CLASS_BLK_LOT_CATEGORY = 'lot-category';
    public const CLASS_BLK_LOT_IMAGES = 'lot-images';
    public const CLASS_BLK_LOT_ITEMS_WON = 'lot-items-won';
    public const CLASS_BLK_LOT_NAME = 'lot-name';
    public const CLASS_BLK_LOT_NUMBER = 'lot-number';
    public const CLASS_BLK_LOT_TITLE = 'lot-title';
    public const CLASS_BTN_NEXT_IMG = 'nextimg';
    public const CLASS_BLK_ORNG = 'orng';
    public const CLASS_BLK_PLACE_CONT = 'place-cont';
    public const CLASS_BLK_PLACED = 'placed';
    public const CLASS_BLK_POST_ADDED = 'postadded';
    public const CLASS_BLK_PREV_IMG = 'previmg';
    public const CLASS_LNK_TOOLTIP = 'tooltip';
    public const CLASS_BLK_TOP_MOCK_PAGE = 'top-mock-page';
    public const CLASS_BLK_UNSOLD = 'unsold';
    public const CLASS_BLK_UPCOMING = 'upcoming';
    public const CLASS_TBL_FOOTABLE = 'footable';
    public const CLASS_BLK_THUMB = 'thumb';
    public const CLASS_BLK_THUMB_SEL = 'thumb-sel';
    public const CLASS_LBL_AUCTIONEER = 'auc-lbl';
    public const CLASS_BLK_CURRENCY = 'currency';
    public const CLASS_BLK_AMOUNT = 'amount';
    public const CLASS_BLK_OUTBID = 'outbid';
    public const CLASS_BLK_BID_DENIED = 'bid-denied';
    public const CLASS_BLK_BID_OWNER = 'bid-owner';
    public const CLASS_BLK_BID_OUTBID = 'bid-outbid';
    public const CLASS_BLK_BUY_NOW = 'buy-now';
    public const CLASS_BLK_SMALL_PRICE = 'small-price';
    public const CLASS_BLK_CLOSED = 'closed';
    public const CLASS_IMG_LOADER = 'loader-img';
    public const CLASS_BLK_YOUTUBE_WRAP = 'youtube-wrap';
    public const CLASS_IFR_YT_EMBED_FRAME = 'yt-embed-frame';
    public const CLASS_IMG_LIMGB = 'limgb';
    public const CLASS_IMG_LIMG = 'limg';
    public const CLASS_BLK_WARNING = 'warning';
    public const CLASS_BLK_COMBOBOX = 'combobox';
    public const CLASS_LST_AUCTION_LOT_DETAILS = 'auction-lot-details';
    public const CLASS_BLK_PREVIEW = 'preview';
    public const CLASS_LBL_AL_PREVIEW_CHOICE = 'al-preview-choice';
    public const CLASS_LBL_AL_PREVIEW_QTY = 'al-preview-qty';
    public const CLASS_LNK_RTB_LOT_PREVIEW = 'rtb-lot-preview-link';

    public const COLOR_BLACK = '#000';
    public const COLOR_GREEN = '#009900';
    public const COLOR_RED = '#FF0000';

    public const CATALOG_FILTER_STATUS_ALL = 'all';
    public const CATALOG_FILTER_STATUS_SOLD = 'sold';
    public const CATALOG_FILTER_STATUS_UNSOLD = 'unsold';

    public const ATTR_DATA_ROW_NUM = 'data-row-num';
    public const ATTR_DATA_LOT_ITEM_ID = 'data-lot_item_id';
    public const ATTR_DATA_SEO_URL = 'data-seo_url';
    public const ATTR_DATA_THUMBNAIL_URL = 'data-thumbnail-url';
}
