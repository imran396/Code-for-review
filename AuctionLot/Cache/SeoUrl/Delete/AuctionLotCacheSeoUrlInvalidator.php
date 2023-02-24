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

namespace Sam\AuctionLot\Cache\SeoUrl\Delete;

use Sam\Core\Service\CustomizableClass;
use QMySqliDatabaseException;
use Sam\Core\DataSource\DbConnectionTrait;

/**
 * Class AuctionLotCacheSeoUrl
 * @package Sam\AuctionLot\Cache
 */
class AuctionLotCacheSeoUrlInvalidator extends CustomizableClass
{
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * Drop alic.seo_url for auction lots
     * @param int $editorUserId
     * @param int[]|null $auctionLotIds
     * @param int[]|null $lotItemIds
     * @param int[]|null $accountIds
     */
    public function drop(
        int $editorUserId,
        ?array $auctionLotIds = null,
        ?array $lotItemIds = null,
        ?array $accountIds = null
    ): void {
        $db = $this->getDb();
        $conditions[] = 'alic.seo_url IS NOT NULL';
        $allJoins = [
            'ali' => 'JOIN auction_lot_item ali ON ali.id = alic.auction_lot_item_id',
            'li' => 'JOIN lot_item li ON li.id = ali.lot_item_id',
            'acc' => 'JOIN account acc ON acc.id = ali.account_id',
        ];
        $joinKeys = [];
        $logMessages = [];
        if ($auctionLotIds) {
            $ids = [];
            foreach ($auctionLotIds as $auctionLotId) {
                $ids[] = $db->SqlVariable($auctionLotId);
            }
            $idList = implode(',', $ids);
            $conditions[] = "alic.auction_lot_item_id IN ({$idList})";
            $joinKeys[] = 'ali';
            $logMessages['ali'] = $idList;
        }
        if ($lotItemIds) {
            $ids = [];
            foreach ($lotItemIds as $lotItemId) {
                $ids[] = $db->SqlVariable($lotItemId);
            }
            $idList = implode(',', $ids);
            $conditions[] = "li.id IN ({$idList})";
            $conditions[] = 'li.active';
            $joinKeys[] = 'ali';
            $joinKeys[] = 'li';
            $logMessages['li'] = $idList;
        }
        if ($accountIds) {
            $ids = [];
            foreach ($accountIds as $accountId) {
                $ids[] = $db->SqlVariable($accountId);
            }
            $idList = implode(',', $ids);
            $conditions[] = "acc.id IN ({$idList})";
            $conditions[] = 'acc.active';
            $joinKeys[] = 'ali';
            $joinKeys[] = 'acc';
            $logMessages['acc'] = $idList;
        }
        $joins = array_intersect_key($allJoins, array_flip(array_unique($joinKeys)));
        $join = implode(' ', $joins);
        $where = 'WHERE ' . implode(' AND ', $conditions);

        $query = 'UPDATE auction_lot_item_cache alic'
            . ' ' . $join
            . ' SET alic.seo_url = NULL,'
            . ' alic.modified_by = ' . $this->escape($editorUserId)
            . ' ' . $where;
        try {
            log_trace('alic.seo_url dropping for records' . composeSuffix($logMessages));
            log_traceQuery($query);
            $db->NonQuery($query);
        } catch (QMySqliDatabaseException $e) {
            log_error($e->getCode() . ' - ' . $e->getMessage());
        }
    }

    /**
     * Drop alic.seo_url for all cache records by account
     * @param int $accountId
     * @param int $editorUserId
     */
    public function dropByAccountId(int $accountId, int $editorUserId): void
    {
        $db = $this->getDb();
        $query = 'UPDATE auction_lot_item_cache alic'
            . ' JOIN auction_lot_item ali ON ali.id = alic.auction_lot_item_id'
            . ' JOIN account acc ON acc.id = ali.account_id'
            . ' SET alic.seo_url = NULL,'
            . ' alic.modified_by = ' . $this->escape($editorUserId)
            . ' WHERE acc.id = ' . $this->escape($accountId)
            . ' AND alic.seo_url IS NOT NULL';
        try {
            $db->NonQuery($query);
        } catch (QMySqliDatabaseException $e) {
            log_error($e->getCode() . ' - ' . $e->getMessage());
        }
    }
}
