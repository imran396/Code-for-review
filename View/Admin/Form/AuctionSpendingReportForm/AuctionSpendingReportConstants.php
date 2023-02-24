<?php
/**
 * Auction Spending Report Constants
 *
 * SAM-5841: Refactor data loader for Auction Spending Report page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 18, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionSpendingReportForm;

/**
 * Class ActionSpendingReportConstants
 */
class AuctionSpendingReportConstants
{
    public const CID_ORD_USERNAME = 'username';
    public const CID_ORD_FIRST_LAST_NAME = 'first_last_name';
    public const CID_ORD_BIDDER_NUM = 'bidder_num';
    public const CID_ORD_SPENT = 'spent';
    public const CID_ORD_COLLECTED = 'collected';
    public const CID_ORD_MAX_OUTSTANDING = 'max_outstanding';
    public const CID_ORD_OUTSTANDING = 'outstanding';
}