<?php
/**
 * SAM-6431: Refactor Auction_Lots_DbCacheManager for 2020 year version
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Cache\ViewCount\Save;

use Sam\Core\Service\CustomizableClass;
use Exception;
use QMySqliDatabaseException;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Constants;

/**
 * Class AuctionLotCacheViewCountUpdater
 * @package ${NAMESPACE}
 */
class AuctionLotCacheViewCountUpdater extends CustomizableClass
{
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Increase view count of an auction_lot_item_cache by one
     *
     * @param int $auctionLotId
     * @param int $editorUserId
     */
    public function increase(int $auctionLotId, int $editorUserId): void
    {
        $db = $this->getDb();
        $n = "\n";
        $query =
            "UPDATE auction_lot_item_cache"
            . " SET view_count = IFNULL(view_count,0) + 1,"
            . " modified_by = " . $this->escape($editorUserId)
            . " WHERE auction_lot_item_id=" . $this->escape($auctionLotId);
        try {
            $db->NonQuery($query);
        } catch (Exception $e) {
            log_error(
                'Exception caught on lot view count increasing for auction lot'
                . composeSuffix(['id' => $auctionLotId, 'Message' => $e->getMessage(), 'Code' => $e->getCode(), 'Query' => $query])
            );
            return;
        }
        try {
            $query = "SET @auction_id = null; " . $n
                . "SELECT @auction_id := ali.auction_id AS auction_id"
                . " FROM auction_lot_item ali"
                . " WHERE ali.id = " . $this->escape($auctionLotId) . "; " . $n
                . "UPDATE auction_cache ac"
                . " SET ac.total_views = IFNULL(ac.total_views, 0) + 1,"
                . " ac.modified_by = " . $this->escape($editorUserId)
                . " WHERE ac.auction_id = @auction_id;";
            $db->MultiQuery($query);
        } catch (Exception $e) {
            log_error(
                'Exception caught on total lot view count increasing of auction.'
                . composeSuffix(['Message' => $e->getMessage(), 'Code' => $e->getCode(), 'Query' => $query,])
            );
            return;
        }
    }

    /**
     * Set view count of auction lots to 0
     *
     * @param int $auctionId
     * @param int $editorUserId
     * @throws QMySqliDatabaseException
     */
    public function resetByAuctionId(int $auctionId, int $editorUserId): void
    {
        $db = $this->getDb();
        $query =
            "UPDATE auction_lot_item_cache alic"
            . " LEFT JOIN auction_lot_item ali ON alic.`auction_lot_item_id` = ali.`id`"
            . " SET alic.`view_count` = 0,"
            . " alic.modified_by = " . $this->escape($editorUserId)
            . " WHERE ali.auction_id = " . $this->escape($auctionId)
            . " AND lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ")";
        $db->NonQuery($query);
        $query = "UPDATE auction_cache ac"
            . " SET ac.total_views = 0,"
            . " ac.modified_by = " . $this->escape($editorUserId)
            . " WHERE ac.auction_id = " . $this->escape($auctionId);
        $db->NonQuery($query);
    }
}
