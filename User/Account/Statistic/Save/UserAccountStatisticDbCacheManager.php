<?php
/**
 * SAM-5259: User account stats invalidation
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/8/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Account\Statistic\Save;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Laminas\Filter\Word\UnderscoreToCamelCase;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserAccountStats\UserAccountStatsReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserLogin\UserLoginReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserWatchlist\UserWatchlistReadRepository;
use Sam\Storage\ReadRepository\Entity\UserWatchlist\UserWatchlistReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\UserAccountStats\UserAccountStatsWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserAccountStatsCurrency\UserAccountStatsCurrencyWriteRepositoryAwareTrait;
use UserAccountStats;

/**
 * Class UserAccountStatisticDbCacheManager
 * @package Sam\User\Account\Statistic\Save
 */
class UserAccountStatisticDbCacheManager extends CustomizableClass
{
    use AbsenteeBidReadRepositoryCreateTrait;
    use AuctionReadRepositoryCreateTrait;
    use AuctionBidderReadRepositoryCreateTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use BidTransactionReadRepositoryCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;
    use LotItemReadRepositoryCreateTrait;
    use UserAccountStatsCurrencyWriteRepositoryAwareTrait;
    use UserAccountStatsReadRepositoryCreateTrait;
    use UserAccountStatsWriteRepositoryAwareTrait;
    use UserLoginReadRepositoryCreateTrait;
    use UserWatchlistReadRepositoryCreateTrait;

    protected array $currencyFields = [
        'lots_bid_on_amt',
        'lots_consigned_sold_amt',
        'lots_won_amt',
        'watchlist_items_bid_amt',
        'watchlist_items_won_amt',
    ];

    protected array $uniqueUsers = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    #[Pure] public function countUniqueUsers(): int
    {
        return count($this->uniqueUsers);
    }

    /**
     * @param int $editorUserId
     * @param array $userIds
     * @param array $accountIds
     */
    public function invalidate(int $editorUserId, array $userIds = [], array $accountIds = []): void
    {
        $userIds = ArrayCast::makeIntArray($userIds);
        $accountIds = ArrayCast::makeIntArray($accountIds);

        $conditions = [
            "uas.modified_on IS NOT NULL"
        ];

        if ($userIds) {
            $ids = [];
            foreach ($userIds as $auctionId) {
                $ids[] = $this->escape($auctionId);
            }
            $idList = implode(',', $ids);
            $conditions[] = "uas.user_id IN ({$idList})";
        }

        if ($accountIds) {
            $ids = [];
            foreach ($accountIds as $accountId) {
                $ids[] = $this->escape($accountId);
            }
            $idList = implode(',', $ids);
            $conditions[] = "uas.account_id IN ({$idList})";
        }

        $whereClause = " WHERE " . implode(' AND ', $conditions);
        $query = "SELECT uas.modified_on AS `modified_on` FROM user_account_stats uas" . $whereClause;
        $this->query($query);
        $row = $this->fetchAssoc();
        if ($row['modified_on']) {
            $query = "UPDATE user_account_stats uas"
                . " SET uas.modified_on = NULL,"
                . " uas.modified_by = " . $this->escape($editorUserId)
                . $whereClause;
            $this->nonQuery($query);
        }
    }

    /**
     * @param int $editorUserId
     * @param int|null $userId
     * @param int|null $maxExecTime
     * @param int|null $limit
     * @return int
     */
    public function refreshAll(int $editorUserId, ?int $userId = null, ?int $maxExecTime = null, ?int $limit = null): int
    {
        $execStartTime = time();
        $count = 0;

        $repo = $this->createUserAccountStatsReadRepository();
        // Processing user account statistics which were not calculated yet or expired from a moment of the last calculation
        $repo
            ->inlineCondition('uas.calculated_on IS NULL OR (uas.expired_on IS NOT NULL AND uas.expired_on >= uas.calculated_on)')
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->orderByCalculatedOn()
            ->orderByExpiredOn()
            ->orderByUserId()
            ->orderByAccountId()
            ->limit($limit)
            ->setChunkSize(($maxExecTime || $limit) ? 1 : 200);
        if ($userId) {
            $repo->filterUserId($userId);
        }

        while ($userAccountStats = $repo->loadEntities()) {
            $this->refreshArray($userAccountStats, $editorUserId, 10);
            $count += count($userAccountStats);

            // If we run from cron script, then we have execution time limitation
            if (
                ($maxExecTime
                    && time() > ($execStartTime + $maxExecTime))
                || ($limit
                    && $count === $limit)
            ) {
                log_debug("Refreshed user account statistics of {$count} users");
                return $count;
            }
        }

        log_debug("Refreshed user account statistics of {$count} users");
        return $count;
    }

