<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/15/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class LotInfoPanelConstants
 */
class LotInfoPanelConstants
{
    public const CID_LBL_STATUS = 'lip1';
    public const CID_LBL_START_BID_REQUIRED = 'lip1b';
    public const CID_TXT_ITEM_NUM = 'lip2';
    public const CID_TXT_ITEM_NUM_EXT = 'lip115';
    public const CID_HID_CATEGORY_DEFAULTS_CONFIRMED = 'lip112';
    public const CID_PNL_CATEGORY = 'lip3';
    public const CID_PNL_IMAGE_DROPZONE = 'lip120';
    public const CID_ICO_WAIT_CAT = 'lip4';
    public const CID_BTN_ADD_CATEGORY = 'lip6';
    public const CID_TXT_NO = 'lip8';
    public const CID_TXT_NAME = 'lip9';
    public const CID_TXT_DESCRIPTION = 'lip10';
    public const CID_TXT_GROUP_ID = 'lip11';
    public const CID_TXT_LOW_ESTIMATE = 'lip16';
    public const CID_TXT_HIGH_ESTIMATE = 'lip17';
    public const CID_TXT_STARTING_BID = 'lip18';
    public const CID_LNK_INCREMENT_ADD = 'lip18a1';
    public const CID_LNK_INCREMENTS_EDIT = 'lip18a2';
    public const CID_PNL_INCREMENT = 'lip18c';
    public const CID_LST_BP_RULE = 'lip119';
    public const CID_PNL_BUYERS_PREMIUM = 'lip12c';
    public const CID_TXT_RESERVE = 'lip19';
    public const CID_TXT_CONSIGNOR = 'lip101';
    public const CID_HID_CONSIGNOR = 'lip102';
    public const CID_TXT_HAMMER_PRICE = 'lip21';
    public const CID_HID_WINNING_AUCTION = 'lip22';
    public const CID_TXT_WINNING_BIDDER = 'lip23';
    public const CID_ICO_WAIT_CONS = 'lip68';
    public const CID_BTN_NEW_CONSIGNOR = 'lip62';
    public const CID_LBL_CONSIGNOR = 'lip63';
    public const CID_TXT_CONSIGNOR_USERNAME = 'lip64';
    public const CID_BTN_SAVE = 'lip65';
    public const CID_BTN_SAVE_EDIT = 'lip66';
    public const CID_BTN_CANCEL = 'lip67';
    public const CID_BTN_POPULATE = 'lip78';
    public const CID_BTN_ADD_TERMS_AND_COND = 'lip90';
    public const CID_TERMS = 'lip91';
    public const CID_BTN_ADD_TERMS_AND_COND_DELETE = 'lip92';
    public const CID_HID_TERMS_AND_CONDITIONS_CONFIRM = 'lip93';
    public const CID_TXT_BUY_NOW = 'lip31';
    public const CID_CHK_NO_BIDDING = 'lip32';
    public const CID_CHK_BEST_OFFER = 'lip325';
    public const CID_CHK_PUBLISHED = 'lip33';
    public const CID_CHK_SAMPLE_LOT = 'lip34';
    public const CID_TXT_LOT_NUM_EXT = 'lip36';
    public const CID_TXT_LOT_NUM_PREFIX = 'lip96';
    public const CID_LBL_AUCTION_TYPE = 'lip39';
    public const CID_CAL_PUBLISH_DATE = 'lip126';
    public const CID_CAL_START_BIDDING_DATE = 'lip127';
    public const CID_CAL_END_PREBIDDING_DATE = 'lip128';
    public const CID_CAL_UNPUBLISH_DATE = 'lip129';
    public const CID_CAL_START_CLOSING_DATE = 'lip130';
    public const CID_TXT_TIMEZONE = 'lip44';
    public const CID_DTG_OFFER_BID = 'lip51';
    public const CID_ICO_WAIT_OFFER_BID = 'lip52';
    public const CID_LBL_OFFER_BID_MESSAGE = 'lip53';
    public const CID_LNK_DELETE_HISTORY = 'lip55';
    public const CID_TXT_NOTE_TO_CLERK = 'lip56';
    public const CID_TXT_GENERAL_NOTE = 'lip57';
    public const CID_CHK_TAX_EXEMPT = 'lip58';
    public const CID_CHK_NO_TAX_OOS = 'lip59';
    public const CID_CHK_TAX_ARTIST_RESALE_RIGHTS = 'lip69';
    public const CID_TXT_SALES_TAX = 'lip60';
    public const CID_CHK_RETURNED = 'lip61';
    public const CID_TXT_WARRANTY = 'lip70';
    public const CID_LST_STATUS = 'lip71';
    public const CID_TXT_QUANTITY = 'lip72';
    public const CID_TXT_QUANTITY_DIGITS = 'lip114';
    public const CID_CHK_QUANTITY_X_MONEY = 'lip73';
    public const CID_CHK_BUY_NOW_SELECT_QUANTITY_ENABLED = 'lip75';
    public const CID_CHK_ONLY_TAX_BP = 'lip103';
    public const CID_CHK_INTERNET_BID = 'lip74';
    public const CID_CAL_DATE_SOLD = 'lip81';
    public const CID_TXT_COST = 'lip82';
    public const CID_TXT_REPLACEMENT_PRICE = 'lip83';
    public const CID_ICO_WAIT_SEARCH = 'lip86';
    public const CID_HID_WINNING_BIDDER = 'lip87';
    public const CID_HID_IMAGE_ORDER = 'lip88';
    public const CID_DTG_LOT_ITEM_SYNC = 'lip89';
    public const CID_TXT_CHANGES = 'lip95';
    public const CID_CHK_LISTING_ONLY = 'lip113';
    public const CID_LST_TAX_SERVICE_COUNTRY = 'lip104';
    public const CID_ICO_WAIT_TAX_STATE = 'lip105';
    public const CID_PNL_TAX_STATE = 'lip106';
    public const CID_BTN_ADD_TAX_STATE = 'lip107';
    public const CID_LST_BULK_GROUP = 'lip110';
    public const CID_LST_BULK_WIN_BID_DISTRIBUTION = 'lip111';
    public const CID_TXT_SEO_URL = 'lip116';
    public const CID_TXT_LOT_HEADER_TITLE = 'lht1';
    public const CID_TXT_LOT_HEADER_DESC = 'lhd1';
    public const CID_TXT_LOT_HEADER_IMG = 'lhi1';
    public const CID_TXT_SEO_TITLE = 'seot';
    public const CID_TXT_SEO_DESCRIPTION = 'seod';
    public const CID_TXT_SEO_KEYWORDS = 'seok';
    public const CID_TXT_LOT_FULL_NUM = 'lip117';
    public const CID_TXT_ITEM_FULL_NUM = 'lip118';
    public const CID_CHK_IMAGE_AUTO_ORIENT = 'lip124';
    public const CID_CHK_IMAGE_OPTIMIZE = 'lip125';
    public const CID_TXT_CATEGORY_TPL = 'txtc%s';
    public const CID_BTN_REMOVE_CATEGORY_TPL = 'btnrmc%s';
    public const CID_BTN_OFFER_BID_ACCEPT_TPL = '%sbtnaccept%s';
    public const CID_BTN_OFFER_BID_REJECT_TPL = '%sbtnreject%s';
    public const CID_BTN_OFFER_BID_COUNTER_TPL = '%sbtncounter%s';
    public const CID_BTN_FILE_TPL = 'btnFile%s';
    public const CID_PNL_FILE_TPL = 'pnlFile%s';
    public const CID_PNL_CUSTOM_FIELD_FILE_TPL = 'pnlCustomFieldFile%s';
    public const CID_CUSTOM_FIELD_TPL = 'custFld%s';
    public const CID_CUSTOM_LABEL_TPL = 'custLbl%s';
    public const CID_ICO_MIX_WAIT_TPL = 'mixWait%s';
    public const CID_BTN_MIX_GENERATE_TPL = 'mixGenerate%s';
    public const CID_BTN_MIX_PRINT_TPL = 'mixPrint%s';
    public const CID_LTS_TAX_STATE = 'lstcs';
    public const CID_LTS_TAX_STATE_TPL = 'lstcs%s';
    public const CID_BTN_REMOVE_TAX_STATE_TPL = 'btnrcs%s';
    public const CID_PNL_REMOVE_TAX_STATE_TPL = 'pnlbrcs%s';
    public const CID_IMG_FILE_LOT_ICON_TPL = 'lotImg%s';
    public const CID_TXT_FIELD_TPL = '%stxt%s';
    public const CID_BTN_ACC_TPL = '%sacc%s';
    public const CID_BTN_CNL_TPL = '%scnl%s';
    public const CID_BLK_BEST_OFFER = 'bestOffer';
    public const CID_BLK_BUY_NOW_INFO = 'buyNowInfo';
    public const CID_BLK_NO_BIDDING = 'noBidding';
    public const CID_BLK_REGULAR_INFO_STARTING_BID = 'regularInfo_StartingBid';
    public const CID_BLK_REGULAR_INFO_INCREMENTS = 'regularInfo_Increments';
    public const CID_BLK_REGULAR_INFO_COST = 'regularInfo_Cost';
    public const CID_BLK_REGULAR_INFO_REPLACEMENT_PRICE = 'regularInfo_ReplacementPrice';
    public const CID_BLK_REGULAR_INFO_RESERVE_PRICE = 'regularInfo_ReservePrice';
    public const CID_BLK_AUCTION_LIST_CONTAINER = 'auction-list-container';
    public const CID_BLK_LOT_INFO_FORM = 'lot-info-form';
    public const CID_BLK_SAMPLE_LOT = 'l1';
    public const CID_BLK_CONSIGNOR_ID = 'consId';
    public const CID_BLK_WINNING_ID = 'winningId';
    public const CID_LOT_FIELD_CONFIG_TPL = 'lfc_%s';
    public const CID_LST_CATEGORY = 'lstc';
    public const CID_LNK_REMOVE_FROM_SALE_TPL = 'lnkRemoveFromSaleForLotItem%s';
    public const CID_TXT_LOCATION = 'lip121';
    public const CID_HID_LOCATION = 'lip122';
    public const CID_BTN_ADD_LOCATION = 'lip123';
    public const CATEGORY_IMAGE_CONTROL_ID_BASE = 'imgc';
    public const CID_PNL_CONSIGNOR_COMMISSION_FEE = 'lip40';
    public const CID_PNL_INVOICE_LOCATION = 'lip131';
    public const CID_LST_HP_TAX_SCHEMA = 'lip-hp-tax-schema';
    public const CID_BTN_POPULATE_HP_TAX_SCHEMA = 'lip-populate-hp-tax-schema';
    public const CID_LST_BP_TAX_SCHEMA = 'lip-bp-tax-schema';
    public const CID_BTN_POPULATE_BP_TAX_SCHEMA = 'lip-populate-bp-tax-schema';

    public const CLASS_BLK_ADD_LINK = 'addlink';
    public const CLASS_BLK_BARCODE = 'barcode';
    public const CLASS_BLK_BEST_OFFER = 'best-offer';
    public const CLASS_BLK_BULK_MASTER_FIELD = 'bulk-master-field';
    public const CLASS_BLK_BULK_MASTER_LABEL = 'bulk-master-label';
    public const CLASS_BLK_CHECK_STRAIGHT = 'check-straight';
    public const CLASS_BLK_LOT_ADD_BUY_CONTAINER_FIELD = 'lot-add-buy-container-field';
    public const CLASS_BLK_LOT_EDIT_BUY_CONTAINER_FIELD = 'lot-edit-buy-container-field';
    public const CLASS_BLK_ONLINE_ITEM = 'online-item';
    public const CLASS_BLK_REQ = 'req';
    public const CLASS_BLK_STRAIGHT = 'straight';
    public const CLASS_BLK_TAX_COUNTRY_STATE = 'tax-country-state';
    public const CLASS_BLK_WIN_BID_DEST_FIELD = 'win-bid-dist-field';
    public const CLASS_BLK_WIN_BID_DEST_LABEL = 'win-bid-dist-label';
}
