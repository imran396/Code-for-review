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


namespace Sam\User\Account\Statistic\Load;

use Auction;
use AuctionLotItem;
use Sam\Core\Service\CustomizableClass;
use LotItem;
use QMySqli5DatabaseResult;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\ReadRepository\Entity\UserAccountStats\UserAccountStatsReadRepositoryCreateTrait;
use UserAccountStats;
use UserWatchlist;


class UserAccountStatisticLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use UserAwareTrait;
    use AccountAwareTrait;
    use LimitInfoAwareTrait;
    use UserAccountStatsReadRepositoryCreateTrait;

    /**
     * @var array
     */
    protected array $userAccountStatsFieldsTotal = [
        'registered_auctions_num',
        'approved_auctions_num',
        'participated_auctions_num',
        'auctions_won_num',

        'lots_bid_on_num',
        'lots_won_num',

        'watchlist_items_won_num',
        'watchlist_items_num',
        'watchlist_items_bid_num',

        'lots_consigned_num',
        'lots_consigned_sold_num',
    ];

    /**
     * @var array
     */
    protected array $userAccountStatsFieldsDate = [
        'last_date_logged_in',
        'last_date_auction_registered',
        'last_date_auction_approved',
        'last_date_bid',
        'last_date_won',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $userId
     * @param int $accountId
     * @return UserAccountStats[]
     */
    protected function _loadUserAccountStats(int $userId, int $accountId): array
    {
        $query = 'SELECT ' .
            '* ' .
            'FROM ' .
            'user_account_stats AS uat ' .
            'WHERE  ' .
            'uat.user_id = ' . $this->escape($userId) . ' ' .
            'AND uat.account_id = ' . $this->escape($accountId) . ' ';
        $dbResult = $this->query($query);
        return $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC) ?: [];
    }

    /**
     * @return UserAccountStats[]
     */
    public function loadUserAccountStats(): array
    {
        return $this->_loadUserAccountStats(
            $this->getUserId(),
            $this->getAccountId()
        );
    }

    /**
     * @param int $userId
     * @param string|null $currencySign null means there is no filtering by currency  sign
     * @return array
     */
    protected function _getUserAccountStatsCurrencyTotal(int $userId, ?string $currencySign = null): array
    {
        // @formatter:off
        $query =
            'SELECT ' .
            'currency_sign AS currency_sign, ' .
            'SUM(IFNULL(uatc.lots_bid_on_amt, 0)) AS lots_bid_on_amt, ' .
            'SUM(IFNULL(uatc.lots_won_amt, 0)) AS lots_won_amt, ' .
            'SUM(IFNULL(uatc.lots_consigned_sold_amt, 0)) AS lots_consigned_sold_amt, ' .
            'SUM(IFNULL(uatc.watchlist_items_won_amt, 0)) AS watchlist_items_won_amt, ' .
            'SUM(IFNULL(uatc.watchlist_items_bid_amt, 0)) AS watchlist_items_bid_amt ' .
            'FROM ' .
            'user_account_stats_currency AS uatc ' .
            'WHERE ' .
            'uatc.user_id = ' . $this->escape($userId) . ' ' .
            ($currencySign?'AND uatc.currency_sign = ' . $this->escape($currencySign) . ' ':' ') .
            'GROUP BY currency_sign';
        // @formatter:on

        $arrUserAccountCurrency = [];
        $dbResult = $this->query($query);
        while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $arrUserAccountCurrency[] = $row;
        }
        return $arrUserAccountCurrency;
    }

    /**
     * @return array
     */
    public function getUserAccountStatsCurrencyTotal(): array
    {
        return $this->_getUserAccountStatsCurrencyTotal($this->getUserId());
    }

    /**
     * @param int $userId
     * @param int $accountId
     * @param string|null $currencySign null means there is no filtering by currency  sign
     * @return array
     */
    protected function _getUserAccountStatsCurrency(int $userId, int $accountId, ?string $currencySign = null): array
    {
        // @formatter:off
        $query =
            'SELECT ' .
            'uatc.* ' .
            'FROM ' .
            'user_account_stats_currency AS uatc ' .
            'WHERE  ' .
            'uatc.user_id = ' . $this->escape($userId) . ' ' .
            'AND uatc.account_id = ' . $this->escape($accountId) . ' ' .
            ($currencySign?'AND uatc.currency_sign = ' . $this->escape($currencySign) . ' ':'');
        // @formatter:on
        $arrUserAccountCurrency = [];
        $dbResult = $this->query($query);
        while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $arrUserAccountCurrency[] = $row;
        }
        return $arrUserAccountCurrency;
    }

    /**
     * @return array
     */
    public function getUserAccountStatsCurrency(): array
    {
        return $this->_getUserAccountStatsCurrency(
            $this->getUserId(),
            $this->getAccountId()
        );
    }

    /**
     * Get registered auctions for this account
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id null means there is no filtering by a.account_id but joining account table
     * @param string $orderExpr
     * @param bool $shouldReturnObject
     * @param int|null $offset null means there is no limit clause if $limit value has null value too
     * @param int|null $limit null means there is no limit clause if $offset value has null value too
     * @return array
     */
    protected function _getRegisteredAuctions(
        int $userId,
        ?int $accountId = null,
        string $orderExpr = 'a.sale_num DESC',
        bool $shouldReturnObject = true,
        ?int $offset = null,
        ?int $limit = null
    ): array {
        $limitClause = '';
        if ($limit !== null && $offset !== null) {
            $limitClause = ' LIMIT ' . $offset . ', ' . $limit;
        }
        // @formatter:off
        $query =
            'SELECT ' .
            'a.*, ' .
            (!$accountId?'acc.name AS __account_name, ':'') .
            'ab.registered_on AS __registered_on ' .
            'FROM ' .
            'auction_bidder ab ' .
            'INNER JOIN ' .
            'auction a ON a.id = ab.auction_id ' .
            'AND a.auction_status_id NOT IN (' . implode(',', [Constants\Auction::AS_DELETED, Constants\Auction::AS_ARCHIVED]) . ') ' .
            ($accountId?'AND a.account_id = ' . $this->escape($accountId):'') . ' ' .
            (!$accountId?'INNER JOIN account acc ON acc.id = a.account_id AND acc.active ':'') .
            'WHERE  ' .
            'ab.user_id = ' . $this->escape($userId) . ' ' .
            'ORDER BY ' . $orderExpr .
            $limitClause;
        // @formatter:on


        $auctionRows = [];
        $dbResult = $this->query($query);

        if ($shouldReturnObject) {
            $auctionRows = Auction::InstantiateDbResult($dbResult);
        } else {
            while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
                $auctionRows[] = $row;
            }
        }
        return $auctionRows;
    }

    /**
     * Get registered auctions for this account
     *
     * @param string $orderExpr
     * @return array
     */
    public function getRegisteredAuctions(
        string $orderExpr = 'a.sale_num DESC'
    ): array {
        return $this->_getRegisteredAuctions(
            $this->getUserId(),
            $this->getAccountId(),
            $orderExpr,
            false,
            $this->getOffset(),
            $this->getLimit()
        );
    }

    /**
     * Get participated auctions for this account
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id account.id null means there is no filtering by a.account_id but joining account table
     * @param string $orderExpr
     * @param bool $shouldReturnObject
     * @param int|null $offset null means there is no limit clause if $limit value has null value too
     * @param int|null $limit null means there is no limit clause if $offset value has null value too
     * @return array
     */
    protected function _getParticipatedAuctions(
        int $userId,
        ?int $accountId = null,
        string $orderExpr = 'sale_num DESC',
        bool $shouldReturnObject = true,
        ?int $offset = null,
        ?int $limit = null
    ): array {
        $limitClause = '';
        if ($limit !== null && $offset !== null) {
            $limitClause = ' LIMIT ' . $offset . ', ' . $limit;
        }
        // @formatter:off
        $query =
            'SELECT ' .
            'a.* ' .
            (!$accountId?',acc.name AS __account_name ':'') .
            'FROM  ' .
            'bid_transaction AS bt ' .
            'INNER JOIN auction a ON a.id = bt.auction_id ' .
            'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$availableAuctionStatuses) . ') ' .
            ($accountId?'AND a.account_id = ' . $this->escape($accountId) . ' ':'') .
            (!$accountId?'INNER JOIN account acc ON acc.id = a.account_id AND acc.active ':'') .
            'INNER JOIN auction_lot_item AS ali ON bt.auction_id = ali.auction_id ' .
            'AND bt.lot_item_id = ali.lot_item_id AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ') ' .
            'WHERE  ' .
            'bt.user_id = ' . $this->escape($userId) . ' ' .
            'AND bt.deleted = false ' .

            'UNION ' .

            'SELECT  ' .
            'a.* ' .
            (!$accountId?',acc.name AS __account_name ':'') .
            'FROM ' .
            'absentee_bid AS ab ' .
            'INNER JOIN auction a ON a.id = ab.auction_id ' .
            'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$availableAuctionStatuses) . ') ' .
            ($accountId?'AND a.account_id = ' . $this->escape($accountId) . ' ':'') .
            (!$accountId?'INNER JOIN account acc ON acc.id = a.account_id AND acc.active ':'') .
            'INNER JOIN auction_lot_item AS ali ON ab.auction_id = ali.auction_id ' .
            'AND ab.lot_item_id = ali.lot_item_id AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ') ' .
            'WHERE  ' .
            'ab.user_id = ' . $this->escape($userId) . ' ' .
            'AND ab.max_bid > 0  ' .

            'GROUP BY a.id ' .

            'ORDER BY ' . $orderExpr .
            $limitClause;
        // @formatter:on

        $auctionRows = [];
        $dbResult = $this->query($query);

        if ($shouldReturnObject) {
            $auctionRows = Auction::InstantiateDbResult($dbResult);
        } else {
            while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
                $auctionRows[] = $row;
            }
        }
        return $auctionRows;
    }

    /**
     * Get participated auctions for this account
     *
     * @param string $orderExpr
     * @return array
     */
    public function getParticipatedAuctions(
        string $orderExpr = 'a.sale_num DESC'
    ): array {
        return $this->_getParticipatedAuctions(
            $this->getUserId(),
            $this->getAccountId(),
            $orderExpr,
            false,
            $this->getOffset(),
            $this->getLimit()
        );
    }

    /**
     * Get lots in watchlist
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id account.id null means there is no filtering by a.account_id and li.account_id but joining account table
     * @param string $orderExpr
     * @param bool $shouldReturnObject
     * @param int|null $offset null means there is no limit clause if $limit value has null value too
     * @param int|null $limit null means there is no limit clause if $offset value has null value too
     * @return int[] num_watchlist
     */
    protected function _getWatchlistItems(
        int $userId,
        ?int $accountId = null,
        string $orderExpr = 'a.sale_num, li.item_num, li.item_num_ext ',
        bool $shouldReturnObject = false,
        ?int $offset = null,
        ?int $limit = null
    ): array {
        $limitClause = '';
        if ($limit !== null && $offset !== null) {
            $limitClause = ' LIMIT ' . $offset . ', ' . $limit;
        }
        // @formatter:off
        $query =
            'SELECT ' .
            'uwl.*, ' .
            'ali.lot_num_prefix AS __lot_num_prefix, ' .
            'ali.lot_num AS __lot_num, ' .
            'ali.lot_num_ext AS __lot_num_ext, ' .
            'li.name AS __lot_name, ' .
            'a.name AS __auction_name, ' .
            (!$accountId?'acc.name AS __account_name, ':'') .
            'a.name AS __auction_sale_name, ' .
            'a.sale_num as sale_num, '.
            'a.sale_num_ext as sale_num_ext '.
            'FROM ' .
            'user_watchlist uwl ' .
            'INNER JOIN lot_item li ON li.id = uwl.lot_item_id ' .
            'AND li.active ' .
            ($accountId?'AND li.account_id = ' . $this->escape($accountId) . ' ':'') .
            'INNER JOIN auction a ON a.id = uwl.auction_id ' .
            'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$availableAuctionStatuses) . ') ' .
            ($accountId?'AND a.account_id = ' . $this->escape($accountId) . ' ':'') .
            (!$accountId?'INNER JOIN account acc ON acc.id = a.account_id AND acc.active ':'') .
            'LEFT JOIN auction_lot_item ali ON ali.auction_id = uwl.auction_id ' .
            'AND ali.lot_item_id = uwl.lot_item_id ' .
            'AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ') ' .
            'WHERE ' .
            'uwl.user_id = ' . $this->escape($userId) . ' ' .
            'ORDER BY ' . $orderExpr .
            $limitClause;
        // @formatter:on

        $watchItemRows = [];
        $dbResult = $this->query($query);
        if ($shouldReturnObject) {
            $watchItemRows = UserWatchlist::InstantiateDbResult($dbResult);
        } else {
            while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
                $watchItemRows[] = $row;
            }
        }
        return $watchItemRows;
    }

    /**
     * Get lots in watchlist
     *
     * @param string $orderExpr
     * @return int[] num_watchlist
     */
    public function getWatchlistItems(
        string $orderExpr = 'a.sale_num, li.item_num, li.item_num_ext '
    ): array {
        return $this->_getWatchlistItems(
            $this->getUserId(),
            $this->getAccountId(),
            $orderExpr,
            false,
            $this->getOffset(),
            $this->getLimit()
        );
    }

    /**
     * Get number of consigned lots
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id account.id null means there is no filtering by li.account_id but joining account table
     * @param string $orderExpr
     * @param bool $shouldReturnObject
     * @param int|null $offset null means there is no limit clause if $limit value has null value too
     * @param int|null $limit null means there is no limit clause if $offset value has null value too
     * @return array|LotItem[] num_consigned
     */
    protected function _getConsignedItems(
        int $userId,
        ?int $accountId = null,
        string $orderExpr = 'li.item_num DESC, li.item_num_ext DESC ',
        bool $shouldReturnObject = false,
        ?int $offset = null,
        ?int $limit = null
    ): array {
        $limitClause = '';
        if ($limit !== null && $offset !== null) {
            $limitClause = ' LIMIT ' . $offset . ', ' . $limit;
        }
        // @formatter:off
        $query =
            'SELECT ' .
            'li.* , a.id AS auction_id, a.sale_num AS sale_num, ' .
            'a.sale_num_ext AS sale_num_ext '.
            (!$accountId?', acc.name AS __account_name ':'') .
            'FROM ' .
            'lot_item li ' .
            'LEFT JOIN auction a ON li.auction_id = a.id '.
            (!$accountId?'INNER JOIN account acc ON acc.id = li.account_id AND acc.active ':'') .
            'WHERE ' .
            'li.consignor_id = ' . $this->escape($userId) . ' ' .
            'AND li.active ' .
            ($accountId?'AND li.account_id = ' . $this->escape($accountId):'') . ' '.
            'ORDER BY ' . $orderExpr .
            $limitClause;
        // @formatter:on
        $arrConsignedItems = [];
        $dbResult = $this->query($query);
        if ($shouldReturnObject) {
            $arrConsignedItems = LotItem::InstantiateDbResult($dbResult);
        } else {
            while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
                $arrConsignedItems[] = $row;
            }
        }
        return $arrConsignedItems;
    }

    /**
     * Get number of consigned lots
     *
     * @param string $orderExpr
     * @return array|LotItem[] num_consigned
     */
    public function getConsignedItems(
        string $orderExpr = 'li.item_num DESC, li.item_num_ext DESC '
    ): array {
        return $this->_getConsignedItems(
            $this->getUserId(),
            $this->getAccountId(),
            $orderExpr,
            false,
            $this->getOffset(),
            $this->getLimit()
        );
    }

    /**
     * Get sold items
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id account.id null means there is no filtering by li.account_id but joining account table
     * @param string $orderExpr
     * @param bool $shouldReturnObject
     * @param int|null $offset null means there is no limit clause if $limit value has null value too
     * @param int|null $limit null means there is no limit clause if $offset value has null value too
     * @return int[] num_consigned
     */
    protected function _getSoldItems(
        int $userId,
        ?int $accountId = null,
        string $orderExpr = 'a.sale_num DESC, li.item_num DESC, li.item_num_ext DESC ',
        bool $shouldReturnObject = false,
        ?int $offset = null,
        ?int $limit = null
    ): array {
        $limitClause = '';
        if ($limit !== null && $offset !== null) {
            $limitClause = ' LIMIT ' . $offset . ', ' . $limit;
        }
        // @formatter:off
        $query =
            'SELECT ' .
            'li.*, a.sale_num AS sale_num, a.sale_num_ext AS sale_num_ext,' .
            'uwn.username AS __winning_bidder ' .
            (!$accountId?', acc.name AS __account_name ':'') .
            'FROM ' .
            'lot_item li ' .
            (!$accountId?'INNER JOIN account acc ON acc.id = li.account_id AND acc.active ':'') .
            'LEFT JOIN ' .
            'user AS uwn ON uwn.id = li.winning_bidder_id ' .
            'LEFT JOIN auction AS a ON li.auction_id = a.id '.
            'WHERE ' .
            'li.consignor_id = ' . $this->escape($userId) . ' ' .
            'AND li.active ' .
            'AND li.hammer_price IS NOT NULL ' .
            'AND li.winning_bidder_id IS NOT NULL ' .
            ($accountId?'AND li.account_id = ' . $this->escape($accountId):'') . ' '.
            'ORDER BY ' . $orderExpr .
            $limitClause;
        // @formatter:on
        $arrSoldItems = [];
        $dbResult = $this->query($query);
        if ($shouldReturnObject) {
            $arrSoldItems = LotItem::InstantiateDbResult($dbResult);
        } else {
            while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
                $arrSoldItems[] = $row;
            }
        }
        return $arrSoldItems;
    }

    /**
     * Get sold items
     *
     * @param string $orderExpr
     * @return int[] num_consigned
     */
    public function getSoldItems(
        string $orderExpr = 'a.sale_num DESC, li.item_num DESC, li.item_num_ext DESC '
    ): array {
        return $this->_getSoldItems(
            $this->getUserId(),
            $this->getAccountId(),
            $orderExpr,
            false,
            $this->getOffset(),
            $this->getLimit()
        );
    }

    /**
     * Get lots last bid on
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id account.id null means there is no filtering by a.account_id but joining account table
     * @param string $orderExpr
     * @param bool $shouldReturnObject
     * @param int|null $offset null means there is no limit clause if $limit value has null value too
     * @param int|null $limit null means there is no limit clause if $offset value has null value too
     * @return array
     */
    protected function _getLastLotsBidOn(
        int $userId,
        ?int $accountId = null,
        string $orderExpr = '',
        bool $shouldReturnObject = false,
        ?int $offset = null,
        ?int $limit = null
    ): array {
        $limitClause = '';
        if ($limit !== null && $offset !== null) {
            $limitClause = ' LIMIT ' . $offset . ', ' . $limit;
        }
        // @formatter:off
        $query =
            'SELECT ' .
            'ali.lot_num_prefix AS lot_num_prefix, ' .
            'ali.lot_num AS lot_num, ' .
            'ali.lot_num_ext AS lot_num_ext, ' .
            $this->escape(Constants\UserAccountStatistic::BT_BID_TRANSACTION) . ' AS __bid_type, ' .
            'bt.created_on AS __bid_date, ' .
            'a.name AS __sale_name, ' .
            (!$accountId?'acc.name AS __account_name, ':'') .
            'li.name AS __lot_name, ' .
            'a.sale_num AS sale_num, '.
            'a.sale_num_ext AS sale_num_ext, '.
            'a.id AS auction_id '.

            'FROM ' .
            'bid_transaction bt ' .
            'INNER JOIN auction_lot_item ali ON ali.lot_item_id = bt.lot_item_id ' .
            'AND ali.auction_id = bt.auction_id ' .
            'AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ') ' .
            'INNER JOIN auction a ON a.id = bt.auction_id ' .
            'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$availableAuctionStatuses) . ') ' .
            ($accountId?'AND a.account_id = ' . $this->escape($accountId) . ' ':'') .
            'INNER JOIN lot_item li ON li.id = bt.lot_item_id AND li.active ' .
            (!$accountId?'INNER JOIN account acc ON acc.id = a.account_id AND acc.active ':'') .
            'WHERE ' .
            'bt.deleted = false ' .
            'AND bt.user_id = ' . $this->escape($userId) . ' ' .

            'UNION ' .

            'SELECT ' .
            'ali.lot_num_prefix AS lot_num_prefix, ' .
            'ali.lot_num AS lot_num, ' .
            'ali.lot_num_ext AS lot_num_ext, ' .
            $this->escape(Constants\UserAccountStatistic::BT_ABSENTEE_BID) . ' AS __bid_type, ' .
            'ab.created_on AS __bid_date, ' .
            'a.name AS __sale_name, ' .
            (!$accountId?'acc.name AS __account_name, ':'') .
            'li.name AS __lot_name, ' .
            'a.sale_num as sale_num, '.
            'a.sale_num_ext AS sale_num_ext, '.
            'a.id AS auction_id '.

            'FROM ' .
            'absentee_bid ab ' .
            'INNER JOIN auction_lot_item ali ON ali.lot_item_id = ab.lot_item_id ' .
            'AND ali.auction_id = ab.auction_id ' .
            'AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ') ' .
            'INNER JOIN auction a ON a.id = ab.auction_id ' .
            'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$availableAuctionStatuses) . ') ' .
            ($accountId?'AND a.account_id = ' . $this->escape($accountId) . ' ':'') .
            'INNER JOIN lot_item li ON li.id = ab.lot_item_id AND li.active ' .
            (!$accountId?'INNER JOIN account acc ON acc.id = a.account_id AND acc.active ':'') .
            'WHERE ' .
            'ab.max_bid > 0 ' .
            'AND ab.user_id = ' . $this->escape($userId) . ' ' .

            ' ORDER BY '.$orderExpr.' '.
            $limitClause;
        // @formatter:on
        $arrLotsBid = [];
        $dbResult = $this->query($query);
        if ($shouldReturnObject) {
            $arrLotsBid = AuctionLotItem::InstantiateDbResult($dbResult);
        } else {
            while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
                $arrLotsBid[] = $row;
            }
        }
        return $arrLotsBid;
    }

    /**
     * @param string $orderExpr
     * @return array
     */
    public function getLastLotsBidOn(
        string $orderExpr = ''
    ): array {
        return $this->_getLastLotsBidOn(
            $this->getUserId(),
            $this->getAccountId(),
            $orderExpr,
            false,
            $this->getOffset(),
            $this->getLimit()
        );
    }

    /**
     * Get total number of all bids for user
     *
     * @param int $userId
     * @param int|null $accountId account.id null means there is no filtering by a.account_id but joining account table
     * @return int
     */
    protected function _getNumberOfBids(
        int $userId,
        ?int $accountId = null
    ): int {
        $total = 0;
        // @formatter:off
        $query =
            'SELECT COUNT(1) AS cnt ' .
            'FROM ' .
            'bid_transaction bt ' .
            'INNER JOIN auction_lot_item ali ON ali.lot_item_id = bt.lot_item_id ' .
            'AND ali.auction_id = bt.auction_id ' .
            'AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ') ' .
            'INNER JOIN auction a ON a.id = bt.auction_id ' .
            'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$availableAuctionStatuses) . ') ' .
            ($accountId?'AND a.account_id = ' . $this->escape($accountId) . ' ':'') .
            'INNER JOIN lot_item li ON li.id = bt.lot_item_id AND li.active ' .
            (!$accountId?'INNER JOIN account acc ON acc.id = a.account_id AND acc.active ':'') .
            'WHERE ' .
            'bt.deleted = false ' .
            'AND bt.user_id = ' . $this->escape($userId);
        $dbResult = $this->query($query);
        $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        $total += $row['cnt'];

        $query =
            'SELECT  COUNT(1) AS cnt ' .
            'FROM ' .
            'absentee_bid ab ' .
            'INNER JOIN auction_lot_item ali ON ali.lot_item_id = ab.lot_item_id ' .
            'AND ali.auction_id = ab.auction_id ' .
            'AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ') ' .
            'INNER JOIN auction a ON a.id = ab.auction_id ' .
            'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$availableAuctionStatuses) . ') ' .
            ($accountId?'AND a.account_id = ' . $this->escape($accountId) . ' ':'') .
            'INNER JOIN lot_item li ON li.id = ab.lot_item_id AND li.active ' .
            (!$accountId?'INNER JOIN account acc ON acc.id = a.account_id AND acc.active ':'') .
            'WHERE ' .
            'ab.max_bid > 0 ' .
            'AND ab.user_id = ' . $this->escape($userId);
        // @formatter:on
        $dbResult = $this->query($query);
        $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        $total += $row['cnt'];

        return $total;
    }

    /**
     * Get total number of all bids for user
     *
     * @return int
     */
    public function getNumberOfBids(): int
    {
        return $this->_getNumberOfBids(
            $this->getUserId(),
            $this->getAccountId()
        );
    }

    /**
     * Get last lots won
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id account.id null means there is no filtering by li.account_id but joining account table
     * @param string $orderExpr
     * @param bool $shouldReturnObject
     * @param int|null $offset null means there is no limit clause if $limit value has null value too
     * @param int|null $limit null means there is no limit clause if $offset value has null value too
     * @return array
     */
    protected function _getLastLotsWon(
        int $userId,
        ?int $accountId = null,
        string $orderExpr = 'a.sale_num DESC',
        bool $shouldReturnObject = false,
        ?int $offset = null,
        ?int $limit = null
    ): array {
        $limitClause = '';
        if ($limit !== null && $offset !== null) {
            $limitClause = ' LIMIT ' . $offset . ', ' . $limit;
        }
        // @formatter:off
        $query =
            'SELECT ' .
            'li.*, ' .
            (!$accountId?'acc.name AS __account_name, ':'') .
            'a.name AS __sale_name, ' .
            'a.sale_num AS sale_num, '.
            'a.sale_num_ext AS sale_num_ext, '.
            'ali.lot_num_prefix AS __lot_num_prefix, ' .
            'ali.lot_num AS __lot_num, ' .
            'ali.lot_num_ext AS __lot_num_ext ' .
            'FROM ' .
            'lot_item li ' .
            (!$accountId?'INNER JOIN account acc ON acc.id = li.account_id AND acc.active ':'') .
            'LEFT JOIN auction a ON a.id = li.auction_id ' .
            'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$availableAuctionStatuses) . ') ' .
            'LEFT JOIN auction_lot_item ali ON ali.auction_id = li.auction_id ' .
            'AND ali.lot_item_id = li.id ' .
            'AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ') ' .
            'WHERE ' .
            'li.winning_bidder_id = ' . $this->escape($userId) . ' ' .
            ($accountId?'AND li.account_id = ' . $this->escape($accountId) . ' ':'') .
            'ORDER BY '.$orderExpr .' ' .
            $limitClause;
        // @formatter:on
        $arrLotsWon = [];
        $dbResult = $this->query($query);
        if ($shouldReturnObject) {
            $arrLotsWon = LotItem::InstantiateDbResult($dbResult);
        } else {
            while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
                $arrLotsWon[] = $row;
            }
        }
        return $arrLotsWon;
    }

    /**
     * @param string $orderExpr
     * @return array
     */
    public function getLastLotsWon(
        string $orderExpr = 'a.sale_num DESC'
    ): array {
        return $this->_getLastLotsWon(
            $this->getUserId(),
            $this->getAccountId(),
            $orderExpr,
            false,
            $this->getOffset(),
            $this->getLimit()
        );
    }

    /**
     * Get won lots
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id account.id null means there is no filtering by li.account_id but joining account table
     * @param string $orderExpr
     * @param bool $shouldReturnObject
     * @param int|null $offset null means there is no limit clause if $limit value has null value too
     * @param int|null $limit null means there is no limit clause if $offset value has null value too
     * @return int[] num_consigned
     */
    protected function _getWonItems(
        int $userId,
        ?int $accountId = null,
        string $orderExpr = 'li.item_num DESC, li.item_num_ext DESC ',
        bool $shouldReturnObject = false,
        ?int $offset = null,
        ?int $limit = null
    ): array {
        $limitClause = '';
        if ($limit !== null && $offset !== null) {
            $limitClause = ' LIMIT ' . $offset . ', ' . $limit;
        }
        // @formatter:off
        $query =
            'SELECT ' .
            'li.*, ' .
            (!$accountId?'acc.name AS __account_name, ':'') .
            'a.name AS __sale_name, ' .
            'a.sale_num AS sale_num, '.
            'a.sale_num_ext AS sale_num_ext, '.
            'ali.lot_num_prefix AS __lot_num_prefix, ' .
            'ali.lot_num AS __lot_num, ' .
            'ali.lot_num_ext AS __lot_num_ext ' .
            'FROM ' .
            'lot_item li ' .
            (!$accountId?'INNER JOIN account acc ON acc.id = li.account_id AND acc.active ':'') .
            'LEFT JOIN auction a ON a.id = li.auction_id ' .
            'AND a.auction_status_id IN (' . implode(',', Constants\Auction::$availableAuctionStatuses) . ') ' .
            'LEFT JOIN auction_lot_item ali ON ali.auction_id = li.auction_id ' .
            'AND ali.lot_item_id = li.id ' .
            'AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ') ' .
            'WHERE ' .
            'li.winning_bidder_id = ' . $this->escape($userId) . ' ' .
            'AND li.active ' .
            'AND li.hammer_price IS NOT NULL ' .
            ($accountId?'AND li.account_id = ' . $this->escape($accountId) . ' ' : '') .
            'ORDER BY ' . $orderExpr .
            $limitClause;
        // @formatter:on
        $arrSoldItems = [];
        $dbResult = $this->query($query);
        if ($shouldReturnObject) {
            $arrSoldItems = LotItem::InstantiateDbResult($dbResult);
        } else {
            while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
                $arrSoldItems[] = $row;
            }
        }
        return $arrSoldItems;
    }

    /**
     * Get won lots
     *
     * @param string $orderExpr
     * @return int[] num_consigned
     */
    public function getWonItems(
        string $orderExpr = 'li.item_num DESC, li.item_num_ext DESC '
    ): array {
        return $this->_getWonItems(
            $this->getUserId(),
            $this->getAccountId(),
            $orderExpr,
            false,
            $this->getOffset(),
            $this->getLimit()
        );
    }

    /**
     * Get array of items bid on.
     *
     * @param int $userId user.id
     * @param int|null $accountId account.id account.id null means there is no filtering by li.account_id but joining account table
     * @param string $orderExpr
     * @param bool $shouldReturnObject
     * @param int|null $offset null means there is no limit clause if $limit value has null value too
     * @param int|null $limit null means there is no limit clause if $offset value has null value too
     * @return array
     */
    protected function _getBidOnItems(
        int $userId,
        ?int $accountId = null,
        string $orderExpr = 'li.item_num DESC, li.item_num_ext DESC',
        bool $shouldReturnObject = true,
        ?int $offset = null,
        ?int $limit = null
    ): array {
        $limitClause = '';
        if ($limit !== null && $offset !== null) {
            $limitClause = ' LIMIT ' . $offset . ', ' . $limit;
        }
        // @formatter:off
        $query =
            'SELECT ' .
            'li.*, ' .
            (!$accountId?'acc.name AS __account_name, ':'') .
            'uwn.username AS __winning_bidder, ' .
            'ali.auction_id AS auction_id, '.
            'a.sale_num AS sale_num, '.
            'a.sale_num_ext AS sale_num_ext '.
            'FROM  ' .
            'bid_transaction AS bt ' .
            'INNER JOIN lot_item li ON li.id = bt.lot_item_id ' .
            'AND li.active ' .
            ($accountId?'AND li.account_id = ' . $this->escape($accountId) . ' ':'') .
            (!$accountId?'INNER JOIN account acc ON acc.id = li.account_id AND acc.active ':'') .
            'LEFT JOIN ' .
            'user AS uwn ON uwn.id = li.winning_bidder_id ' .
            'INNER JOIN auction_lot_item AS ali ON bt.auction_id = ali.auction_id ' .
            'AND bt.lot_item_id = ali.lot_item_id AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ') ' .
            'LEFT JOIN auction AS a ON a.id = ali.auction_id ' .
            'WHERE  ' .
            'bt.user_id = ' . $this->escape($userId) . ' ' .
            'AND bt.deleted = false ' .

            'UNION ' .

            'SELECT  ' .
            'li.*, ' .
            (!$accountId?'acc.name AS __account_name, ':'') .
            'uwn.username AS __winning_bidder, ' .
            'ali.auction_id AS auction_id, '.
            'a.sale_num AS sale_num, '.
            'a.sale_num_ext AS sale_num_ext '.
            'FROM ' .
            'absentee_bid AS ab ' .
            'INNER JOIN lot_item li ON li.id = ab.lot_item_id ' .
            'AND li.active ' .
            ($accountId?'AND li.account_id = ' . $this->escape($accountId) . ' ':'') .
            (!$accountId?'INNER JOIN account acc ON acc.id = li.account_id AND acc.active ':'') .
            'LEFT JOIN ' .
            'user AS uwn ON uwn.id = li.winning_bidder_id ' .
            'INNER JOIN auction_lot_item AS ali ON ab.auction_id = ali.auction_id ' .
            'AND ab.lot_item_id = ali.lot_item_id AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ') ' .
            'LEFT JOIN auction AS a ON a.id = ali.auction_id ' .
            'WHERE  ' .
            'ab.user_id = ' . $this->escape($userId) . ' ' .
            'AND ab.max_bid > 0  ' .

            'GROUP BY li.id ' .
            'ORDER BY ' . $orderExpr .
            $limitClause;
        // @formatter:on


        $arrItems = [];
        $dbResult = $this->query($query);

        if ($shouldReturnObject) {
            $arrItems = LotItem::InstantiateDbResult($dbResult);
        } else {
            while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
                $arrItems[] = $row;
            }
        }

        return $arrItems;
    }

    /**
     * Get array of items bid on.
     *
     * @param string $orderExpr
     * @return array
     */
    public function getBidOnItems(
        string $orderExpr = 'li.item_num DESC '
    ): array {
        return $this->_getBidOnItems(
            $this->getUserId(),
            $this->getAccountId(),
            $orderExpr,
            false,
            $this->getOffset(),
            $this->getLimit()
        );
    }

    /**
     * @param int $userId
     * @return array
     */
    protected function _loadUserAccountStatsTotal(int $userId): array
    {
        $query = 'SELECT 1';

        $fields = $this->userAccountStatsFieldsTotal;
        foreach ($fields as $field) {
            $query .= ', SUM(' . $field . ') AS ' . $field . ' ';
        }

        $fields = $this->userAccountStatsFieldsDate;
        foreach ($fields as $field) {
            $query .= ', MAX(' . $field . ') AS ' . $field . ' ';
        }

        $query .= 'FROM user_account_stats uas ' .
            'WHERE uas.user_id = ' . $this->escape($userId) . ' ';

        $dbResult = $this->query($query);
        $rows = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);

        $registeredNum = $rows['registered_auctions_num'];
        $participatedNum = $rows['participated_auctions_num'];
        $auctionsWonNum = $rows['auctions_won_num'];

        if ($registeredNum > 0) {
            $rows['auctions_won_perc'] = ($auctionsWonNum / $registeredNum) * 100;
            $rows['participated_auctions_perc'] = ($participatedNum / $registeredNum) * 100;
        }

        $lotsBidOnNum = $rows['lots_bid_on_num'];
        $lotsWonNum = $rows['lots_won_num'];

        if ($lotsBidOnNum > 0) {
            $rows['lots_won_perc'] = ($lotsWonNum / $lotsBidOnNum) * 100;
        }

        $watchlistNum = $rows['watchlist_items_num'];
        $watchlistWonNum = $rows['watchlist_items_won_num'];
        $watchlistBidOnNum = $rows['watchlist_items_bid_num'];

        if ($watchlistNum > 0) {
            $rows['watchlist_items_won_perc'] = ($watchlistWonNum / $watchlistNum) * 100;
            $rows['watchlist_items_bid_perc'] = ($watchlistBidOnNum / $watchlistNum) * 100;
        }

        return $rows;
    }

    public function loadNotExpired(int $userId, int $accountId, bool $isReadOnlyDb = false): ?UserAccountStats
    {
        return $this->createUserAccountStatsReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterUserId($userId)
            // Get not expired only, do not need to make it expired again, because this will change its place in the queue
            ->inlineCondition('uas.calculated_on IS NOT NULL AND uas.expired_on IS NULL')
            ->loadEntity();
    }

    /**
     * @return array
     */
    public function loadUserAccountStatsTotal(): array
    {
        return $this->_loadUserAccountStatsTotal($this->getUserId());
    }

    public function loadExpiredQueue(): array
    {
        return $this->createUserAccountStatsReadRepository()
            ->select(['user_id', 'uas.account_id'])
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->inlineCondition('uas.calculated_on IS NULL OR (uas.expired_on IS NOT NULL AND uas.expired_on >= uas.calculated_on)')
            ->orderByCalculatedOn()
            ->orderByExpiredOn()
            ->orderByUserId()
            ->orderByAccountId()
            ->loadRows();
    }

    public function detectQueueIndex(array $rows, int $userId, int $accountId): ?int
    {
        foreach ($rows as $key => $row) {
            if (
                $userId === (int)$row['user_id']
                && $accountId === (int)$row['account_id']
            ) {
                return $key + 1;
            }
        }
        return null;
    }
}