    /**
     * Refresh cached values
     * @param UserAccountStats[] $stats
     * @param int $editorUserId
     * @param int|null $chunkSize - process array by portions with the size, null - whole array at once
     * @return void
     */
    public function refreshArray(array $stats, int $editorUserId, ?int $chunkSize = null): void
    {
        if (empty($stats)) {
            return;
        }
        if ($chunkSize === null) {
            $chunkSize = count($stats);
        }
        for ($i = 0, $count = count($stats); $i < $count; $i += $chunkSize) {
            $chunkedStats = array_slice($stats, $i, $chunkSize);
            $userPerAccountIds = [];
            foreach ($chunkedStats as $stat) {
                $userPerAccountIds[$stat->AccountId][] = $stat->UserId;
                $this->identifyUniqueUsers($stat->UserId);
            }
            $values = $this->getValues($userPerAccountIds);
            $this->update($chunkedStats, $values, $editorUserId);
        }
    }

    /**
     * @param UserAccountStats[]|array $stats
     * @param array $values [account.id][user.id]['approved_auctions_num', 'last_date_bid', ...]
     * @param int $editorUserId
     * @return UserAccountStats[] - cache entry
     */
    protected function update(array $stats, array $values, int $editorUserId): array
    {
        $currencySign = UserAccountStatisticCurrencyDbCacheManager::new()->getPrimaryCurrencySign();
        $underscoreToCamelCase = new UnderscoreToCamelCase();
        foreach ($stats as $stat) {
            $accountId = $stat->AccountId;
            $userId = $stat->UserId;
            $statCurrency = UserAccountStatisticProducer::new()->loadUserAccountStatsCurrencyOrCreate(
                $userId,
                $accountId,
                $editorUserId,
                $currencySign
            );
            if (!isset($values[$accountId][$userId])) {
                continue;
            }
            $statValues = $values[$accountId][$userId];
            foreach ($statValues as $key => $value) {
                $property = $underscoreToCamelCase->filter($key);

                if (in_array($key, $this->currencyFields, true)) {
                    $statCurrency->$property = $value;
                    continue;
                }

                try {
                    if (is_scalar($stat->$property)) {
                        $stat->$property = $value;
                    }
                } catch (InvalidArgumentException $e) {
                }
                $stat->CalculatedOn = $this->getCurrentDateUtc();
                $stat->ExpiredOn = null;
            }
            $this->getUserAccountStatsWriteRepository()->saveWithModifier($stat, $editorUserId);
            $this->getUserAccountStatsCurrencyWriteRepository()->saveWithModifier($statCurrency, $editorUserId);

            log_debug(
                "User account statistics updated"
                . composeSuffix(['u' => $userId, 'values' => $statValues])
            );
        }
        return $stats;
    }

    protected function identifyUniqueUsers(int $userId): void
    {
        if (!in_array($userId, $this->uniqueUsers)) {
            $this->uniqueUsers[] = $userId;
        }
    }

    /**
     * Return all values for auction, calculated in one query
     *
     * @param array<int, int[]> $userPerAccountIds [account.id][user.id]
     * @return array[account.id][user.id][]
     */
    protected function getValues(array $userPerAccountIds): array
    {
        $columns = [
            'approved_auctions_num',
            'auctions_won_num',
            'last_date_bid',
            'last_date_auction_registered',
            'last_date_logged_in',
            'last_date_won',
            'lots_bid_on_num',
            'lots_consigned_num',
            'lots_consigned_sold_num',
            'lots_won_num',
            'participated_auctions_num',
            'registered_auctions_num',
            'watchlist_items_bid_num',
            'watchlist_items_num',
            'watchlist_items_won_num',
            // Currency stats
            'lots_bid_on_amt',
            'lots_consigned_sold_amt',
            'lots_won_amt',
            'watchlist_items_bid_amt',
            'watchlist_items_won_amt',
        ];
        $values = [];
        foreach ($userPerAccountIds as $accountId => $userIds) {
            $accountId = (int)$accountId;
            $values[$accountId] = $this->getAggregatedValues($accountId, $userIds, $columns);
        }
        return $values;
    }

