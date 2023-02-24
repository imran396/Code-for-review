<?php
/**
 * SAM-4696: Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @author        Oleg Kovalov
 * @since         May 31, 2019
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Core\Constants\Responsive;

/**
 * Class LotDetailsTimedConstants
 * @package Sam\Core\Constants\Responsive
 */
class LotDetailsTimedConstants
{
    public const CID_BEST_OFFER_BTN_ACCEPT_OFFER = 'oai19';
    public const CID_BEST_OFFER_BTN_DECLINE_OFFER = 'oai20';
    public const CID_BEST_OFFER_LBL_OFFER_MESSAGE = 'oai23';
    public const CID_BEST_OFFER_LBL_OFFER_MESSAGE2 = 'oai27';
    public const CID_LBL_MESSAGE = 'oai22';
    public const CID_LBL_BELOW_RESERVE = 'oai24';
    public const CID_BTN_PLACE_BID = 'btnV0';
    public const CID_BTN_BUY_NOW = 'btnV2';
    public const CID_BTN_BUY_NOW_TPL = 'bBnow%s';
    public const CID_TXT_BUY_NOW_QUANTITY_TPL = 'bBnowQty%s';
    public const CID_BTN_PLACE_BID_TPL = 'bPbid%s';
    public const CID_BTN_REGISTER_TPL = 'breg%s';
    public const CID_BTN_FORCE_BID_TPL = 'bFbid%s';
    public const CID_TXT_MAX_BID_TPL = 'tmbid%s';
    public const CID_TXT_FORCE_BID_TPL = 'tfbid%s';
    public const CID_HID_VIS_ASK_BID_TPL = 'hidVisAskBid%s';
    public const CID_BEST_OFFER_TEXT_FIELD_TPL = 'tOffer%s';
    public const CID_BEST_OFFER_BTN_MAKE_OFFER_TPL = 'bOffer%s';
    public const CID_BTN_NEXT_TPL = 'bNext%s';
    public const CID_BLK_ASKING = 'asking';
    public const CID_BLK_ENTER_ASKING = 'enterAsking';
    public const CID_BLK_ASKING_FORCE_BID = 'askingForceBid';
    public const CID_BLK_CURRENT_BID = 'currentBid';
    public const CID_BLK_BID_HISTORY = 'bidHistory';
    public const CID_TXT_CURRENT_BID = 'currbid';
    public const CID_BLK_SLIDE_SHOW = 'slider4';
    public const CID_BLK_NAV = 'nav';
    public const CID_BLK_AUC_LOT_TPL = 'auc-lot%s';
    public const CID_BLK_AUC_LOT_PRICE_TPL = 'auc-lot-price%s';
    public const CID_BLK_LOT_TIME_TPL = 'lot-time%s';

    public const CLASS_BLK_BIDDING = 'bidding';
    public const CLASS_BLK_BID_STATUS_OTHER = 'bid-status-other';
    public const CLASS_BLK_BID_STATUS_OWNER = 'bid-status-owner';
    public const CLASS_BLK_BULK_MASTER_ASKING = 'bulk-master-asking';
    public const CLASS_BLK_OR_TIMED = 'or-timed';
    public const CLASS_BTN_INLINE_CONFIRM = 'inline-confirm';
    public const CLASS_LBL_WARNING = 'warning';
    public const CLASS_PNL_BUYING = 'buying';
}
