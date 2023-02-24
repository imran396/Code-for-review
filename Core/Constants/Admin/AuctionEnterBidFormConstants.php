<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/20/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class AuctionEnterBidFormConstants
 */
class AuctionEnterBidFormConstants
{
    public const AT_SUBMIT_BID = 1;
    public const AT_SELL_LOT = 2;

    public const CID_TXT_LOT_FULL_NUM = 'aeb8';
    public const CID_TXT_LOT_NUM = 'aeb1';
    public const CID_TXT_LOT_NUM_PREF = 'aeb6';
    public const CID_TXT_LOT_NUM_EXT = 'aeb7';
    public const CID_TXT_AMOUNT = 'aeb2';
    public const CID_TXT_BIDDER_NUM = 'aeb3';
    public const CID_BTN_SUBMIT = 'aeb4';
    public const CID_BTN_SELL_LOT = 'aeb5';
    public const CID_CHK_NOTIFY = 'abn19';

    public const CLASS_LNK_OFF_INCREMENT = 'off-increment';

    //Reverse tag will be used for reverse translation
    public const REVERSE_SUFFIX = ".reverse";
}
