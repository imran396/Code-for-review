<?php
/**
 * SAM-5200: Apply page dependent constants in rtb console code
 * SAM-4897: Refactor request handlers
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           24.05.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\Core\Constants\Admin;

/**
 * Class AuctioneerConsoleConstants
 * @package Sam\Core\Constants\Admin
 */
class AuctioneerConsoleConstants
{
    public const CID_CHK_CATALOG_FOLLOW = 'chkFollow';
    public const CID_RAD_CATALOG_UPCOMING = 'radUpcoming';
    public const CID_RAD_CATALOG_PAST = 'radPast';
    public const CID_LBL_CATALOG = 'lblUpcoming';

    public const CID_BTN_BIDDER_CONFIRM = 'btnConfirm';
    public const CID_IMG_INVOICE_LOGO = 'imgInvoiceLogo';
    public const CID_TXT_BIDDER = 'txtBidder';
    public const CID_LBL_LOT_COUNT = 'lblLotCount';
    public const CID_LBL_LOT_NO = 'lblLotNo';
    public const CID_LBL_LOT_NAME = 'lblLotName';
    public const CID_LBL_RESERVE_PRICE = 'lblReserve';
    public const CID_LBL_LOW_ESTIMATE = 'lblLowEst';
    public const CID_LBL_HIGH_ESTIMATE = 'lblHighEst';
    public const CID_LBL_CURRENT_BID = 'lblCurrent';
    public const CID_LBL_ASKING_BID = 'lblAsking';
    public const CID_LBL_LOT_DETAIL = 'lblLotDet';
    public const CID_LNK_AUCTION_RESULT = 'lnkResult';
    public const CID_LNK_AUCTION_HISTORY = 'lnkHistory';
    public const CID_LBL_OWNER = 'lblOwnBy';
    public const CID_LBL_ABSENTEE_BID_HIGH = 'lblAbs1';
    public const CID_LBL_ABSENTEE_BID_SECOND = 'lblAbs2';
    public const CID_LBL_LOT_IMAGE = 'lblLotImg';
    public const CID_LBL_LOT_IMAGE_BIG = 'lblLotImgBig';
    public const CID_LBL_GROUP_TYPE = 'lblGrpBy';
    public const CID_LBL_CONNECTED_USER = 'lcrtb';
    public const CID_FRM_CONNECTED_USER = 'frmConnected';
    public const CID_LBL_NOTICE = 'lblNotice';
    public const CID_FRM_INTERESTED_BIDDER = 'frmInterest';
    public const CID_TXT_PREV_LOT = 'prev-lot';
    public const CID_LBL_PREV_LOT = 'prev-lot-lbl';
    public const CID_TXT_PREV_HP = 'prev-hp';
    public const CID_LBL_PREV_HP = 'prev-hp-lbl';
    public const CID_TXT_PREV_BUYER = 'prev-buyer';
    public const CID_LBL_PREV_BUYER = 'prev-buyer-lbl';
    public const CID_LBL_BIDDER_ADDRESS = 'lblBidderAddress';
    public const CID_BLK_REOPEN_SALE = 'rs';
    public const CID_BLK_UPCOMING_SCROLL = 'upcoming-scroll';
    public const CID_LNK_INTERESTED_BIDDER_TPL = 'ib%s';

    public const CLASS_BLK_AUCTIONEER_RTB_FORM = 'auctioneer-rtb-form';
    public const CLASS_BLK_LOT_INFO = 'lot-info';
    public const CLASS_BLK_LOT_TITLE = 'lot-title';
    public const CLASS_BLK_LOT_PRICE_INFO = 'lot-price-info';
    public const CLASS_BLK_GRP_TITLE = 'grp-title';
    public const CLASS_LNK_TOOLTIP = 'tooltip';
    public const CLASS_BLK_MSG_BUTTON = 'msg-button';
    public const CLASS_BLK_ABSENTEE_BID_1 = 'lot-abs1';
    public const CLASS_LBL_ABSENTEE_BID_1 = 'abs1-lbl';
    public const CLASS_BLK_ABSENTEE_BID_1_VALUE = 'abs1-val';
    public const CLASS_BLK_ABSENTEE_BID_2 = 'lot-abs2';
    public const CLASS_LBL_ABSENTEE_BID_2 = 'abs2-lbl';
    public const CLASS_BLK_ABSENTEE_BID_2_VALUE = 'abs2-val';
    public const CLASS_BLK_BID_CAP = 'bid-cap';
    public const CLASS_BLK_REOPEN_SALE = 'reopen-sale';
    public const CLASS_LNK_REGISTERED = 'registered';
    public const CLASS_BLK_RESERVE_LABEL = 'rsv-lbl';
    public const CLASS_BLK_RESERVE_VALUE = 'rsv-val';

    public const COLOR_BLACK = '#000000';
    public const COLOR_BLUE = '#0000FF';
    public const COLOR_PURPLE = '#F904FC';
    public const COLOR_WHITE = '#fff';
}
