<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/14/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class CouponListFormConstants
 */
class CouponListFormConstants
{
    public const CID_BTN_ADD = 'clf0';
    public const CID_TXT_TITLE = 'clf1';
    public const CID_TXT_CODE = 'clf2';
    public const CID_CAL_START_DATE = 'clf3';
    public const CID_TXT_TIMEZONE = 'clf4';
    public const CID_CAL_END_DATE = 'clf5';
    public const CID_TXT_MIN_PUR_AMT = 'clf7';
    public const CID_TXT_PER_USER = 'clf8';
    public const CID_LST_TYPE = 'clf9';
    public const CID_CHK_WAIVE_ADDITIONAL_CHANGES = 'clf10';
    public const CID_BTN_SAVE = 'clf11';
    public const CID_BTN_CANCEL = 'clf12';
    public const CID_DTG_COUPONS = 'clf16';
    public const CID_LST_SHOW = 'clf17';
    public const CID_TXT_FIXED_AMT_OFF = 'clf18';
    public const CID_TXT_PERCENTAGE_OFF = 'clf19';
    public const CID_PNL_LOT_CATEGORY = 'clf20';
    public const CID_ICO_WAIT_LOT_CAT = 'clf21';
    public const CID_BTN_ADD_LOT_CATEGORY = 'clf22';
    public const CID_LST_AUCTIONS = 'clf23';
    public const CID_BTN_EDIT_TPL = '%sbedi%s';
    public const CID_BTN_DELETE_TPL = '%sbdel%s';
    public const CID_BTN_DEACTIVATE_TPL = '%sbdea%s';
    public const CID_BTN_ACTIVATE_TPL = '%sbact%s';
    public const CID_BTN_REMOVE_AUCTION_TPL = 'rtnrmlc%s';
    public const CID_PNL_BR_CC_TPL = 'pnlbrcc%s';

    public const CLASS_BLK_FIXED_AMT = 'fixed-amt';
    public const CLASS_BLK_PERCENT = 'percent';
    public const CLASS_BLK_WAIVE_CHARGE = 'waive-charge';
}
