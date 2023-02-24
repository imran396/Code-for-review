<?php
/**
 * SAM-5583: Refactor data loader for Assign-ready item list at Auction Lot List page at admin side
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

namespace Sam\View\Admin\Form\AuctionLotListForm\AssignReady;

use Sam\Lot\Search\Query\LotSearchQueryCriteria;

/**
 * Class AssignReadyLotListConstants
 * @package
 */
class AssignReadyLotListConstants
{
    public const ORD_CONSIGNOR = 'consignor';
    public const ORD_CREATED_ON = 'created_on';
    public const ORD_EDITOR = 'editor_username';
    public const ORD_ESTIMATE = 'estimate';
    public const ORD_HAMMER_PRICE = 'hammer_price';
    public const ORD_ITEM_NO = 'item_no';
    public const ORD_LOT_NAME = 'lot_name';
    public const ORD_RECENT_AUCTION = LotSearchQueryCriteria::ORDER_BY_RECENT_AUCTION;
    public const ORD_STARTING_BID = 'starting_bid';
    public const ORD_WINNER = 'winner_username';
    public const ORD_DEFAULT = self::ORD_ITEM_NO;
}
