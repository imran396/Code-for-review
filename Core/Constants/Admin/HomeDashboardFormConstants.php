<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/1/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class HomeDashboardFormConstants
 */
class HomeDashboardFormConstants
{
    public const CID_ICO_ACCOUNT_WAIT = 'adf0';
    public const CID_LST_ACCOUNT = 'adf1';
    public const CID_LST_CURRENCY = 'adf2';
    public const CID_PNL_ACTIVE_AUCTIONS = 'adf3';
    public const CID_PNL_CLOSED_AUCTIONS = 'adf4';
    public const CID_PNL_INVOICE_OVERVIEW = 'adf5';
    public const CID_ICO_INVOICE_WAIT = 'adf6';
    public const CID_HID_INVOICE_AUCTION = 'adf7';
    public const CID_CAL_INVOICE_DATE_START = 'adf8';
    public const CID_CAL_INVOICE_DATE_END = 'adf9';
    public const CID_BTN_INVOICE_REFRESH = 'adf10';
    public const CID_PNL_SETTLEMENT_OVERVIEW = 'adf11';
    public const CID_ICO_SETTLEMENT_WAIT = 'adf12';
    public const CID_HID_SETTLEMENT_AUCTION = 'adf13';
    public const CID_CAL_SETTLEMENT_DATE_START = 'adf14';
    public const CID_CAL_SETTLEMENT_DATE_END = 'adf15';
    public const CID_BTN_SETTLEMENT_REFRESH = 'adf16';
    public const CID_TXT_MESSAGE = 'textMessage';
    public const CID_BTN_SAVE = 'messageSave';
    public const CID_BTN_CLEAR = 'messageClear';
    public const CID_BLK_ACTIVE_AUCTIONS = 'active_auctions';
    public const CID_BLK_CLOSED_AUCTIONS = 'closed_auctions';
    public const CID_BLK_INVOICE_OVERVIEW = 'invoice_overview';
    public const CID_BLK_INVOICE_AUCTION_LIST_CONTAINER = 'invoice-auction-list-container';
    public const CID_BLK_SETTLEMENT_OVERVIEW = 'settlement_overview';
    public const CID_BLK_SETTLEMENT_AUCTION_LIST_CONTAINER = 'settlement-auction-list-container';
    public const CID_BLK_ADMIN_MESSAGE = 'adminMessage';
    public const CID_BLK_ADMIN_MESSAGE_ACTIONS = 'adminMessageActions';
    public const CID_BLK_ADMIN_WIDE_MESSAGE = 'adminwidemessage';
    public const CID_BLK_WAIT = 'wait';

    public const CLASS_BLK_MESSAGE = 'message';
    public const CLASS_BLK_NO_MESSAGE = 'no-message';
    public const CLASS_BLK_WITH_MESSAGE = 'with-message';
    public const CLASS_BTN_MESSAGE_CLEAR = 'messageClear';
    public const CLASS_BTN_MESSAGE_EDIT = 'messageEdit';
    public const CLASS_BTN_MESSAGE_SAVE = 'messageSave';
}
