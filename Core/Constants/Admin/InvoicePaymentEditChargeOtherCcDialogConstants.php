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

use Sam\Core\Constants;

/**
 * Class ChargeInvoiceCcInfoDialogConstants
 */
class InvoicePaymentEditChargeOtherCcDialogConstants
{
    public const CID_ICO_CONFIRM_WAIT = 'ipecocd0';
    public const CID_TXT_FIRST_NAME = 'ipecocd1';
    public const CID_TXT_LAST_NAME = 'ipecocd2';
    public const CID_LST_CC_TYPE = 'ipecocd19';
    public const CID_TXT_CC_NUMBER = 'ipecocd3';
    public const CID_LST_MONTH = 'ipecocd4';
    public const CID_LST_YEAR = 'ipecocd5';
    public const CID_TXT_CC_CODE = 'ipecocd6';
    public const CID_BTN_SUBMIT = 'ipecocd7';
    public const CID_BTN_CANCEL = 'ipecocd8';
    public const CID_CHK_REPLACE_OLD_CARD = 'ipecocd21';
    public const CID_LBL_MESSAGE = 'ipecocd22';
    public const CID_LST_PAYMENT_METHOD = 'ipecocd23';
    public const CID_TXT_EWAY_CARDNUMBER = Constants\UrlParam::EWAY_CARDNUMBER;
    public const CID_TXT_EWAY_CARDCVN = Constants\UrlParam::EWAY_CARDCVN;
}