    /**
     * Return calculated aggregated values
     * @param int $accountId
     * @param int[] $userIds
     * @param array $columns set fields you are interested in
     * @return array[user.id][column] = value
     */
    public function getAggregatedValues(int $accountId, array $userIds, array $columns): array
    {
        $query = $this->getAggregatedQuery($accountId, $userIds, $columns);
        $this->query($query);
        $values = [];
        while ($row = $this->fetchAssoc()) {
            $row = $this->postCalculation($row);
            $userId = (int)$row['user_id'];
            foreach ($row as $column => $value) {
                $values[$userId][$column] = $value;
            }
        }
        return $values;
    }

    protected function clearSqlUserIds(array $userIds): string
    {
        $clearSqlUserIds = '';
        foreach ($userIds as $userId) {
            $clearSqlUserIds .= $this->escape($userId) . ',';
        }
        return '(' . rtrim($clearSqlUserIds, ',') . ')';
    }

    /**
     * Return query for calculating aggregated values
     * @param int $accountId
     * @param int[] $userIds
     * @param array $columns set fields you are interested in
     * @return string
     */
    protected function getAggregatedQuery(int $accountId, array $userIds, array $columns): string
    {
        $userIds = ArrayCast::makeIntArray($userIds);
        $clearSqlUserIds = $this->clearSqlUserIds($userIds);
        $n = "\n";

        $userAccountStatisticCurrencyDbCacheManager = UserAccountStatisticCurrencyDbCacheManager::new();
        $selects['lots_bid_on_amt'] = $userAccountStatisticCurrencyDbCacheManager->buildQueryLotsBidOnAmt($accountId);
        $selects['lots_consigned_sold_amt'] = $userAccountStatisticCurrencyDbCacheManager->buildQueryLotsConsignedSoldAmt($accountId);
        $selects['lots_won_amt'] = $userAccountStatisticCurrencyDbCacheManager->buildQueryLotsWonAmt($accountId);
        $selects['watchlist_items_bid_amt'] = $userAccountStatisticCurrencyDbCacheManager->buildQueryWatchlistItemsBidAmt($accountId);
        $selects['watchlist_items_won_amt'] = $userAccountStatisticCurrencyDbCacheManager->buildQueryWatchlistItemsWonAmt($accountId);

        $selects['approved_auctions_num'] = $this->buildQueryApprovedAuctionsNum($accountId);
        $selects['auctions_won_num'] = $this->buildQueryAuctionsWonNum($accountId);
        $selects['last_date_auction_registered'] = $this->buildQueryLastDateAuctionRegistered($accountId);
        $selects['last_date_bid'] = $this->buildQueryLastDateBid($accountId);
        $selects['last_date_logged_in'] = $this->buildQueryLastDateLoggedIn();
        $selects['last_date_won'] = $this->buildQueryLastDateWon($accountId);
        $selects['lots_bid_on_num'] = $this->buildQueryLotsBidOnNum($accountId);
        $selects['lots_consigned_num'] = $this->buildQueryLotsConsignedNum($accountId);
        $selects['lots_consigned_sold_num'] = $this->buildQueryLotsConsignedSoldNum($accountId);
        $selects['lots_won_num'] = $this->buildQueryLotsWonNum($accountId);
        $selects['participated_auctions_num'] = $this->buildQueryParticipatedAuctionsNum($accountId);
        $selects['registered_auctions_num'] = $this->buildQueryRegisteredAuctionsNum($accountId);
        $selects['watchlist_items_bid_num'] = $this->buildQueryWatchlistItemsBidNum($accountId);
        $selects['watchlist_items_num'] = $this->buildQueryWatchlistItemsNum($accountId);
        $selects['watchlist_items_won_num'] = $this->buildQueryWatchlistItemsWonNum($accountId);

        $columnList = '';
        foreach ($columns as $key) {
            $columnList .= '(' . $selects[$key] . ') AS `' . $key . '`,' . $n;
        }
        $columnList = rtrim($columnList, ',' . $n) . $n;
        $usActive = Constants\User::US_ACTIVE;
        $query = <<<SQL
SELECT 
    u.id AS user_id,
    {$columnList}
FROM user AS u 
WHERE 
    u.user_status_id = {$usActive} 
    AND u.id IN {$clearSqlUserIds} 
GROUP BY u.id
SQL;
        return $query;
    }

