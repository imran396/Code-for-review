<?php
/**
 * SAM-9530: "User Absentee Bid" page - extract logic and cover with unit test for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderAbsenteeForm;

/**
 * Class AuctionBidderAbsenteeBidConstants
 * @package Sam\View\Admin\Form\AuctionBidderAbsenteeForm
 */
class AuctionBidderAbsenteeBidConstants
{
    public const ORD_BY_MAX_BID = 'max_bid';
    public const ORD_BY_PLACED_ON = 'placed_on';
    public const ORD_BY_BID_TYPE = 'bid_type';
    public const ORD_BY_LOT_FULL_NUM = 'lot_full_num';
}
