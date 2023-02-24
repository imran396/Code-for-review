<?php
/**
 * SAM-4799: Refactor User Account Statistic loader and saver
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/19/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\User\Account\Statistic\Save;


use DateTime;
use QMySqli5DatabaseResult;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\Account\AccountReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserAccountStats\UserAccountStatsReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserAccountStatsCurrency\UserAccountStatsCurrencyReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserLogin\UserLoginReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserWatchlist\UserWatchlistReadRepository;
use Sam\Storage\ReadRepository\Entity\UserWatchlist\UserWatchlistReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\UserAccountStats\UserAccountStatsWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserAccountStatsCurrency\UserAccountStatsCurrencyWriteRepositoryAwareTrait;
use Sam\User\Account\Statistic\Load\UserAccountStatisticLoaderCreateTrait;
use UserAccountStatsCurrency;

class UserAccountStatisticProducer extends CustomizableClass
{
    use AbsenteeBidReadRepositoryCreateTrait;
    use AccountAwareTrait;
    use AccountReadRepositoryCreateTrait;
    use AuctionBidderReadRepositoryCreateTrait;
    use BidTransactionReadRepositoryCreateTrait;
    use CurrencyLoaderAwareTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;
    use EntityFactoryCreateTrait;
    use LotItemReadRepositoryCreateTrait;
    use UserAccountStatisticLoaderCreateTrait;
    use UserAccountStatsCurrencyReadRepositoryCreateTrait;
    use UserAccountStatsCurrencyWriteRepositoryAwareTrait;
    use UserAccountStatsReadRepositoryCreateTrait;
    use UserAccountStatsWriteRepositoryAwareTrait;
    use UserAwareTrait;
    use UserLoginReadRepositoryCreateTrait;
    use UserWatchlistReadRepositoryCreateTrait;

    /** @var string[] */
    public array $userAccountStatsFieldsBoard = [
        'registered_auctions_num' => 'num_registered',
        'approved_auctions_num' => '',
        'participated_auctions_num' => 'num_participated',
        'auctions_won_num' => '',

        'lots_bid_on_num' => 'num_lots_bid_on',
        'lots_won_num' => 'num_lots_won',

        'watchlist_items_won_num' => '',
        'watchlist_items_num' => 'num_watchlist',

        'lots_consigned_num' => 'num_consigned',
        'lots_consigned_sold_num' => 'num_sold',

        'last_date_auction_registered' => 'last_auction_registered',
        'last_date_bid' => 'last_lots_bid',
        'last_date_won' => 'last_lots_won',
        'last_date_logged_in' => 'last_lots_won',
    ];

    /** @var string[] */
    protected array $userAccountStatsFields = [
        'registered_auctions_num',
        'approved_auctions_num',
        'participated_auctions_num',
        'participated_auctions_perc',
        'auctions_won_num',
        'auctions_won_perc',

        'lots_bid_on_num',
        'lots_won_num',
        'lots_won_perc',

        'watchlist_items_won_num',
        'watchlist_items_num',
        'watchlist_items_won_perc',
        'watchlist_items_bid_num',
        'watchlist_items_bid_perc',

        'lots_consigned_num',
        'lots_consigned_sold_num',
        'last_date_auction_registered',
        'last_date_bid',
        'last_date_won',
        'last_date_logged_in',

        'calculated_on',
        'expired_on',
    ];

    /** @var string[] */
    protected array $userAccountStatsCurrencyFields = [
        'lots_bid_on_amt',
        'lots_won_amt',
        'lots_consigned_sold_amt',
        'watchlist_items_won_amt',
        'watchlist_items_bid_amt',
    ];

    /**
     * Returns an instance of UserAccountStatisticProducer
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createUserAccountStats(int $userId, int $accountId, int $editorUserId): void
    {
        if ($this->existUserAccountStats($userId, $accountId)) {
            return;
        }
        $this->produceUserAccountStats($userId, $accountId, $editorUserId);
    }

    public function createUserAccountStatsCurrency(int $userId, int $accountId, int $editorUserId, string $currencySign): void
    {
        if ($this->existUserAccountStatsByCurrencySign($userId, $accountId, $currencySign)) {
            return;
        }
        $this->produceUserAccountStatsForCurrency($userId, $accountId, $currencySign, $editorUserId);
    }

    public function loadUserAccountStatsCurrencyOrCreate(int $userId, int $accountId, int $editorUserId, string $currencySign): ?UserAccountStatsCurrency
    {
        return $this->loadUserAccountStatsCurrency($userId, $accountId, $currencySign)
            ?: $this->produceUserAccountStatsForCurrency($userId, $accountId, $currencySign, $editorUserId);
    }

    public function loadUserAccountStatsCurrency(int $userId, int $accountId, string $currencySign): ?UserAccountStatsCurrency
    {
        return $this->createUserAccountStatsCurrencyReadRepository()
            ->filterAccountId($accountId)
            ->filterCurrencySign($currencySign)
            ->filterUserId($userId)
            ->loadEntity();
    }

    public function markExpired(?int $userId, ?int $accountId, bool $isReadOnlyDb = false): void
    {
        if (
            !$userId
            || !$accountId
        ) {
            return;
        }

        $userAccountStats = $this->createUserAccountStatisticLoader()->loadNotExpired($userId, $accountId, $isReadOnlyDb);
        if (!$userAccountStats) {
            return;
        }

        $userAccountStats->ExpiredOn = $this->getCurrentDateUtc();
        $this->getUserAccountStatsWriteRepository()->saveWithSystemModifier($userAccountStats);
    }

    /**
     * @param int $userId
     * @param int $accountId
     * @return array
     */
    protected function determineUpdatedData(int $userId, int $accountId): array
    {
        $data = [];

        //PHASE 1
        //number of registered auctions for this account
        $registeredNum = $this->countRegisteredAuction($userId, $accountId);
        $data['registered_auctions_num'] = $registeredNum;

        //number of approved auctions in this account
        $approvedNum = $this->countApprovedAuction($userId, $accountId);
        $data['approved_auctions_num'] = $approvedNum;

        //number of participated auctions for this account (placed bids or absentee bids).
        $participatedNum = $this->countParticipatedAuction($userId, $accountId);
        $data['participated_auctions_num'] = $participatedNum;

        //number of registered auctions won for this account
        $auctionsWonNum = $this->countRegisteredAuctionWon($userId, $accountId);
        $data['auctions_won_num'] = $auctionsWonNum;

        if ($registeredNum > 0) {
            $data['auctions_won_perc'] = ($auctionsWonNum / $registeredNum) * 100;
            $data['participated_auctions_perc'] = ($participatedNum / $registeredNum) * 100;
        }

        //number of lots won
        $lotsWonNum = $this->countWonItems($userId, $accountId);
        $data['lots_won_num'] = $lotsWonNum;

        //number of lots bid on
        $lotsBidOnNum = $this->countBidItems($userId, $accountId);
        $data['lots_bid_on_num'] = $lotsBidOnNum;

        $data['lots_won_perc'] = 0;
        if ($lotsBidOnNum > 0) {
            $data['lots_won_perc'] = ($lotsWonNum / $lotsBidOnNum) * 100;
        }

        //amount won -> sum hammer prices of lots the user won per currency
        $amountsWonCurr = $this->aggregateAmountWonPerCurrency($userId, $accountId);
        $data['lots_won_amt'] = $amountsWonCurr;

        //number of lots in watchlist
        $watchlistNum = $this->countWatchlistItems($userId, $accountId);
        $data['watchlist_items_num'] = $watchlistNum;

        //number of lots won in watchlist
        $watchlistWonNum = $this->countWatchlistItemsWon($userId, $accountId);
        $data['watchlist_items_won_num'] = $watchlistWonNum;

        $watchlistBidOnNum = $this->countWatchlistItemsBidOn($userId, $accountId);
        $data['watchlist_items_bid_num'] = $watchlistBidOnNum;
        $data['watchlist_items_won_perc'] = 0;
        $data['watchlist_items_bid_perc'] = 0;
        if ($watchlistNum > 0) {
            $data['watchlist_items_won_perc'] = ($watchlistWonNum / $watchlistNum) * 100;
            $data['watchlist_items_bid_perc'] = ($watchlistBidOnNum / $watchlistNum) * 100;
        }
        //number of consigned lots
        $consignedNum = $this->countConsignedItems($userId, $accountId);
        $data['lots_consigned_num'] = $consignedNum;

        //number of lots sold
        $lotsSoldNum = $this->countSoldItems($userId, $accountId);
        $data['lots_consigned_sold_num'] = $lotsSoldNum;

        //amount lots sold -> sum of hammer prices of lots sold per currency
        $amountsSoldCurr = $this->aggregateAmountSoldPerCurrency($userId, $accountId);
        $data['lots_consigned_sold_amt'] = $amountsSoldCurr;

        //sum of hammer prices of watched items won
        $watchlistWonAmount = $this->aggregateWatchlistWonAmount($userId, $accountId);
        $data['watchlist_items_won_amt'] = $watchlistWonAmount;

        //sum of high bids of watched items bid on
        $watchlistBidAmount = $this->aggregateWatchlistBidAmount($userId, $accountId);
        $data['watchlist_items_bid_amt'] = $watchlistBidAmount;

        //total amount bid -> sum highest bids of lots the user bid on by currency
        $amountsBidCurr = $this->aggregateTotalBidOnPerCurrency($userId, $accountId);
        $data['lots_bid_on_amt'] = $amountsBidCurr;

        //last date registered on this account
        $lastRegisteredDateIso = $this->detectLastDateRegistered($userId, $accountId);
        $data['last_date_auction_registered'] = $lastRegisteredDateIso;

        //last date bid on this account
        $lastBidOnDateIso = $this->detectLastDateBidOn($userId, $accountId);
        $data['last_date_bid'] = $lastBidOnDateIso;

        //last date won on this account
        $lastWonDateIso = $this->detectLastDateWonOn($userId, $accountId);
        $data['last_date_won'] = $lastWonDateIso;

        //last date logged in this account
        $lastLoggedInDateIso = $this->detectLastDateLoggedIn($userId);
        $data['last_date_logged_in'] = $lastLoggedInDateIso;

        return $data;
    }

    /**
     * @param int $userId
     * @param int $accountId
     * @param int $editorUserId
     */
    protected function produceUserAccountStats(int $userId, int $accountId, int $editorUserId): void
    {
        $stats = $this->createEntityFactory()->userAccountStats();
        $stats->AccountId = $accountId;
        $stats->UserId = $userId;
        $this->getUserAccountStatsWriteRepository()->saveWithModifier($stats, $editorUserId);
    }

    /**
     * @param int $userId
     * @param int $accountId
     * @param array $data
     * @param int $editorUserId
     * @return bool
     */
    protected function updateUserAccountStats(int $userId, int $accountId, array $data, int $editorUserId): bool
    {
        if (count($data) === 0) {
            return false;
        }

        if (!$this->existUserAccountStats($userId, $accountId)) {
            $this->produceUserAccountStats($userId, $accountId, $editorUserId);
        }

        $accountRepository = $this->createUserAccountStatsReadRepository()
            ->filterUserId($userId)
            ->filterAccountId($accountId);

        $query = $accountRepository->getResultQuery();
        $accountStats = $accountRepository->loadEntities();
        foreach ($accountStats as $accountStat) {
            $data['calculated_on'] = $this->getCurrentDateUtc()->format(Constants\Date::ISO);
            $data['expired_on'] = null;
            foreach ($data as $field => $value) {
                if (in_array($field, $this->userAccountStatsFields, true)) {
                    $tempArr = explode('_', $field);
                    foreach ($tempArr as $key => $word) {
                        $tempArr[$key] = ucfirst($word);
                    }
                    $fieldName = implode('', $tempArr);
                    //checking if it's a date/time field
                    if (date(Constants\Date::ISO, strtotime((string)$value)) === $value) {
                        $accountStat->{$fieldName} = (new DateTime())->setTimestamp(strtotime($value));
                    } else {
                        $accountStat->{$fieldName} = $value;
                    }
                    $this->getUserAccountStatsWriteRepository()->saveWithModifier($accountStat, $editorUserId);
                }
            }
        }
        log_traceQuery($query);

        return true;
    }

    protected function produceUserAccountStatsForCurrency(int $userId, int $accountId, string $currencySign, int $editorUserId): UserAccountStatsCurrency
    {
        $statsCurrency = $this->createEntityFactory()->userAccountStatsCurrency();
        $statsCurrency->UserId = $userId;
        $statsCurrency->AccountId = $accountId;
        $statsCurrency->CurrencySign = $currencySign;
        $this->getUserAccountStatsCurrencyWriteRepository()->saveWithModifier($statsCurrency, $editorUserId);
        return $statsCurrency;
    }

    /**
     * @param int $userId
     * @param int $accountId
     * @param array $data
     * @param int $editorUserId
     * @return bool
     */
    protected function updateUserAccountCurrency(int $userId, int $accountId, array $data, int $editorUserId): bool
    {
        if (count($data) === 0) {
            return false;
        }

        $currencyFields = $this->userAccountStatsCurrencyFields;

        foreach ($currencyFields as $field) {
            if (isset($data[$field])) {
                $tempArr = explode('_', $field);
                foreach ($tempArr as $key => $word) {
                    $tempArr[$key] = ucfirst($word);
                }
                $fieldName = implode('', $tempArr);

                foreach ($data[$field] as $currencySign => $amount) {
                    if (!$this->existUserAccountStatsByCurrencySign($userId, $accountId, $currencySign)) {
                        $this->produceUserAccountStatsForCurrency($userId, $accountId, $currencySign, $editorUserId);
                    }

                    $query = 'SELECT * FROM user_account_stats_currency  ' .
                        'WHERE user_id = ' . $this->escape($userId) . ' ' .
                        'AND account_id = ' . $this->escape($accountId) . ' ' .
                        'AND currency_sign = ' . $this->escape($currencySign) . ' ';
                    //log_traceQuery($query);

                    $accountCurrencyStatsFields = UserAccountStatsCurrency::InstantiateDbResult($this->query($query));
                    foreach ($accountCurrencyStatsFields as $userAccountStatsCurrency) {
                        $userAccountStatsCurrency->{$fieldName} = $amount;
                        $this->getUserAccountStatsCurrencyWriteRepository()->saveWithModifier($userAccountStatsCurrency, $editorUserId);
                    }
                }
            }
        }
        return true;
    }

    /**
     * @param int $userId
     * @param int $accountId
     * @return bool
     */
    protected function existUserAccountStats(int $userId, int $accountId): bool
    {
        $exist = $this->createUserAccountStatsReadRepository()
            ->filterUserId($userId)
            ->filterAccountId($accountId)
            ->exist();
        return $exist;
    }

    /**
     * @param int $userId
     * @param int $accountId
     * @param string $currencySign
     * @return bool
     */
    protected function existUserAccountStatsByCurrencySign(int $userId, int $accountId, string $currencySign): bool
    {
        $exist = $this->createUserAccountStatsCurrencyReadRepository()
            ->filterUserId($userId)
            ->filterAccountId($accountId)
            ->filterCurrencySign($currencySign)
            ->exist();
        return $exist;
    }

    /* PHASE 1 includes the following
     *
     * number of registered auctions for this account
     * amount won
       -> sum hammer prices of lots the user won per currency
     * number of lots in watchlist
     * number of consigned lots
     * number of lots sold
     * amount lots sold
       -> sum of hammer prices of lots sold per currency
     * last date registered on this account
     * last date bid on this account
     * last date won on this account
     */
    /**
     * @param int $userId
     * @param int $editorUserId
     */
    public function updateUserAccountAll(int $userId, int $editorUserId): void
    {
        $accounts = $this->createAccountReadRepository()
            ->filterActive(true)
            ->loadEntities();

        $accountIds = [];
        foreach ($accounts as $account) {
            $accountIds[] = $account->Id;
        }

        foreach ($accountIds as $accountId) {
            $data = $this->determineUpdatedData($userId, $accountId);
            $this->updateUserAccountStats($userId, $accountId, $data, $editorUserId);
            $this->updateUserAccountCurrency($userId, $accountId, $data, $editorUserId);
        }
    }

    /**
     * Get the number of registered auctions for this account
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id null means there is no filtering by account_id
     * @return int num_registered
     */
    protected function countRegisteredAuction(int $userId, ?int $accountId = null): int
    {
        $repository = $this->createAuctionBidderReadRepository()
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->filterUserId($userId);
        if ($accountId) {
            $repository->joinAuctionFilterAccountId($accountId);
        }
        $count = $repository->count();
        return $count;
    }

    /**
     * Get the number of registered auctions for this account
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id null means there is no filtering by a.account_id
     * @return int num_auction_won
     */
    protected function countRegisteredAuctionWon(int $userId, ?int $accountId = null): int
    {
        // @formatter:off
        $query =
            'SELECT ' .
                'COUNT(1) AS num_auction_won ' .
            'FROM ' .
                'auction_bidder ab ' .
            'INNER JOIN ' .
                'auction a ON a.id = ab.auction_id ' .
                'AND a.auction_status_id NOT IN (' . implode(',', [Constants\Auction::AS_DELETED, Constants\Auction::AS_ARCHIVED]) . ') ' .
                ($accountId?'AND a.account_id = ' . $this->escape($accountId):'') . ' ' .
            'WHERE  ' .
                'ab.user_id = ' . $this->escape($userId) . ' ' .
                'AND ( ' .
                    'SELECT count(1)  ' .
                    'FROM auction_lot_item ali ' .
                    'INNER JOIN lot_item li ON li.id = ali.lot_item_id ' .
                    'WHERE  ' .
                        'ali.auction_id = a.id ' .
                        'AND li.winning_bidder_id = ab.user_id ' .
                        'AND li.hammer_price IS NOT NULL) > 0';
        // @formatter:on
        log_traceQuery($query);
        $dbResult = $this->query($query);
        $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        return (int)$row['num_auction_won'];
    }

    /**
     * Get number of registered & approved auctions for this account
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id null means there is no filtering by account_id
     * @return int num_approved
     */
    protected function countApprovedAuction(int $userId, ?int $accountId = null): int
    {
        $repository = $this->createAuctionBidderReadRepository()
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->filterUserId($userId)
            ->filterApproved(true)
            ->filterBidderNumFilled();
        if ($accountId) {
            $repository->joinAuctionFilterAccountId($accountId);
        }
        return $repository->count();
    }

    /**
     * Get the number of registered auctions for this account
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id null means there is no filtering by a.account_id
     * @return int|null num_registered
     */
    protected function countParticipatedAuction(int $userId, ?int $accountId = null): ?int
    {
        // @formatter:off
        $query =
            'SELECT ' .
                'COUNT(1) AS num_participated ' .
            'FROM ' .
                '( ' .
                'SELECT ' .
                    'bt.auction_id AS auc_id ' .
                'FROM  ' .
                    'bid_transaction AS bt ' .
                'INNER JOIN auction a ON a.id = bt.auction_id ' .
                    'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$availableAuctionStatuses) . ') ' .
                    ($accountId?'AND a.account_id = ' . $this->escape($accountId) . ' ':'') .
                'INNER JOIN auction_lot_item AS ali ON bt.auction_id = ali.auction_id ' .
                    'AND bt.lot_item_id = ali.lot_item_id AND ali.lot_status_id IN ('.implode(',',Constants\Lot::$availableLotStatuses).') ' .
                'WHERE  ' .
                    'bt.user_id = ' . $this->escape($userId) . ' ' .
                    'AND bt.deleted = false ' .

                'UNION ' .

                'SELECT  ' .
                    'ab.auction_id AS auc_id ' .
                'FROM ' .
                    'absentee_bid AS ab ' .
                'INNER JOIN auction a ON a.id = ab.auction_id ' .
                    'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$availableAuctionStatuses) . ') ' .
                    ($accountId?'AND a.account_id = ' . $this->escape($accountId) . ' ':'') .
                'INNER JOIN auction_lot_item AS ali ON ab.auction_id = ali.auction_id ' .
                    'AND ab.lot_item_id = ali.lot_item_id AND ali.lot_status_id IN ('.implode(',',Constants\Lot::$availableLotStatuses).') ' .
                'WHERE  ' .
                    'ab.user_id = ' . $this->escape($userId) . ' ' .
                    'AND ab.max_bid > 0  ' .
                'GROUP BY a.id ' .

            ') AS participated ';
        // @formatter:on
        log_traceQuery($query);
        $dbResult = $this->query($query);
        $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        return $row['num_participated'];
    }

    /**
     * Get amount of won per currency
     *
     * @param int $userId user.id
     * @param int $accountId account.id
     * @return array
     */
    protected function aggregateAmountWonPerCurrency(int $userId, int $accountId): array
    {
        $rows = $this->createLotItemReadRepository()
            ->select(['SUM(IFNULL(li.hammer_price, 0)) AS hammer_price'])
            ->joinCurrencySelectCurrencySign()
            ->filterAccountId($accountId)
            ->filterWinningBidderId($userId)
            ->joinCurrencyGroupCurrencySign()
            ->loadRows();
        $arrAmountCurr = [];
        foreach ($rows as $row) {
            $arrAmountCurr[$row['currency_sign']] = $row['hammer_price'];
        }
        return $arrAmountCurr;
    }

    /**
     * @param int $userId
     * @param int|null $accountId null means there is no filtering by a.account_id
     * @return array
     */
    protected function aggregateTotalBidOnPerCurrency(int $userId, ?int $accountId): array
    {
        // @formatter:off
        $query =
            'SELECT ' .
                'currency_sign, ' .
                'SUM(high_bid) AS high_bid_amount ' .
            'FROM( ' .
                'SELECT  ' .
                    'c.sign AS currency_sign, ' .
                    'MAX(bt.bid) AS high_bid ' .
                'FROM ' .
                    'bid_transaction bt ' .
                'INNER JOIN auction a ON a.id = bt.auction_id ' .
                    'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$availableAuctionStatuses) . ') ' .
                    ($accountId?'AND a.account_id = ' . $this->escape($accountId) . ' ':'') .
                'LEFT JOIN ' .
                    'currency c ON c.id = a.currency ' .
                'WHERE ' .
                    'bt.deleted = false ' .
                    'AND bt.user_id = ' . $this->escape($userId) . ' ' .
                    'AND bt.failed = false ' .
                'GROUP BY lot_item_id, a.currency ' .
            ') AS high_bids ' .
            'GROUP BY currency_sign ';
        // @formatter:on
        $arrAmountCurr = [];
        log_traceQuery($query);
        $dbResult = $this->query($query);
        while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $arrAmountCurr[$row['currency_sign']] = $row['high_bid_amount'];
        }
        return $arrAmountCurr;
    }

    /**
     * Get number of lots in watchlist
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id null means there is no filtering by account_id
     * @return int num_watchlist
     */
    protected function countWatchlistItems(int $userId, ?int $accountId = null): int
    {
        $repository = $this->createUserWatchlistReadRepository()
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->filterUserId($userId)
            ->joinLotItemFilterActive(true);
        if ($accountId) {
            $repository->joinAuctionFilterAccountId($accountId);
        }
        return $repository->count();
    }

    /**
     * Get number of lots won in watchlist
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id null means there is no filtering by a.account_id and li,account_id
     * @return int num_watchlist
     */
    protected function countWatchlistItemsWon(int $userId, ?int $accountId = null): int
    {
        // @formatter:off
        $query =
            'SELECT ' .
                'COUNT(1) AS num_watchlist_won ' .
            'FROM ' .
                'user_watchlist uwl ' .
            'INNER JOIN lot_item li ON li.id = uwl.lot_item_id ' .
                'AND li.active ' .
                'AND li.winning_bidder_id = uwl.user_id ' .
                ($accountId?'AND li.account_id = ' . $this->escape($accountId) . ' ':'') .
            'INNER JOIN auction a ON a.id = uwl.auction_id ' .
                'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$availableAuctionStatuses) . ') ' .
                'AND a.id = uwl.auction_id ' .
                ($accountId?'AND a.account_id = ' . $this->escape($accountId) . ' ':'') .
            'WHERE ' .
                'uwl.user_id = ' . $this->escape($userId);
        // @formatter:on
        log_traceQuery($query);
        $dbResult = $this->query($query);
        $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        return (int)$row['num_watchlist_won'];
    }


    /**
     * Get number of lots in watchlist
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id null means there is no filtering by a.account_id and li,account_id
     * @return int num_watchlist
     */
    protected function countWatchlistItemsBidOn(int $userId, ?int $accountId = null): int
    {
        // @formatter:off
        $query =
            'SELECT ' .
                'COUNT(1) AS num_watchlist_bid_on ' .
            'FROM ' .
                'user_watchlist AS uwl ' .
            'INNER JOIN lot_item li ON li.id = uwl.lot_item_id ' .
                'AND li.active ' .
                ($accountId?'AND li.account_id = ' . $this->escape($accountId) . ' ':'') .
            'INNER JOIN auction a ON a.id = uwl.auction_id ' .
                'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$availableAuctionStatuses) . ') ' .
                'AND a.id = uwl.auction_id ' .
                ($accountId?'AND a.account_id = ' . $this->escape($accountId) . ' ':'') .
            'WHERE ' .
                'user_id = ' . $this->escape($userId) . ' ' .
                'AND (' .
                    '(SELECT COUNT(1) ' .
                    'FROM bid_transaction AS bt ' .
                    'WHERE bt.auction_id = uwl.auction_id ' .
                    'AND bt.lot_item_id = uwl.lot_item_id ' .
                    'AND bt.deleted = false  ' .
                    'AND bt.failed = false) > 0 ' .
                'OR ' .
                    '(SELECT COUNT(1) ' .
                    'FROM absentee_bid AS ab ' .
                    'WHERE ab.auction_id = uwl.auction_id ' .
                    'AND ab.lot_item_id = uwl.lot_item_id ' .
                    'AND ab.max_bid > 0) > 0 ' .
                ') ';
        // @formatter:on
        log_traceQuery($query);
        $dbResult = $this->query($query);
        $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        return (int)$row['num_watchlist_bid_on'];
    }

    /**
     * Get number of consigned lots
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id null means there is no filtering by account_id
     * @return int num_consigned
     */
    protected function countConsignedItems(int $userId, ?int $accountId = null): int
    {
        $repository = $this->createLotItemReadRepository()
            ->filterConsignorId($userId)
            ->filterActive(true);
        if ($accountId) {
            $repository->filterAccountId($accountId);
        }
        $count = $repository->count();
        return $count;
    }

    /**
     * Get number of lots sold
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id null means there is no filtering by account_id
     * @return int num_sold
     */
    protected function countSoldItems(int $userId, ?int $accountId = null): int
    {
        $repository = $this->createLotItemReadRepository()
            ->filterConsignorId($userId)
            ->filterActive(true)
            ->skipHammerPrice(null)
            ->skipWinningBidderId(0);
        if ($accountId) {
            $repository->filterAccountId($accountId);
        }
        $count = $repository->count();
        return $count;
    }

    /**
     * Get amount lots sold
     *
     * @param int $userId user.id
     * @param int $accountId account.id
     * @return array
     */
    protected function aggregateAmountSoldPerCurrency(int $userId, int $accountId): array
    {
        $rows = $this->createLotItemReadRepository()
            ->select(['SUM(IFNULL(li.hammer_price, 0)) AS hammer_price'])
            ->joinCurrencySelectCurrencySign()
            ->filterConsignorId($userId)
            ->filterAccountId($accountId)
            ->joinCurrencyGroupCurrencySign()
            ->loadRows();
        $arrAmountCurr = [];
        foreach ($rows as $row) {
            $arrAmountCurr[$row['currency_sign']] = $row['hammer_price'];
        }
        return $arrAmountCurr;
    }

    /**
     * Get last date registered on this account
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id null means there is no filtering by auction's account
     * @return string|null iso date format
     */
    protected function detectLastDateRegistered(int $userId, ?int $accountId = null): ?string
    {
        $repository = $this->createAuctionBidderReadRepository()
            ->filterUserId($userId);
        if ($accountId) {
            $repository->joinAuctionFilterAccountId($accountId)
                ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses);
        }
        $repository->select(['MAX(aub.registered_on) AS registered_on']);
        $row = $repository->loadRow();
        return $row['registered_on'] ?? null;
    }

    /**
     * Get last date bid on this account
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id null means there is no filtering by a.account_id
     * @return string|null iso date format
     */
    protected function detectLastDateBidOn(int $userId, ?int $accountId = null): ?string
    {
        // @formatter:off
        $query =
            'SELECT MAX(max_bid_date) AS bid_date ' .
            'FROM (' .

                'SELECT ' .
                    'MAX(bt.created_on) AS max_bid_date ' .
                'FROM ' .
                    'bid_transaction bt ' .
                'INNER JOIN auction a ON a.id = bt.auction_id ' .
                    'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$availableAuctionStatuses) . ') ' .
                    ($accountId?'AND a.account_id = ' . $this->escape($accountId) . ' ':'') .
                'WHERE ' .
                    'bt.deleted = false ' .
                    'AND bt.user_id = ' . $this->escape($userId) . ' ' .
                    'AND bt.failed = false ' . // No Failed bids

                'UNION ' .

                'SELECT ' .
                    'MAX(ab.created_on) AS max_bid_date ' .
                'FROM ' .
                    'absentee_bid ab ' .
                'INNER JOIN auction a ON a.id = ab.auction_id ' .
                    'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$availableAuctionStatuses) . ') ' .
                    ($accountId?'AND a.account_id = ' . $this->escape($accountId) . ' ':'') .
                'WHERE ' .
                    'ab.max_bid > 0 ' .
                    'AND ab.user_id = ' . $this->escape($userId) . ' ' .

            ') AS bid_dates';
        //'ORDER BY bid_date DESC ' .
        //'LIMIT 1';
        // @formatter:on
        log_traceQuery($query);
        $dbResult = $this->query($query);
        $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        return $row['bid_date'];
    }

    /**
     * Get last date won on this account
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id  null means there is no filtering by account_id
     * @return string|null iso date format
     */
    protected function detectLastDateWonOn(int $userId, ?int $accountId = null): ?string
    {
        $repository = $this->createLotItemReadRepository()
            ->filterWinningBidderId($userId);

        if ($accountId) {
            $repository->filterAccountId($accountId);
        }

        $repository->select(['MAX(li.date_sold) AS date_sold']);
        $row = $repository->loadRow();

        return $row['date_sold'] ?? null;
    }

    /**
     * Get last date logged in this account
     *
     * @param int $userId user.id
     * @return string|null iso date format
     */
    protected function detectLastDateLoggedIn(int $userId): ?string
    {
        $dbResult = $this->createUserLoginReadRepository()
            ->filterUserId($userId)
            ->select(['MAX(ul.logged_date) AS logged_date'])
            ->loadRow();
        return $dbResult['logged_date'] ?? null;
    }

    /**
     * Get  number of items won.
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id null means there is no filtering by account_id
     * @return int
     */
    protected function countWonItems(int $userId, ?int $accountId = null): int
    {
        $repository = $this->createLotItemReadRepository()
            ->filterWinningBidderId($userId)
            ->filterActive(true)
            ->skipHammerPrice(null);
        if ($accountId) {
            $repository->filterAccountId($accountId);
        }
        return $repository->count();
    }

    /**
     * Get number of items bid on.
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id null means there is no filtering by li.account_id
     * @return int
     */
    protected function countBidItems(int $userId, ?int $accountId = null): int
    {
        // @formatter:off
        $query =
            'SELECT ' .
                'COUNT(1) AS num_bid_items ' .
            'FROM ' .
            '( ' .
                'SELECT ' .
                    'bt.lot_item_id AS item_id ' .
                'FROM  ' .
                    'bid_transaction AS bt ' .
                'INNER JOIN lot_item li ON li.id = bt.lot_item_id ' .
                    'AND li.active ' .
                    ($accountId?'AND li.account_id = ' . $this->escape($accountId) . ' ':'') .
                'INNER JOIN auction_lot_item AS ali ON bt.auction_id = ali.auction_id ' .
                    'AND bt.lot_item_id = ali.lot_item_id AND ali.lot_status_id IN ('.implode(',',Constants\Lot::$availableLotStatuses).') ' .
                'INNER JOIN auction a ON a.id = ali.auction_id ' .
                    'AND ali.lot_status_id IN ('.implode(',',Constants\Auction::$availableAuctionStatuses).') ' .
                'WHERE  ' .
                    'bt.user_id = ' . $this->escape($userId) . ' ' .
                    'AND bt.deleted = false ' .

                'UNION ' .

                'SELECT  ' .
                    'ab.lot_item_id AS item_id ' .
                'FROM ' .
                    'absentee_bid AS ab ' .
                'INNER JOIN lot_item li ON li.id = ab.lot_item_id ' .
                    'AND li.active ' .
                    ($accountId?'AND li.account_id = ' . $this->escape($accountId) . ' ':'') .
                'INNER JOIN auction_lot_item AS ali ON ab.auction_id = ali.auction_id ' .
                    'AND ab.lot_item_id = ali.lot_item_id AND ali.lot_status_id IN ('.implode(',',Constants\Lot::$availableLotStatuses).') ' .
                'INNER JOIN auction a ON a.id = ali.auction_id ' .
                    'AND ali.lot_status_id IN ('.implode(',',Constants\Auction::$availableAuctionStatuses).') ' .
                'WHERE  ' .
                    'ab.user_id = ' . $this->escape($userId) . ' ' .
                    'AND ab.max_bid > 0 ' .
                'GROUP BY li.id ' .

            ') AS bid_items ';
        // @formatter:on
        log_traceQuery($query);
        $dbResult = $this->query($query);
        $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        return (int)$row['num_bid_items'];
    }

    protected function aggregateWatchlistBidAmount(int $userId, ?int $accountId = null): array
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
        $rows = $this->prepareWatchlistRepository($accountId)
            ->select(['SUM(IFNULL(li.hammer_price, 0)) AS hammer_price', 'curr.sign AS currency_sign'])
            ->inlineCondition("(({$subqueryAbsenteeBid}) > 0 OR ({$subqueryBidTransaction}) > 0)")
            ->filterUserId($userId)
            ->groupByCurrencySign()
            ->loadRows();
        $arrAmountCurr = [];
        foreach ($rows as $row) {
            $arrAmountCurr[$row['currency_sign']] = $row['hammer_price'];
        }
        return $arrAmountCurr;
    }

    protected function aggregateWatchlistWonAmount(int $userId, ?int $accountId = null): array
    {
        $rows = $this->createLotItemReadRepository()
            ->select(['SUM(IFNULL(li.hammer_price, 0)) AS hammer_price', 'curr.sign AS currency_sign'])
            ->joinCurrencySelectCurrencySign()
            ->joinUserWatchlist()
            ->filterAccountId($accountId)
            ->filterWinningBidderId($userId)
            ->joinCurrencyGroupCurrencySign()
            ->loadRows();
        $arrAmountCurr = [];
        foreach ($rows as $row) {
            $arrAmountCurr[$row['currency_sign']] = $row['hammer_price'];
        }
        return $arrAmountCurr;
    }

    protected function prepareWatchlistRepository(int $accountId): UserWatchlistReadRepository
    {
        return $this->createUserWatchlistReadRepository()
            ->joinAuctionFilterAccountId($accountId)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->joinLotItemFilterActive(true);
    }
}