    /**
     * Calculate additional values based on fetched values from db
     * @param array $row
     * @return array
     */
    protected function postCalculation(array $row): array
    {
        $row['lots_won_perc'] = 0;
        if ($row['lots_bid_on_num'] > 0) {
            $row['lots_won_perc'] = ($row['lots_won_num'] / $row['lots_bid_on_num']) * 100;
        }
        if ($row['registered_auctions_num'] > 0) {
            $row['auctions_won_perc'] = ($row['auctions_won_num'] / $row['registered_auctions_num']) * 100;
            $row['participated_auctions_perc'] = ($row['participated_auctions_num'] / $row['registered_auctions_num']) * 100;
        }
        $row['watchlist_items_bid_perc'] = 0;
        $row['watchlist_items_won_perc'] = 0;
        if ($row['watchlist_items_num'] > 0) {
            $row['watchlist_items_bid_perc'] = ($row['watchlist_items_bid_num'] / $row['watchlist_items_num']) * 100;
            $row['watchlist_items_won_perc'] = ($row['watchlist_items_won_num'] / $row['watchlist_items_num']) * 100;
        }
        return $row;
    }

    /**
     * @param int $accountId
     * @return string
     */
    protected function buildQueryAuctionsWonNum(int $accountId): string
    {
        $subqueryHasWonLotInAuction = $this->createLotItemReadRepository()
            ->skipHammerPrice(null)
            ->joinAuctionLotItem()
            ->inlineCondition('li.winning_bidder_id = aub.user_id AND ali.auction_id = aub.auction_id')
            ->getCountQuery();

        return $this->prepareAuctionBidderRepository($accountId)
            ->inlineCondition("({$subqueryHasWonLotInAuction}) > 0")
            ->getCountQuery();
    }

    protected function buildQueryApprovedAuctionsNum(int $accountId): string
    {
        return $this->prepareAuctionBidderRepository($accountId, Constants\Auction::$notDeletedAuctionStatuses)
            ->filterApproved(true)
            ->filterBidderNumFilled()
            ->getCountQuery();
    }

    protected function buildQueryLastDateAuctionRegistered(int $accountId): string
    {
        return $this->prepareAuctionBidderRepository($accountId)
            ->select(['MAX(aub.registered_on)'])
            ->getResultQuery();
    }

    protected function buildQueryLastDateBid(int $accountId): string
    {
        $subqueryAbsenteeBid = $this->createAbsenteeBidReadRepository()
            ->select(['MAX(ab.created_on) AS max_bid_date'])
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->joinAuctionFilterAccountId($accountId)
            ->filterMaxBidGreater(0)
            ->inlineCondition('ab.user_id = u.id')
            ->getResultQuery();

        $subqueryBidTransaction = $this->createBidTransactionReadRepository()
            ->select(['MAX(bt.created_on) AS max_bid_date'])
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->joinAuctionFilterAccountId($accountId)
            ->filterDeleted(false)
            ->filterFailed(false)
            ->inlineCondition('bt.user_id = u.id')
            ->getResultQuery();

        return "SELECT GREATEST(IFNULL(($subqueryAbsenteeBid), 0), IFNULL(($subqueryBidTransaction), 0)) AS greatest_created_on";
    }

    protected function buildQueryLastDateLoggedIn(): string
    {
        return $this->createUserLoginReadRepository()
            ->select(['MAX(ul.logged_date)'])
            ->inlineCondition('ul.user_id = u.id')
            ->getResultQuery();
    }

    protected function buildQueryLastDateWon(int $accountId): string
    {
        return $this->prepareLotItemRepository($accountId)
            ->select(['MAX(li.date_sold)'])
            ->inlineCondition('li.winning_bidder_id = u.id')
            ->getResultQuery();
    }

