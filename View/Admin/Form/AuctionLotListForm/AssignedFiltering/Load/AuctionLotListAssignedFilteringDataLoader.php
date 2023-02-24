<?php
/**
 * Auction Lot List Assigned Filtering Data Loader
 * SAM-6562: Move data loading for Winning Bidder of Added Lots filtering at Auction Lot List page of admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 28, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\AssignedFiltering\Load;

use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use QMySqli5DatabaseResult;

/**
 * Class AuctionLotListAssignedFilteringDataLoader
 */
class AuctionLotListAssignedFilteringDataLoader extends CustomizableClass
{
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get all winning bidders on this auction
     *
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadWinningBidderRows(int $auctionId, bool $isReadOnlyDb = false): array
    {
        $this->enableReadOnlyDb($isReadOnlyDb);
        $n = "\n";

        // TODO: Replace ANY_VALUE() with more confident choice logic (SAM-10501)
        $query = "SELECT u.id, u.username, ANY_VALUE(ab.bidder_num) AS bidder_num" . $n .
            "FROM auction_lot_item AS ali " . $n .
            "INNER JOIN lot_item li ON li.id=ali.lot_item_id AND li.winning_bidder_id IS NOT NULL AND li.active = true " . $n .
            "INNER JOIN user u ON u.id = li.winning_bidder_id AND u.user_status_id = " . Constants\User::US_ACTIVE . " " . $n .
            "LEFT JOIN auction_bidder ab ON ab.auction_id = ali.auction_id AND ab.user_id = u.id " . $n .
            "WHERE ali.auction_id = " . $this->escape($auctionId) . " " . $n .
            "AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$wonLotStatuses) . ") " . $n .
            "GROUP BY u.id " . $n .
            "ORDER BY bidder_num, u.username";

        $dbResult = $this->query($query);
        $rows = [];
        while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $rows[] = $row;
        }
        return $rows;
    }
}
