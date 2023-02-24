<?php
/**
 * Class implements functionality for caching auction lot aggregated values for auction in database.
 * SAM-1320: YBL - Admin view - Max bid total
 * SAM-1652: Admin dashboard
 * SAM-4691: Refactor AuctionDbCacheManager
 *
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @since           Nov 17, 2012
 * @copyright       Copyright 2018 by Bidpath, Inc. All rights reserved.
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache;

use AuctionCache;
use Exception;
use Laminas\Filter\Word\UnderscoreToCamelCase;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Query\AuctionBidderQueryBuilderHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Infrastructure\OutputBuffer\OutputBufferCreateTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionCache\AuctionCacheWriteRepositoryAwareTrait;

/**
 * Class AuctionDbCacheManager
 * @package Sam\Auction\Cache
 */
class AuctionDbCacheManager extends CustomizableClass
{
    use AuctionBidderQueryBuilderHelperCreateTrait;
    use AuctionCacheLoaderAwareTrait;
    use AuctionCacheWriteRepositoryAwareTrait;
    use AuctionReadRepositoryCreateTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;
    use EntityFactoryCreateTrait;
    use OutputBufferCreateTrait;

    public const ACCOUNT_ID = 'account_id';
    public const BIDDERS = 'bidders';
    public const BIDDERS_APPROVED = 'bidders_approved';
    public const BIDDERS_BIDDING = 'bidders_bidding';
    public const BIDDERS_WINNING = 'bidders_winning';
    public const BIDS = 'bids';
    public const LOTS_ABOVE_HIGH_ESTIMATE = 'lots_above_high_estimate';
    public const LOTS_SOLD = 'lots_sold';
    public const LOTS_WITH_BIDS = 'lots_with_bids';
    public const TOTAL_ACTIVE_LOTS = 'total_active_lots';
    public const TOTAL_BID = 'total_bid';
    public const TOTAL_BUYERS_PREMIUM = 'total_buyers_premium';
    public const TOTAL_FEES = 'total_fees';
    public const TOTAL_HAMMER_PRICE = 'total_hammer_price';
    public const TOTAL_HAMMER_PRICE_INTERNET = 'total_hammer_price_internet';
    public const TOTAL_HIGH_ESTIMATE = 'total_high_estimate';
    public const TOTAL_LOTS = 'total_lots';
    public const TOTAL_LOW_ESTIMATE = 'total_low_estimate';
    public const TOTAL_MAX_BID = 'total_max_bid';
    public const TOTAL_PAID_BUYERS_PREMIUM = 'total_paid_buyers_premium';
    public const TOTAL_PAID_FEES = 'total_paid_fees';
    public const TOTAL_PAID_HAMMER_PRICE = 'total_paid_hammer_price';
    public const TOTAL_PAID_TAX = 'total_paid_tax';
    public const TOTAL_RESERVE = 'total_reserve';
    public const TOTAL_RESERVE_MET = 'total_reserve_met';
    public const TOTAL_STARTING_BID = 'total_starting_bid';
    public const TOTAL_VIEWS = 'total_views';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = self::_new(self::class);
        return $instance;
    }

    /**
     * Refresh cached values
     *
     * @param AuctionCache $auctionCache
     * @param int $editorUserId
     * @return AuctionCache
     */
    public function refresh(AuctionCache $auctionCache, int $editorUserId): AuctionCache
    {
        $values = $this->detectValues([$auctionCache->AuctionId]);
        $auctionCaches = $this->update([$auctionCache], $editorUserId, $values);
        return $auctionCaches[0];
    }

    /**
     * @param int $auctionId
     * @param int $editorUserId
     * @return AuctionCache
     */
    public function refreshByAuctionId(int $auctionId, int $editorUserId): AuctionCache
    {
        $auctionCache = $this->getAuctionCacheLoader()->load($auctionId);
        if (!$auctionCache) {
            $auctionCache = $this->createEntityFactory()->auctionCache();
            $auctionCache->AuctionId = $auctionId;
        }
        $auctionCache = $this->refresh($auctionCache, $editorUserId);
        return $auctionCache;
    }

    /**
     * Refresh cached values for all auctions
     *
     * @param int $accountId
     * @param int $editorUserId
     * @param bool $processOutput Output processing info
     */
    public function refreshAllForAccount(int $accountId, int $editorUserId, bool $processOutput = false): void
    {
        if ($processOutput) {
            $this->createOutputBuffer()->endFlush();
        }
        $auctionRepository = $this->createAuctionReadRepository()
            ->filterAccountId($accountId)
            ->filterAuctionStatusId(Constants\Auction::$availableAuctionStatuses);
        $offset = 0;
        $limit = 100;
        $count = $auctionRepository->count();
        log_debug(
            'Started refreshing db cached values for auctions.'
            . composeSuffix(['Auction count' => $count])
        );
        while ($offset < $count) {
            $auctions = $auctionRepository->offset($offset)
                ->limit($limit)
                ->loadEntities();
            foreach ($auctions as $auction) {
                $auctionCache = $this->getAuctionCacheLoader()->load($auction->Id);
                if (!$auctionCache) {
                    $auctionCache = $this->createEntityFactory()->auctionCache();
                    $auctionCache->AuctionId = $auction->Id;
                }
                $this->refresh($auctionCache, $editorUserId);
            }
            if ($processOutput) {
                echo 'Auctions cached: ' . ($offset + count($auctions))
                    . " (of {$count}) for account id: {$accountId}\n";
            }
            $offset += $limit;
        }
        log_debug(
            'Finished refreshing db cached values for auctions.'
            . composeSuffix(['Auction count' => $count])
        );
    }

    /**
     * Clear value of auction_cache.gcal_changed_on
     *
     * @param int[]|null $auctionIds null means drop value for all auctions
     * @param int $editorUserId
     */
    public function dropGcalChangedOn(?array $auctionIds, int $editorUserId): void
    {
        $conditions = [];
        if ($auctionIds !== null) {
            if (empty($auctionIds)) {
                return;
            }
            $sqlClearAuctionIds = [];
            foreach ($auctionIds as $auctionId) {
                $sqlClearAuctionIds[] = $this->escape($auctionId);
            }
            $conditions[] = "ac.auction_id IN (" . implode(',', $sqlClearAuctionIds) . ")";
        }
        $whereClause = !empty($conditions) ? " WHERE " . implode(' AND ', $conditions) : '';
        $query = "SELECT `ac`.`gcal_changed_on` AS `gcal_changed_on` FROM auction_cache ac" . $whereClause;
        $this->query($query);
        $row = $this->fetchAssoc();
        $gcalChangedOn = $row['gcal_changed_on'] ?? null;
        if ($gcalChangedOn) {
            $query = "UPDATE auction_cache ac"
                . " SET ac.gcal_changed_on = NULL,"
                . " ac.modified_by = " . $this->escape($editorUserId)
                . $whereClause;
            $this->nonQuery($query);
        }
    }

    /**
     * Return calculated aggregated values. Result with array indexed by auction ids.
     * @param int[] $auctionIds
     * @param string[] $columns set fields you are interested in
     * @return array[auction.id][]
     */
    public function loadAggregatedValues(array $auctionIds, array $columns): array
    {
        $query = $this->buildQuery($auctionIds, $columns);
        $this->query($query);
        $values = array_fill_keys($auctionIds, array_fill_keys($columns, null));
        while ($row = $this->fetchAssoc()) {
            $auctionId = (int)$row['auction_id'];
            foreach ($row as $column => $value) {
                $values[$auctionId][$column] = $value;
            }
        }
        return $values;
    }

    // --- Protected / Private methods --------------------------------------

    /**
     * Return query for calculating aggregated values
     * @param int[] $auctionIds
     * @param string[] $columns set fields you are interested in
     * @return string
     */
    protected function buildQuery(array $auctionIds, array $columns): string
    {
        $n = "\n";
        $usActive = Constants\User::US_ACTIVE;
        $timed = Constants\Auction::TIMED;
        $approvedBidderWhereClause = $this->createAuctionBidderQueryBuilderHelper()->makeApprovedBidderWhereClause('ab');
        // TB (Aug 2018): ignore user.flag for auction_cache value calculation
        // $flagBlk = Constants\User::FLAG_BLOCK;
        // $flagNaa = Constants\User::FLAG_NOAUCTIONAPPROVAL;
        $selects = [
            self::ACCOUNT_ID => 'a.account_id',
            self::TOTAL_BID => 'SUM(alic.current_bid)',
            self::TOTAL_MAX_BID => 'SUM(alic.current_max_bid)',
            self::TOTAL_STARTING_BID => 'SUM(alic.starting_bid_normalized)',
            self::TOTAL_LOW_ESTIMATE => 'SUM(li.low_estimate)',
            self::TOTAL_HIGH_ESTIMATE => 'SUM(li.high_estimate)',
            self::TOTAL_RESERVE => 'SUM(li.reserve_price)',
            self::TOTAL_RESERVE_MET => 'SUM(IF(!a.reverse AND alic.current_bid >= IFNULL(li.reserve_price, 0), alic.current_bid, 0))',
            self::TOTAL_HAMMER_PRICE => 'SUM(IF(li.auction_id = a.id, li.hammer_price, 0))',
            self::TOTAL_HAMMER_PRICE_INTERNET => 'SUM(IF(li.auction_id = a.id AND li.internet_bid = 1, li.hammer_price, 0))',
            self::TOTAL_VIEWS => 'SUM(alic.view_count)',
            self::TOTAL_LOTS => 'COUNT(ali.id)',
            self::TOTAL_ACTIVE_LOTS => "SUM(IF(ali.lot_status_id = {$usActive}, 1, 0))",
            self::LOTS_WITH_BIDS => 'SUM(IF(alic.current_bid IS NULL, 0, 1))',
            self::LOTS_ABOVE_HIGH_ESTIMATE => 'SUM(IF(!a.reverse AND alic.current_bid >= li.high_estimate, 1, 0))',
            self::LOTS_SOLD => 'SUM(IF(li.hammer_price IS NOT NULL, 1, 0))',
            self::BIDDERS => "(SELECT COUNT(1) FROM auction_bidder ab " . $n .
                "INNER JOIN `user` u ON u.id = ab.user_id AND u.user_status_id = {$usActive} " . $n . /*"AND u.flag != {$flagBlk} " . */
                "WHERE ab.auction_id = a.id)",
            self::BIDDERS_APPROVED => "(SELECT COUNT(1) FROM auction_bidder ab " . $n .
                "INNER JOIN user u ON u.id = ab.user_id AND u.user_status_id = {$usActive} " . $n . /*"AND u.flag NOT IN ({$flagBlk}, {$flagNaa}) " . */
                "WHERE {$approvedBidderWhereClause} AND ab.auction_id = a.id)",
            self::BIDDERS_WINNING =>
                "(SELECT COUNT(DISTINCT li.winning_bidder_id) FROM lot_item li " . $n .
                "INNER JOIN `user` u ON u.id = li.winning_bidder_id AND u.user_status_id = {$usActive} " . $n . /*"AND u.flag != {$flagBlk} " . */
                "WHERE li.auction_id = a.id AND li.hammer_price IS NOT NULL)",
            self::BIDDERS_BIDDING => "IF(a.auction_type = '{$timed}', " . $n .
                "(SELECT COUNT(DISTINCT bt.user_id) FROM bid_transaction bt " . $n .
                "INNER JOIN `user` u ON u.id = bt.user_id AND u.user_status_id = {$usActive} " . $n . /*"AND u.flag != {$flagBlk} " . */
                "WHERE bt.auction_id = a.id), " . $n .
                "(SELECT COUNT(DISTINCT ab.user_id) FROM absentee_bid ab " . $n .
                "INNER JOIN `user` u ON u.id = ab.user_id AND u.user_status_id = {$usActive} " . $n . /*"AND u.flag != {$flagBlk} " . */
                "WHERE ab.auction_id = a.id))",
            self::BIDS => "IF(a.auction_type = '{$timed}', " . $n .
                "(SELECT COUNT(1) FROM bid_transaction bt " . $n .
                "INNER JOIN `user` u ON u.id = bt.user_id AND u.user_status_id = {$usActive} " . $n . /*"AND u.flag != {$flagBlk} " . */
                "WHERE bt.auction_id = a.id AND bt.bid_status IN ('" . Constants\BidTransaction::BS_WINNER . "', '" . Constants\BidTransaction::BS_LOOSER . "') AND !bt.deleted), " . $n .
                "(SELECT COUNT(1) FROM absentee_bid ab " . $n .
                "INNER JOIN `user` u ON u.id = ab.user_id AND u.user_status_id = {$usActive} " . $n . /*"AND u.flag != {$flagBlk} " . */
                "WHERE ab.auction_id = a.id))",
        ];
        $clearSqlAuctionIds = '';
        foreach ($auctionIds as $auctionId) {
            $clearSqlAuctionIds .= $this->escape($auctionId) . ',';
        }
        $clearSqlAuctionIds = '(' . rtrim($clearSqlAuctionIds, ',') . ')';
        $columnList = '';
        foreach ($columns as $key) {
            $columnList .= $selects[$key] . ' AS `' . $key . '`,' . $n;
        }
        $columnList = rtrim($columnList, ',' . $n) . $n;
        // GROUP BY - Not necessary in new mysql versions, but for v5.0.51a it fixes
        // "Mysqli Error: Mixing of GROUP columns (MIN(),MAX(),COUNT(),...) with no GROUP columns is illegal if there is no GROUP BY clause"
        $availableLotStatusList = implode(',', Constants\Lot::$availableLotStatuses);
        $query = <<<SQL
SELECT 
    a.id AS auction_id, 
    {$columnList}
FROM auction AS a 
LEFT JOIN auction_lot_item AS ali ON ali.auction_id = a.id 
LEFT JOIN lot_item AS li ON ali.lot_item_id = li.id 
LEFT JOIN auction_lot_item_cache AS alic ON alic.auction_lot_item_id=ali.id 
WHERE 
    (li.active IS NULL OR li.active = TRUE) 
    AND (ali.lot_status_id IS NULL
        OR ali.lot_status_id IN ({$availableLotStatusList})) 
    AND a.id IN {$clearSqlAuctionIds} 
GROUP BY a.id
SQL;
        return $query;
    }

    /**
     * Update values for auction_cache entry
     *
     * @param AuctionCache[] $auctionCaches
     * @param int $editorUserId
     * @param array $values [auction.id][]
     * - float 'total_bid'
     * - float 'total_max_bid'
     * - float 'total_starting_bid'
     * - float 'total_low_estimate'
     * - float 'total_high_estimate'
     * - float 'total_reserve'
     * - float 'total_reserve_met'
     * - float 'total_hammer_price'
     * - float 'total_hammer_price_internet'
     * - float 'total_paid_hammer_price'
     * - float 'total_paid_buyers_premium'
     * - float 'total_paid_tax'
     * - float 'total_paid_fees'
     * - int 'total_views'
     * - int 'total_lots'
     * - int 'lots_with_bids'
     * - int 'lots_above_high_estimate'
     * - int 'lots_sold'
     * - int 'bidders'
     * - int 'bidders_approved'
     * - int 'bidders_bidding'
     * - int 'bidders_winning'
     * - int 'bids'
     * @return AuctionCache[] - cache entry
     */
    public function update(array $auctionCaches, int $editorUserId, array $values = []): array
    {
        $underscoreToCamelCase = new UnderscoreToCamelCase();
        foreach ($auctionCaches as $auctionCache) {
            $auctionId = $auctionCache->AuctionId;
            if (!isset($values[$auctionId])) {
                continue;
            }
            $auctionValues = $values[$auctionId];
            foreach ($auctionValues as $key => $value) {
                $property = $underscoreToCamelCase->filter($key);
                try {
                    $auctionCache->$property = $value;
                } catch (Exception $e) {
                }
            }

            $auctionCache->CalculatedOn = $this->getCurrentDateUtc();
            $this->getAuctionCacheWriteRepository()->saveWithModifier($auctionCache, $editorUserId);
            log_debug(
                "Auction values in db cache updated"
                . composeSuffix(['a' => $auctionCache->AuctionId] + $auctionValues)
            );
        }
        return $auctionCaches;
    }

    /**
     * Return all values for auction, calculated in one query
     *
     * @param int[] $auctionIds auction.id
     * @return array[auction.id][]
     */
    public function detectValues(array $auctionIds): array
    {
        $columns = [
            self::ACCOUNT_ID,
            self::TOTAL_BID,
            self::TOTAL_MAX_BID,
            self::TOTAL_STARTING_BID,
            self::TOTAL_LOW_ESTIMATE,
            self::TOTAL_HIGH_ESTIMATE,
            self::TOTAL_RESERVE,
            self::TOTAL_RESERVE_MET,
            self::TOTAL_HAMMER_PRICE,
            self::TOTAL_HAMMER_PRICE_INTERNET,
            self::TOTAL_VIEWS,
            self::TOTAL_LOTS,
            self::TOTAL_ACTIVE_LOTS,
            self::LOTS_WITH_BIDS,
            self::LOTS_ABOVE_HIGH_ESTIMATE,
            self::LOTS_SOLD,
            self::BIDDERS,
            self::BIDDERS_APPROVED,
            self::BIDDERS_BIDDING,
            self::BIDDERS_WINNING,
            self::BIDS,
        ];
        $values = $this->loadAggregatedValues($auctionIds, $columns);

        // Get invoice values
        $invoiceValues = $this->detectInvoiceValues($auctionIds);
        foreach ($values as $auctionId => $value) {
            $values[$auctionId] = array_merge($value, $invoiceValues[$auctionId]);
        }

        return $values;
    }

    /**
     * Return values from invoice
     *
     * @param array $auctionIds
     * @return array[auction.id][]
     * - float 'total_paid_hammer_price'
     * - float 'total_paid_buyers_premium'
     * - float 'total_buyers_premium'
     * - float 'total_paid_tax'
     * - float 'total_paid_fees'
     * - float 'total_fees'
     */
    protected function detectInvoiceValues(array $auctionIds): array
    {
        $valuesDefined = [
            self::TOTAL_PAID_HAMMER_PRICE => null,
            self::TOTAL_PAID_BUYERS_PREMIUM => null,
            self::TOTAL_PAID_TAX => null,
            self::TOTAL_PAID_FEES => null,
            self::TOTAL_BUYERS_PREMIUM => null,
            self::TOTAL_FEES => null,
        ];
        $values = [];
        $sqlClearAuctionIds = '';
        foreach ($auctionIds as $auctionId) {
            $sqlClearAuctionIds .= $this->escape($auctionId) . ',';
            $values[$auctionId] = $valuesDefined;
        }
        $sqlClearAuctionIds = '(' . rtrim($sqlClearAuctionIds, ',') . ')';

        $isPaid = Constants\Invoice::IS_PAID;
        $invoiceStatusList = implode(',', Constants\Invoice::$availableInvoiceStatuses);

        $taHpBp = Constants\User::TAX_HP_BP;
        $taHp = Constants\User::TAX_HP;
        $taBp = Constants\User::TAX_BP;
        $tdsLegacy = Constants\Invoice::TDS_LEGACY;

        // get invoice_item related values
        $query = <<<SQL
SELECT
    ii.auction_id AS auction_id,
    @total_hp_paid := SUM(IF(i.invoice_status_id = {$isPaid}, ii.hammer_price, 0)) AS total_hp_paid,
    @total_bp_paid := SUM(IF(i.invoice_status_id = {$isPaid}, ii.buyers_premium, 0)) AS total_bp_paid,
    @total_bp := SUM(IFNULL(ii.buyers_premium,0)) AS total_bp,
    @total_tax_paid := SUM(
        IF(i.invoice_status_id = {$isPaid},
            IF(i.tax_designation = {$tdsLegacy},    
                CASE ii.tax_application
                    WHEN {$taHpBp} THEN (ii.hammer_price + ii.buyers_premium) * (ii.sales_tax / 100)
                    WHEN {$taHp} THEN (ii.hammer_price) * (ii.sales_tax / 100)
                    WHEN {$taBp} THEN (ii.buyers_premium) * (ii.sales_tax / 100)
                    ELSE 0
                END,
                ii.hp_tax_amount + ii.bp_tax_amount
            ),
            0
        )
    ) AS total_tax_paid
FROM invoice i
INNER JOIN invoice_item ii ON i.id = ii.invoice_id AND ii.active
WHERE
    i.invoice_status_id IN ({$invoiceStatusList})
    AND ii.auction_id IN {$sqlClearAuctionIds} 
GROUP BY ii.auction_id;
SQL;
        $this->query($query);
        while ($row = $this->fetchAssoc()) {
            $id = (int)$row['auction_id'];
            $values[$id][self::TOTAL_PAID_HAMMER_PRICE] = $row['total_hp_paid'];
            $values[$id][self::TOTAL_PAID_BUYERS_PREMIUM] = $row['total_bp_paid'];
            $values[$id][self::TOTAL_BUYERS_PREMIUM] = $row['total_bp'];
            $values[$id][self::TOTAL_PAID_TAX] = $row['total_tax_paid'];
        }

        // get invoice related values
        foreach ($auctionIds as $auctionId) {
            $sqlClearAuctionId = $this->escape($auctionId);
            $query = <<<SQL
SELECT 
    @total_fees_paid := SUM(IF(i.invoice_status_id = {$isPaid}, i.shipping_fees, 0) + 
    IF(i.invoice_status_id = {$isPaid}, i.extra_charges, 0)) AS total_fees_paid,
    @total_fees := SUM(IFNULL(i.shipping_fees,0) +
    IFNULL(i.extra_charges,0)) AS total_fees
FROM `invoice` i
INNER JOIN (
    SELECT i.id FROM invoice i
    INNER JOIN invoice_item ii ON i.id = ii.invoice_id AND ii.active
    WHERE
        i.invoice_status_id IN ({$invoiceStatusList})
        AND ii.auction_id = {$sqlClearAuctionId}
    GROUP BY i.id
) i2 ON i2.id=i.id
SQL;

            $this->query($query);
            while ($row = $this->fetchAssoc()) {
                $values[$auctionId][self::TOTAL_PAID_FEES] = $row['total_fees_paid'];
                $values[$auctionId][self::TOTAL_FEES] = $row['total_fees'];
            }
        }
        return $values;
    }
}
