<?php
/**
 * SAM-5657: Refactor data loader for Assigned lot datagrid at Auction Lot List page at admin side
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/26/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\Assigned;

/**
 * Class AssignedAuctionLotListConstants
 * @package
 */
class AssignedAuctionLotListConstants
{
    public const ORD_LOT_ORDER = 'order_num';
    public const ORD_IMAGE_COUNT = 'image_count';
    public const ORD_LOT_NO = 'lot_no';
    public const ORD_ITEM_NO = 'item_no';
    public const ORD_GROUP = 'group';
    public const ORD_LOT_NAME = 'lot_name';
    public const ORD_QUANTITY = 'quantity';
    public const ORD_ESTIMATE = 'estimate';
    public const ORD_STARTING_BID = 'starting_bid';
    public const ORD_HAMMER_PRICE = 'hammer_price';
    public const ORD_RESERVE_PRICE = 'reserve_price';
    public const ORD_WINNER = 'winner_username';
    public const ORD_WINNER_EMAIL = 'winner_email';
    public const ORD_WINNER_COMPANY = 'winner_company';
    public const ORD_INTERNET_BID = 'internet_bid';
    public const ORD_CURRENT_BID_TIMED = 'current_bid_timed';
    public const ORD_CURRENT_BID_LIVE = 'current_bid_live';
    public const ORD_CURRENT_MAX_BID = 'current_max_bid';
    public const ORD_CONSIGNOR = 'consignor';
    public const ORD_CONSIGNOR_COMPANY = 'consignor_company';
    public const ORD_CONSIGNOR_EMAIL = 'consignor_email';
    public const ORD_VIEW_COUNT = 'view_count';
    public const ORD_LOT_STATUS = 'lot_status';
    public const ORD_EDITOR = 'editor_username';
    public const ORD_LOT_END_DATE = 'lot_end_date';
    public const ORD_LOT_SECONDS_LEFT = 'lot_seconds_left';
    public const ORD_LOT_BID_COUNT = 'bid_count';
    public const ORD_LOT_HIGH_BIDDER = 'high_bidder';
    public const ORD_LOT_HIGH_BIDDER_COMPANY = 'high_bidder_company';
    public const ORD_LOT_HIGH_BIDDER_EMAIL = 'high_bidder_email';
    public const ORD_LOT_LAST_BID_TIME = 'current_bid_placed';

    public const ORD_DEFAULT = self::ORD_LOT_ORDER;
}
