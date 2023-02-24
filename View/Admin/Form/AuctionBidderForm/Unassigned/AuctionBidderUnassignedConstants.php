<?php
/**
 * SAM-5593: Refactor data loaders for Auction Bidder List page at admin side
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           07/01/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderForm\Unassigned;

/**
 * Class AuctionBidderUnassignedConstants
 */
class AuctionBidderUnassignedConstants
{
    public const ORD_CREATED_ON = 'created_on';
    public const ORD_CUSTOMER_NO = 'customer_no';
    public const ORD_USERNAME = 'username';
    public const ORD_EMAIL = 'email';
    public const ORD_FLAG = 'flag';

    public const ORD_DEFAULT = self::ORD_CREATED_ON;
}
