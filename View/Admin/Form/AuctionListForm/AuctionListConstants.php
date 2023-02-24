<?php
/**
 * SAM-5584: Refactor data loader for Auction List page at admin side
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

namespace Sam\View\Admin\Form\AuctionListForm;

/**
 * Class AuctionListConstants
 */
class AuctionListConstants
{
    public const ORD_AUCTION_DATE = 'auction_date';
    public const ORD_START_CLOSING_DATE = 'start_closing_date';
    public const ORD_END_DATE = 'end_date';
    public const ORD_TOTAL_LOTS = 'total_lots';
    public const ORD_SALE_NO = 'sale_no';
    public const ORD_NAME = 'name';
    public const ORD_AUCTION_STATUS_ID = 'auction_status_id';
    public const ORD_AUCTION_TYPE = 'auction_type';
    public const ORD_PUBLISHED = 'published';
    public const ORD_ACCOUNT_NAME = 'account_name';

    public const ORD_DEFAULT = self::ORD_AUCTION_DATE;
}
