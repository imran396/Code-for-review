<?php
/**
 * SAM-4696: Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @author        Vahagn Hovsepyan
 * @since         May 29, 2019
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Core\Constants\Responsive;

use Sam\Core\Constants;

/**
 * Class ReviseBillingInfoFormConstants
 */
class ReviseBillingInfoFormConstants
{
    public const CID_PNL_BILLING_INFO = 'rbi01';
    public const CID_LBL_MESSAGE = 'rbi02';
    public const CID_BTN_SUBMIT = 'rbi03';
    public const CID_BTN_CANCEL = 'rbi04';
    public const CID_LBL_ERROR = 'rbi05';
    public const CID_BLK_SUB_CONTENT = 'sub-content';
    public const CID_BLK_FLASH_NOTIFICATION = 'flash-notification';
    public const CID_TXT_EWAY_CARDNUMBER = Constants\UrlParam::EWAY_CARDNUMBER;
    public const CID_TXT_EWAY_CARDCVN = Constants\UrlParam::EWAY_CARDCVN;
}
