<?php
/**
 * Db data manager for bidder outstanding limit calculation
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Mar 27, 2015
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\Outstanding\Storage;

use QMySqli5DatabaseResult;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataManager
 * @package Sam\Bidder\Outstanding\Storage
 */
class DataManager extends CustomizableClass implements IDataManager
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
     * Calculate "Spent" value
     * @param int $auctionId
     * @param int $userId
     * @return float
     */
    public function calcSpent(int $auctionId, int $userId): float
    {
        $n = "\n";
        // @formatter:off
        $query =
            "SELECT SUM(li.hammer_price) AS spent" . $n
            . " FROM lot_item li" . $n
            . " WHERE li.auction_id = " . $this->escape($auctionId) . $n
                . " AND li.`winning_bidder_id` = " . $this->escape($userId) . $n
                . " AND li.active";
        // @formatter:on
        $dbResult = $this->query($query);
        $rows = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        $spent = (float)($rows['spent'] ?? 0.);
        return $spent;
    }

    /**
     * Calculate "Collected" value
     * @param int $auctionId
     * @param int $userId
     * @return float
     */
    public function calcCollected(int $auctionId, int $userId): float
    {
        $n = "\n";
        // @formatter:off
        $query =
            "SELECT SUM(ii.hammer_price) AS collected" . $n
            . " FROM invoice_item ii" . $n
            . " INNER JOIN invoice i" . $n
                . " ON i.id = ii.invoice_id" . $n
                . " AND i.invoice_status_id = " . Constants\Invoice::IS_PAID . $n
            . " WHERE ii.auction_id = " . $this->escape($auctionId) . $n
                . " AND ii.winning_bidder_id = " . $this->escape($userId) . $n
                . " AND ii.active" . $n
                . " AND ii.release = false";
        // @formatter:on

        $dbResult = $this->query($query);
        $rows = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        $collected = (float)($rows['collected'] ?? 0.);
        return $collected;
    }

    /**
     * Return auction bidder ids, who have exceeded outstanding limit
     * @param int $auctionId
     * @return int[]
     */
    public function findExceededLimitAuctionBidderIds(int $auctionId): array
    {
        $n = "\n";
        // @formatter:off
        $query =
            "SELECT GROUP_CONCAT(aub.id) AS ids" . $n
            . " FROM auction_bidder aub" . $n
            . " LEFT JOIN auction a ON aub.auction_id = a.id" . $n
            . " LEFT JOIN `user_info` ui ON ui.user_id = aub.user_id" . $n
            . " WHERE aub.auction_id = " . $this->escape($auctionId) . $n
            . " AND (" . $n
            . "IF(ui.`max_outstanding` IS NOT NULL, ui.`max_outstanding`, a.`max_outstanding`) IS NOT NULL" . $n
            . " AND IFNULL(aub.spent, 0) - IFNULL(aub.`collected`, 0)" . $n
            . " > IF(ui.`max_outstanding` IS NOT NULL, ui.`max_outstanding`, a.`max_outstanding`)" . $n
            . ")";
        // @formatter:on
        $dbResult = $this->query($query);
        $rows = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        $ids = ArrayCast::castInt(explode(',', $rows['ids']));
        return $ids;
    }
}
