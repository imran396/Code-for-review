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
 * Class LotInfoFormConstants
 */
class LotInfoFormConstants
{
    public const CID_ICO_WAIT = 'lid0';
    public const CID_BTN_SAVE = 'lid1';
    public const CID_BTN_SAVE_EXIT = 'lid6';
    public const CID_BTN_SAVE_ADD_MOVE = 'lid7';
    public const CID_BTN_CANCEL = 'lid2';
    public const CID_BTN_SAVE_ON_TOP = 'lid8';
    public const CID_BTN_SAVE_EXIT_ON_TOP = 'lid9';
    public const CID_BTN_SAVE_ADD_MORE_ON_TOP = 'lid10';
    public const CID_BTN_CANCEL_ON_TOP = 'lid11';
    public const CID_PNL_LOT_INFO = 'lid4';
    public const CID_ICO_CLONE_WAIT = 'lid12';
    public const CID_BTN_CLONE_LOT = 'lid13';
    public const CID_ICO_CLONE_WAIT_BOTTOM = 'lid18';
    public const CID_BTN_CLONE_LOT_BOTTOM = 'lid19';
    public const CID_DLG_CONFIRM_CLONE_LOT_FIELDS = 'lid24';
    public const CID_DLG_CONFIRM_REMOVE_UNSOLD = 'lip77';
    public const CID_DLG_CONFIRM_AUCTION_LOT_DATETIME = 'lid5';
    public const CID_LST_TAX_STATE_TPL = 'lstcs%s';
    public const CID_CUST_FIELD_TPL = 'custFld%s';
    public const CID_LOT_INFO_FORM = 'LotInfoForm';
    public const CID_PREVIEW_IN_AUCTION = 'previewInAuction';
    public const CID_LOT_FIELD_CUSTOM_TPL = 'lfc_fc%s';
    public const CID_LOT_FIELD_HIDDEN = 'lot-field-hidden';

    public const ACT_NO_ACTION = 'noAction';
    public const ACT_SAVE_AND_EXIT = 'saveAndExit';
    public const ACT_SAVE_ADD_MORE = 'saveAddMore';

    // css classes
    public const HIDDEN_LOT_ITEM_ID = 'hidden-lot-item-id';
    public const HIDDEN_ACCOUNT_ID = 'hidden-account-id';
    public const CLASS_BLK_CLOSE = 'close';
    public const CLASS_BLK_LEGEND = 'legend_div';
    public const CLASS_BLK_LEG_ALL = 'legall';
    public const CLASS_BLK_OPEN = 'open';
    public const CLASS_BLK_SAM_HELP_CONTENT = 'sam-help-content';
    public const CLASS_BLK_TOOLTIP = 'tooltip';
}
