<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/31/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class AuctionInfoPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class AuctionInfoPanelConstants
{
    public const CID_BTN_ADD_TAX_STATE = 'aip72';
    public const CID_BTN_UPDATE_LOCATION = 'bulid';

    public const CID_CAL_BIDDING_CONSOLE_ACCESS_DATE = 'aip74';
    public const CID_CAL_END_PREBIDDING_DATE = 'aip91';
    public const CID_CAL_PUBLISH_DATE = 'aip78';
    public const CID_CAL_START_BIDDING_DATE = 'aip90';
    public const CID_CAL_START_CLOSING_DATE = 'aip94';
    public const CID_CAL_START_REGISTER_DATE = 'aip89';
    public const CID_CAL_END_REGISTER_DATE = 'aip93';
    public const CID_CAL_UNPUBLISH_DATE = 'aip92';
    public const CID_CAL_LIVE_END_DATE = 'aip95';

    public const CID_CHK_IMAGE_AUTO_ORIENT = 'aip87';
    public const CID_CHK_IMAGE_OPTIMIZE = 'aip88';
    public const CID_CHK_LISTING_ONLY = 'aip73';
    public const CID_CHK_PARCEL_CHOICE = 'aip26';
    public const CID_CHK_REVERSE = 'aip62';
    public const CID_CHK_ONGOING_LOTS = 'aip30';
    public const CID_CHK_NOT_SHOW_UPCOMING_LOTS = 'aip31';
    public const CID_CHK_APPLY_AUCTION_DATES = 'aip32';
    public const CID_CHK_APPLY_ITEM_DATES = 'aip33';
    public const CID_CHK_HIDE_UNSOLD_LOTS = 'aip36';

    public const CID_HID_TMP_IMAGE_PATH = 'aip85';

    public const CID_LBL_AUCTION_TYPE = 'aip1';

    public const CID_LST_AUCTION_TYPE = 'aip0';
    public const CID_LST_EVENT_TYPE = 'aip63';
    public const CID_LST_EXCLUDE_CLOSED_LOTS_TIME = 'aip61';
    public const CID_LST_HP_TAX_SCHEMA = 'aip-hp-tax-schema';
    public const CID_LST_BP_TAX_SCHEMA = 'aip-bp-tax-schema';
    public const CID_LST_SERVICES_TAX_SCHEMA = 'aip-services-tax-schema';
    public const CID_LST_STAGGER_CLOSING_TIME = 'aip15';
    public const CID_TXT_TIMEZONE = 'aip6';
    public const CID_LST_STREAM_DISPLAY = 'aip20';
    public const CID_LST_TAX_SERVICE_COUNTRY = 'aip69';

    public const CID_TXT_INVOICE_LOCATION = 'aip66';
    public const CID_HID_INVOICE_LOCATION_ID = 'aip67';
    public const CID_BTN_ADD_INVOICE_LOCATION = 'aip75';

    public const CID_TXT_EVENT_LOCATION = 'aip76';
    public const CID_HID_EVENT_LOCATION_ID = 'aip77';
    public const CID_BTN_ADD_EVENT_LOCATION = 'aip79';

    public const CID_PNL_TAX_STATE = 'aip71';

    public const CID_PNL_EVENT_LOCATION = 'aip96';
    public const CID_PNL_INVOICE_LOCATION = 'aip97';
    public const CID_PNL_CONSIGNOR_COMMISSION_FEE = 'aip34';

    public const CID_RAD_CLERK_STYLE = 'aip35';

    public const CID_TXT_AUCTION_INFO_LINK = 'aip81';
    public const CID_TXT_DESCRIPTION = 'aip14';
    public const CID_TXT_FULL_SALE_NO = 'aip80';
    public const CID_TXT_INVOICE_NOTES = 'aip64';
    public const CID_TXT_LOT_INTERVAL = 'aip16';
    public const CID_TXT_NAME = 'aip13';
    public const CID_TXT_REMOTE_IMAGE_URL = 'aip86';
    public const CID_TXT_SALE_NUM = 'aip40';
    public const CID_TXT_SALE_NUM_EXT = 'aip41';
    public const CID_TXT_SEO_DESCRIPTION = 'aip84';
    public const CID_TXT_SEO_KEYWORDS = 'aip83';
    public const CID_TXT_SEO_TITLE = 'aip82';
    public const CID_TXT_SHIPPING_INFO = 'aip65';
    public const CID_TXT_STREAM_NAME = 'aip22';
    public const CID_TXT_STREAM_SERVER = 'aip21';
    public const CID_TXT_TAX_PERCENT = 'aip68';
    public const CID_TXT_TERMS_CONDITIONS = 'aip19';
    public const CID_ICO_WAIT = "aip70";
    public const CID_BLK_IMAGE_UPLOAD_CONTAINER = 'imageUploadContainer';
    public const CID_BLK_AUCTION_IMAGE_PREVIEW = 'auction_image_preview';
    public const CID_BTN_UPLOAD_REMOTE = 'uploadRemoteBtn';
    public const CID_BTN_UPLOAD_FILE = 'uploadFileBtn';
    public const CID_BTN_PROCESS_REMOTE_UPLOAD = 'processRemoteUploadBtn';
    public const CID_BTN_UPLOAD_AUCTION_IMAGE = 'upload_auction_image';
    public const CID_BLK_AUCTION_STARTS_ENDING = 'auction-starts-ending';
    public const CID_BLK_TEMPLATE_UPLOAD = 'template-upload';
    public const CID_BLK_AUCTION_START_CLOSING = 'auction-starts-closing';
    public const CID_BLK_STAGGER_CLOSING = 'stagger-closing';
    public const CID_BLK_START_DATE_INDICATOR = 'start-date-indicator';
    public const CID_BLK_END_DATE_INDICATOR = 'end-date-indicator';

    public const CID_LST_TAX_STATE_TPL = 'lstcs%s';

    public const CLASS_BLK_BIDDING_CONSOLE_ACCESS_TIME = 'bidding-console-access-time';
    public const CLASS_BLK_ERROR = 'error';
    public const CLASS_BLK_ERROR_CONTAINER = 'error_container';
    public const CLASS_BLK_HYBRID = 'hybrid';
    public const CLASS_BLK_INPUT = 'input';
    public const CLASS_BLK_LIVE = 'live';
    public const CLASS_BLK_TAX_COUNTRY_STATE = 'tax-country-state';
    public const CLASS_BLK_TIMED = 'timed';
    public const CLASS_BLK_TS_COUNTRY_STATE = 'ts_country_state';
    public const CLASS_BLK_UPLOAD_FILE = 'uploadFile';
    public const CLASS_BLK_UPLOAD_REMOTE = 'uploadRemote';
    public const CLASS_BTN_REMOVE_AUCTION_STATE = 'removeAuctionState';
    public const CID_BLK_AUCTION_ITEMS = 'auction-items';

    // Group name
    public const GN_APPLY_AUCTION_ITEM_DATES = 'aucdate';
}