    protected function buildQueryLotsBidOnNum(int $accountId): string
    {
        $subqueryAbsenteeBid = $this->createAuctionLotItemReadRepository()
            ->select(['li.id', 'ab.user_id AS bid_transaction_user_id'])
            ->joinLotItemFilterActive(true)
            ->innerJoinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->innerJoinAbsenteeBid()
            ->filterAccountId($accountId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->inlineCondition("ab.max_bid > 0")
            ->getResultQuery();

        $subqueryBidTransaction = $this->createAuctionLotItemReadRepository()
            ->select(['li.id', 'bt.user_id AS bid_transaction_user_id'])
            ->joinLotItemFilterActive(true)
            ->innerJoinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->innerJoinBidTransaction()
            ->filterAccountId($accountId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->inlineCondition("NOT bt.deleted")
            ->getResultQuery();

        return "SELECT COUNT(1) `cnt` FROM (($subqueryAbsenteeBid) UNION DISTINCT ($subqueryBidTransaction)) AS lots_id_on WHERE bid_transaction_user_id = u.id";
    }

    protected function buildQueryLotsConsignedNum(int $accountId): string
    {
        return $this->prepareLotItemRepository($accountId)
            ->filterActive(true)
            ->inlineCondition('li.consignor_id = u.id')
            ->getCountQuery();
    }

    protected function buildQueryLotsConsignedSoldNum(int $accountId): string
    {
        return $this->prepareLotItemRepository($accountId)
            ->filterActive(true)
            ->skipHammerPrice(null)
            ->skipWinningBidderId(0)
            ->inlineCondition('li.consignor_id = u.id')
            ->getCountQuery();
    }

    protected function buildQueryLotsWonNum(int $accountId): string
    {
        return $this->prepareLotItemRepository($accountId)
            ->filterActive(true)
            ->skipHammerPrice(null)
            ->inlineCondition('li.winning_bidder_id = u.id')
            ->getCountQuery();
    }

    protected function buildQueryParticipatedAuctionsNum(int $accountId): string
    {
        $subqueryAbsenteeBid = $this->createAbsenteeBidReadRepository()
            ->select(['ab.auction_id AS auc_id', 'ab.user_id AS bid_transaction_user_id'])
            ->innerJoinAuctionFilterAccountId($accountId)
            ->innerJoinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->innerJoinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->filterMaxBidGreater(0)
            ->getResultQuery();

        $subqueryBidTransaction = $this->createBidTransactionReadRepository()
            ->select(['bt.auction_id AS auc_id', 'bt.user_id AS bid_transaction_user_id'])
            ->innerJoinAuctionByAuctionIdFilterAccountId($accountId)
            ->innerJoinAuctionByAuctionIdFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->innerJoinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->filterDeleted(false)
            ->getResultQuery();

        return "SELECT COUNT(1) `cnt` FROM (($subqueryAbsenteeBid) UNION ($subqueryBidTransaction)) AS participated WHERE bid_transaction_user_id = u.id";
    }

    /**
     * @param int $accountId
     * @return string
     */
    protected function buildQueryRegisteredAuctionsNum(int $accountId): string
    {
        return $this->prepareAuctionBidderRepository($accountId)->getCountQuery();
    }

    protected function buildQueryWatchlistItemsBidNum(int $accountId): string
    {
        $subqueryAbsenteeBid = $this->createAbsenteeBidReadRepository()
            ->filterMaxBidGreater(0)
            ->inlineCondition('ab.auction_id = uw.auction_id AND ab.lot_item_id = uw.lot_item_id')
            ->getCountQuery();

        $subqueryBidTransaction = $this->createBidTransactionReadRepository()
            ->filterDeleted(false)
            ->filterFailed(false)
            ->inlineCondition('bt.auction_id = uw.auction_id AND bt.lot_item_id = uw.lot_item_id')
            ->getCountQuery();

        return $this->prepareWatchlistRepository($accountId)
            ->inlineCondition("(({$subqueryAbsenteeBid}) > 0 OR ({$subqueryBidTransaction}) > 0)")
            ->inlineCondition('uw.user_id = u.id')
            ->getCountQuery();
    }

    protected function buildQueryWatchlistItemsNum(int $accountId): string
    {
        return $this->prepareWatchlistRepository($accountId)
            ->inlineCondition('uw.user_id = u.id')
            ->getCountQuery();
    }

    protected function buildQueryWatchlistItemsWonNum(int $accountId): string
    {
        return $this->prepareWatchlistRepository($accountId)
            ->joinLotItemFilterAccountId($accountId)
            ->inlineCondition('uw.user_id = u.id AND li.winning_bidder_id = uw.user_id')
            ->getCountQuery();
    }

    protected function prepareAuctionBidderRepository(int $accountId, array $auctionStatuses = []): AuctionBidderReadRepository
    {
        return $this->createAuctionBidderReadRepository()
            ->joinAuctionFilterAuctionStatusId($auctionStatuses ?: Constants\Auction::$availableAuctionStatuses)
            ->joinAuctionFilterAccountId($accountId)
            ->inlineCondition('aub.user_id = u.id');
    }

    protected function prepareLotItemRepository(int $accountId): LotItemReadRepository
    {
        return $this->createLotItemReadRepository()
            ->filterAccountId($accountId);
    }

    protected function prepareWatchlistRepository(int $accountId): UserWatchlistReadRepository
    {
        return $this->createUserWatchlistReadRepository()
            ->joinAuctionFilterAccountId($accountId)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->joinLotItemFilterActive(true);
    }
}
