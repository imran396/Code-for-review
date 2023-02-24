<?php
/**
 * SAM-4627: Refactor auction list report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-05-02
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\AuctionList\Base;

use OutOfBoundsException;
use OutOfRangeException;
use RangeException;
use Sam\Bidder\AuctionBidder\Query\AuctionBidderQueryBuilderHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class QueryBuilder
 */
abstract class QueryBuilder extends CustomizableClass
{
    use AuctionBidderQueryBuilderHelperCreateTrait;
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterDatePeriodAwareTrait;
    use LimitInfoAwareTrait;
    use SettingsManagerAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;


    protected string $alias = 'ali';
    protected string $sortOrderDefaultIndex = 'invoice_id';
    protected string $table = 'auction_lot_item';
    protected string $nl = "\n";

    /**
     * @var string[]
     */
    protected array $resultFieldsMapping = [
        'account_name' => '',
        'auction_name' => '',
        'description' => '',
        'auction_date' => '',
        'start_date' => '',
        'start_closing_date' => '',
        'timezone' => '',
        'timezone_location' => '',
        'end_date' => '',
        'auction_type' => '',
        'event_type' => '',
        'sale_num' => '',
        'sale_num_ext' => '',
        'page_views' => '',
        'id' => '',
        'categories' => '',
        'lots' => '',
        'lots_with_bids' => '',
        'total_bids' => '',
        'current_bid_total' => '',
        'current_bid_total_above_reserve' => '',
        'max_bid_total' => '',
        'max_bid_total_above_reserve' => '',
        'max_bid_total_above_cost' => '',
        'lots_sold_total' => '',
        'lots_sold_online' => '',
        'registered_bidders' => '',
        'approved_bidders' => '',
        'live_bidders' => '',
        'under_bidders' => '',
        'winning_bidders' => '',
        'hammer_price' => '',
        'invoices' => '',
        'invoice_shipped' => '',
        'invoice_paid' => '',
        'invoice_pending' => '',
        'invoice_open' => '',
        'invoice_canceled' => '',
        'invoice_total' => '',
        'invoice_deleted' => '',
        'currency_sign' => '',
        'currency_code' => '',
    ];


    protected array $availableReturnFields = [];
    protected array $defaultSortOrders = [];
    protected array $returnFields = [];

    /**
     * @var string[]
     */
    protected array $defaultReturnFields = [
        'account_name',
        'auction_name',
        'description',
        'auction_date',
        'timezone',
        'timezone_location',
        'start_date',
        'start_closing_date',
        'end_date',
        'auction_type',
        'event_type',
        'sale_num',
        'sale_num_ext',
        'page_views',
        'id',
        'categories',
        'lots',
        'lots_with_bids',
        'total_bids',
        'current_bid_total',
        'current_bid_total_above_reserve',
        'max_bid_total',
        'max_bid_total_above_reserve',
        'max_bid_total_above_cost',
        'lots_sold_total',
        'lots_sold_online',
        'registered_bidders',
        'approved_bidders',
        'live_bidders',
        'under_bidders',
        'winning_bidders',
        'hammer_price',
        'invoices',
        'invoice_shipped',
        'invoice_paid',
        'invoice_pending',
        'invoice_open',
        'invoice_canceled',
        'invoice_total',
        'invoice_deleted',
        'currency_sign',
        'currency_code',
    ];

    /**
     * @var string[][]
     */
    protected array $orderFieldsMapping = [
        'account_name' => [
            'asc' => 'acc.name ASC ',
            'desc' => 'acc.name DESC '
        ],
        'auction_name' => [
            'asc' => 'a.name ASC ',
            'desc' => 'a.name DESC '
        ],
        'description' => [
            'asc' => 'description ASC ',
            'desc' => 'description DESC '
        ],
        'auction_date' => [
            'asc' => 'auction_date ASC ',
            'desc' => 'auction_date DESC '
        ],
        'start_date' => [
            'asc' => 'start_date ASC ',
            'desc' => 'start_date DESC '
        ],
        'start_closing_date' => [
            'asc' => 'start_closing_date ASC ',
            'desc' => 'start_closing_date DESC '
        ],
        'end_date' => [
            'asc' => 'end_date ASC ',
            'desc' => 'end_date DESC '
        ],
        'auction_type' => [
            'asc' => 'auction_type ASC ',
            'desc' => 'auction_type DESC '
        ],
        'sale_num' => [
            'asc' => 'sale_num ASC ',
            'desc' => 'sale_num DESC '
        ],
        'page_views' => [
            'asc' => 'page_views ASC ',
            'desc' => 'page_views DESC '
        ],
        'id' => [
            'asc' => 'id ASC ',
            'desc' => 'id DESC '
        ],
        'categories' => [
            'asc' => 'categories ASC ',
            'desc' => 'categories DESC '
        ],
        'lots' => [
            'asc' => 'lots ASC ',
            'desc' => 'lots DESC '
        ],
        'lots_with_bids' => [
            'asc' => 'lots_with_bids ASC ',
            'desc' => 'lots_with_bids DESC '
        ],
        'total_bids' => [
            'asc' => 'total_bids ASC ',
            'desc' => 'total_bids DESC '
        ],
        'current_bid_total' => [
            'asc' => 'current_bid_total ASC ',
            'desc' => 'current_bid_total DESC '
        ],
        'current_bid_total_above_reserve' => [
            'asc' => 'current_bid_total_above_reserve ASC ',
            'desc' => 'current_bid_total_above_reserve DESC '
        ],
        'max_bid_total' => [
            'asc' => 'max_bid_total ASC ',
            'desc' => 'max_bid_total DESC '
        ],
        'max_bid_total_above_reserve' => [
            'asc' => 'max_bid_total_above_reserve ASC ',
            'desc' => 'max_bid_total_above_reserve DESC '
        ],
        'max_bid_total_above_cost' => [
            'asc' => 'max_bid_total_above_cost ASC ',
            'desc' => 'max_bid_total_above_cost DESC '
        ],
        'lots_sold_total' => [
            'asc' => 'lots_sold_total ASC ',
            'desc' => 'lots_sold_total DESC '
        ],
        'lots_sold_online' => [
            'asc' => 'lots_sold_online ASC ',
            'desc' => 'lots_sold_online DESC '
        ],
        'registered_bidders' => [
            'asc' => 'registered_bidders ASC ',
            'desc' => 'registered_bidders DESC '
        ],
        'approved_bidders' => [
            'asc' => 'approved_bidders ASC ',
            'desc' => 'approved_bidders DESC '
        ],
        'live_bidders' => [
            'asc' => 'live_bidders ASC ',
            'desc' => 'live_bidders DESC '
        ],
        'under_bidders' => [
            'asc' => 'under_bidders ASC ',
            'desc' => 'under_bidders DESC '
        ],
        'winning_bidders' => [
            'asc' => 'winning_bidders ASC ',
            'desc' => 'winning_bidders DESC '
        ],
        'hammer_price' => [
            'asc' => 'hammer_price ASC ',
            'desc' => 'hammer_price DESC '
        ],
        'invoices' => [
            'asc' => 'invoices ASC ',
            'desc' => 'invoices DESC '
        ],
        'invoice_total' => [
            'asc' => 'invoice_total ASC ',
            'desc' => 'invoice_total DESC '
        ],
        'invoice_shipped' => [
            'asc' => 'invoice_shipped ASC ',
            'desc' => 'invoice_shipped DESC '
        ],
        'invoice_paid' => [
            'asc' => 'invoice_paid ASC ',
            'desc' => 'invoice_paid DESC '
        ],
        'invoice_pending' => [
            'asc' => 'invoice_pending ASC ',
            'desc' => 'invoice_pending DESC '
        ],
        'invoice_open' => [
            'asc' => 'invoice_open ASC ',
            'desc' => 'invoice_open DESC '
        ],
        'invoice_canceled' => [
            'asc' => 'invoice_canceled ASC ',
            'desc' => 'invoice_canceled DESC '
        ],
        'invoice_deleted' => [
            'asc' => 'invoice_deleted ASC ',
            'desc' => 'invoice_deleted DESC '
        ],
    ];

    /**
     * @var string[]|null
     */
    protected ?array $queryParts = null;

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->initResultFieldsMapping();
        $this->initSortFields();
        return $this;
    }

    /**
     * Build Count Query
     * @return string|null
     */
    public function buildCountQuery(): ?string
    {
        $countQuery = null;
        $queryParts = $this->getQueryParts();
        if ($queryParts) {
            $countQuery = $queryParts['select_count']
                . $queryParts['from']
                . $queryParts['where'];
        }
        return $countQuery;
    }

    /**
     * Build Result Query
     * @return string|null
     */
    public function buildResultQuery(): ?string
    {
        $resultQuery = null;
        $queryParts = $this->getQueryParts();
        if ($queryParts) {
            $resultQuery = $queryParts['select']
                . $queryParts['from']
                . $queryParts['where']
                . $queryParts['order']
                . $queryParts['limit'];
        }
        return $resultQuery;
    }

    /**
     * Get Query Parts
     * @return array
     */
    public function getQueryParts(): array
    {
        if ($this->queryParts === null) {
            $this->buildQueryParts();
        }
        // we want to rebuild LIMIT clause in every call
        $this->queryParts['limit'] = $this->getLimitClause();
        return $this->queryParts;
    }

    protected function buildQueryParts(): void
    {
        $this->queryParts = [
            'select' => $this->getSelectClause(),
            'select_count' => 'SELECT COUNT(1) AS `total`',
            'from' => $this->getFromClause(),
            'where' => $this->getWhereClause(),
            'order' => $this->getOrderClause(),
        ];
    }

    /**
     * @param array $queryParts
     * @return static
     * @noinspection PhpUnused
     */
    public function setQueryParts(array $queryParts): static
    {
        $this->queryParts = $queryParts;
        return $this;
    }

    /**
     * Get Return Fields
     * @return array
     * @noinspection PhpUnused
     */
    public function getReturnFields(): array
    {
        return $this->returnFields;
    }

    /**
     * Set Return Fields
     * @param array $returnFields
     * @return static
     */
    public function setReturnFields(array $returnFields): static
    {
        $this->returnFields = $returnFields;
        return $this;
    }

    /**
     * Get Available Return Fields
     * @return string[]
     * @noinspection PhpUnused
     */
    public function getAvailableReturnFields(): array
    {
        return $this->availableReturnFields;
    }

    /**
     * Set Available Return Fields
     * @param array $availableReturnFields
     * @return static
     */
    public function setAvailableReturnFields(array $availableReturnFields): static
    {
        $this->availableReturnFields = $availableReturnFields;
        return $this;
    }

    /**
     * Get resultFieldsMapping
     * @return array
     */
    public function getResultFieldsMapping(): array
    {
        return $this->resultFieldsMapping;
    }

    /**
     * Get defaultReturnFields
     * @return string[]
     */
    public function getDefaultReturnFields(): array
    {
        return $this->defaultReturnFields;
    }

    /**
     * define output default sort orders
     * based on User export default fields
     */
    protected function setDefaultSortOrders(): void
    {
        foreach ($this->availableReturnFields as $key => $returnField) {
            $tempField = substr($returnField, 0, strpos($returnField, "AS"));
            $sortProps = [
                'asc' => $tempField . " ASC",
                'desc' => $tempField . " DESC",
            ];
            $this->defaultSortOrders[$key] = $sortProps;
        }
        if (empty($this->orderFieldsMapping)) {
            $this->orderFieldsMapping = $this->defaultSortOrders;
        }
    }

    /**
     * @return string
     */
    protected function getSelectClause(): string
    {
        $query = '';
        $n = $this->nl;
        foreach ($this->returnFields as $returnFieldIndex) {
            $field = $this->availableReturnFields[$returnFieldIndex];
            if ($field !== '') {
                $query .= ($query ? ', ' . $n : '');
                $query .= $field;
            }
        }

        if ($query === '') {
            throw new RangeException('No ReturnFields defined');
        }
        return sprintf('SELECT %s ', $query);
    }

    /**
     * Get From Clause
     * @return string
     */
    protected function getFromClause(): string
    {
        $n = $this->nl;
        return "FROM " . $n .
            "auction AS a " . $n .
            "INNER JOIN account AS acc ON acc.id = a.account_id AND acc.active " . $n .
            "LEFT JOIN timezone AS atz ON atz.id = a.timezone_id " . $n .
            "LEFT JOIN auction_cache AS ac ON ac.auction_id = a.id " . $n;
    }

    /**
     * Get SQL Where Clause
     * @return string
     */
    protected function getWhereClause(): string
    {
        $dateFromIso = $this->escape($this->getFilterStartDateUtcIso());
        $dateToIso = $this->escape($this->getFilterEndDateUtcIso());
        $accountId = $this->escape($this->getFilterAccountId());

        $auctionDateSql = "IF (auction_type = '" . Constants\Auction::TIMED . "', " .
            "a.end_date, " .
            "a.start_closing_date " .
            ") ";
        $n = $this->nl;
        $query = " WHERE a.auction_status_id IN (" . implode(',', Constants\Auction::$availableAuctionStatuses) . ") " . $n .
            "AND ($auctionDateSql >= $dateFromIso AND $auctionDateSql <= $dateToIso) " . $n;
        if ($accountId !== "NULL") {
            $query .= "AND a.account_id = $accountId ";
        }
        return $query;
    }

    /**
     * Get Limit Clause
     * @return string
     */
    protected function getLimitClause(): string
    {
        if ($this->limit === null) {
            return '';
        }
        if ($this->limit > 0) {
            $query = $this->limit;
        } else {
            throw new OutOfBoundsException(sprintf('Query limit can\'t be %s', $this->limit));
        }

        if ($this->offset > 0) {
            $query = $this->offset . ',' . $query;
        }
        return sprintf(' LIMIT %s ', $query);
    }

    /**
     * Return ORDER part of the query
     * @return string
     */
    protected function getOrderClause(): string
    {
        $sortOrder = $this->getSortColumn() ?: $this->sortOrderDefaultIndex;
        switch ($sortOrder) {
            case 'description':
            case 'auction_date':
            case 'start_date':
            case 'start_closing_date':
            case 'end_date':
            case 'auction_type':
            case 'sale_num':
            case 'page_views':
            case 'id':
            case 'categories':
            case 'lots':
            case 'lots_with_bids':
            case 'total_bids':
            case 'current_bid_total':
            case 'current_bid_total_above_reserve':
            case 'max_bid_total':
            case 'max_bid_total_above_reserve':
            case 'lots_sold_total':
            case 'lots_sold_online':
            case 'registered_bidders':
            case 'approved_bidders':
            case 'live_bidders':
            case 'under_bidders':
            case 'winning_bidders':
            case 'hammer_price':
            case 'invoices':
            case 'invoice_total':
            case 'invoice_shipped':
            case 'invoice_paid':
            case 'invoice_pending':
            case 'invoice_open':
            case 'invoice_canceled':
            case 'invoice_deleted':
                if (!in_array($sortOrder, $this->returnFields, true)) {
                    throw new OutOfRangeException(sprintf('Can\'t sort by %s if it is not in ReturnFields', $sortOrder));
                }
                break;
        }
        return sprintf('ORDER BY %s ', $this->orderFieldsMapping[$sortOrder][$this->isAscendingOrder() ? 'asc' : 'desc']);
    }

    /**
     * Initialize ResultFieldsMapping
     */
    protected function initResultFieldsMapping(): void
    {
        // @formatter:off
        $categoriesSql = "SELECT COUNT(DISTINCT(lic.lot_category_id)) " .
            "FROM auction_lot_item AS ali " .
            "INNER JOIN lot_item AS li ON ali.lot_item_id = li.id AND ali.lot_status_id IN (".implode(',',Constants\Lot::$inAuctionStatuses).") AND li.active " .
            "LEFT JOIN lot_item_category AS lic ON ali.lot_item_id = lic.lot_item_id " .
            "INNER JOIN lot_category AS lc ON lic.lot_category_id = lc.id AND lc.active " .
            "WHERE ali.auction_id = a.id ";

        $lots = "SELECT %s " .
            "FROM auction_lot_item AS ali " .
            "INNER JOIN lot_item AS li ON ali.lot_item_id = li.id AND li.active " .
            "INNER JOIN auction_lot_item_cache AS alic ON alic.auction_lot_item_id=ali.id " .
            "WHERE ali.auction_id = a.id ";

        $lotsCountSql = sprintf($lots, 'COUNT(1) ') .
            " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") ";

        $lotsWithBidsSql = sprintf($lots, 'COUNT(1) ') .
            " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") AND alic.bid_count > 0 ";

        $totalBidsSql = sprintf($lots, 'SUM(alic.bid_count) ') .
            " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") ";

        $pageViews = sprintf($lots, 'SUM(alic.view_count)  ') .
            " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") ";

        $currentBidTotalSql = sprintf($lots, 'SUM(alic.current_bid) ') .
            " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") ";

        $currentBidTotalAboveReserveSql = sprintf($lots, 'SUM(alic.current_bid) ') .
            " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") " .
            " AND li.reserve_price > 0 " .
            " AND alic.current_bid >= li.reserve_price ";

        $maxBidTotalSql = sprintf($lots, 'SUM(alic.current_max_bid) ') .
            " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") ";

        $maxBidTotalAboveReserveSql = sprintf($lots, 'SUM(alic.current_max_bid) ') .
            " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") " .
            " AND li.reserve_price > 0 " .
            " AND alic.current_max_bid >= li.reserve_price ";

        $maxBidTotalAboveCostSql = sprintf($lots, 'SUM(alic.current_max_bid) ') .
            " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") " .
            " AND li.cost > 0 " .
            " AND alic.current_max_bid >= li.cost ";

        $lotsSoldTotalSql = sprintf($lots, 'SUM(li.hammer_price) ') .
            " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$wonLotStatuses) . ") " .
            " AND li.auction_id = a.id ";

        $lotsSoldOnlineSql = sprintf($lots, 'SUM(li.hammer_price) ') .
            " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$wonLotStatuses) . ") " .
            " AND li.auction_id = a.id " .
            " AND li.internet_bid ";

        $registeredBiddersSql = "SELECT COUNT(1) " .
            "FROM auction_bidder AS ab " .
            "WHERE ab.auction_id = a.id ";

        $approvedBiddersSql = $registeredBiddersSql .
            "AND " . $this->createAuctionBidderQueryBuilderHelper()->makeApprovedBidderWhereClause('ab');

        $underBiddersSql = "SELECT COUNT(DISTINCT(bt.user_id)) " .
            "FROM auction_lot_item AS ali " .
            "INNER JOIN lot_item AS li ON li.id=ali.lot_item_id " .
                "AND li.auction_id = ali.auction_id AND li.active " .
            "INNER JOIN bid_transaction AS bt ON bt.auction_id = li.auction_id " .
                "AND bt.lot_item_id=li.id AND bt.user_id IS NOT NULL " .
                "AND bt.user_id != li.winning_bidder_id " .
            "INNER JOIN user u ON u.id=bt.user_id " .
            "INNER JOIN auction_bidder ab " .
                "ON ab.auction_id = ali.auction_id AND ab.user_id = bt.user_id " .
            "WHERE ali.lot_status_id IN (" . implode(',', Constants\Lot::$wonLotStatuses) . ") AND ali.auction_id = a.id ";

        $winningBiddersSql = "SELECT COUNT(DISTINCT(li.winning_bidder_id)) " .
            "FROM auction_lot_item AS ali " .
            "INNER JOIN lot_item AS li ON li.id=ali.lot_item_id " .
                "AND li.auction_id = ali.auction_id AND li.active " .
            "WHERE ali.lot_status_id IN (" . implode(',', Constants\Lot::$wonLotStatuses) . ") AND ali.auction_id = a.id ";

        $liveBiddersSql = "if (a.auction_type = '" . Constants\Auction::TIMED . "',0, (SELECT COUNT(DISTINCT(bt.user_id)) " .
            "FROM auction_lot_item AS ali " .
            "INNER JOIN lot_item AS li ON li.id=ali.lot_item_id " .
                "AND li.auction_id = ali.auction_id AND li.active " .
            "INNER JOIN bid_transaction AS bt ON bt.auction_id = li.auction_id " .
                "AND bt.lot_item_id=li.id AND bt.user_id IS NOT NULL " .
            "WHERE ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") AND ali.auction_id = a.id)) ";

        $hammerPrice = sprintf($lots, 'SUM(li.hammer_price)  ') .
            " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") ";

        $invoicesSql =
        $invoiceShippedSql =
        $invoicePaidSql =
        $invoicePendingSql =
        $invoiceOpenSql =
        $invoiceCanceledSql =
        $invoiceDeletedSql = '\'N\\A\'';

        $multipleSaleInvoice = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::MULTIPLE_SALE_INVOICE, $this->getSystemAccountId());
        if (!$multipleSaleInvoice) {

            $ttInvoice = Constants\Payment::TT_INVOICE;
            $invoicesSql = "SELECT " .
                "COUNT(DISTINCT(it.invoice_id)) " .
                "FROM invoice_item AS it  " .
                "WHERE it.active " .
                "AND it.auction_id = a.id";

            $invoiceSum = "SELECT " .
                "SUM( " .
                    "ROUND(i.bid_total, 2)  + " .
                    "ROUND(i.buyers_premium, 2) + " .
                    "ROUND(i.tax, 2) + " .
                    "IFNULL(ROUND(i.shipping_fees, 2), 0) + " .
                    "IFNULL(ROUND(i.extra_charges, 2), 0) - " .
                    "(IF(i.cash_discount = true, " .
                        "(ROUND(i.bid_total, 2)  + ROUND(i.buyers_premium, 2)) * (select IFNULL(cash_discount,0) from setting_invoice where account_id = i.account_id) / 100," .
                        " 0))  " .
                ") - SUM(IFNULL(p.amount, 0)) " .
                "FROM invoice AS i " .
                "LEFT JOIN payment AS p ON p.tran_id = i.id AND p.tran_type = '{$ttInvoice}' AND p.active = true " .
                    "AND i.invoice_status_id != " . Constants\Invoice::IS_DELETED  . " " .
                "WHERE i.id IN (" .
                    "SELECT " .
                    "DISTINCT(it.invoice_id) " .
                    "FROM invoice_item AS it " .
                    "%s " .
                    "AND it.auction_id = a.id " .
                    "AND it.active " .
                ") " .
                "%s ";
            $invoiceShippedSql = sprintf($invoiceSum, "WHERE it.active",
                "AND i.invoice_status_id = " . Constants\Invoice::IS_SHIPPED  . " ");
            $invoicePaidSql = "SELECT " .
                    "SUM(p.amount) " .
                "FROM payment AS p " .
                "WHERE p.tran_id IN (" .
                    "SELECT " .
                        "DISTINCT(it.invoice_id) " .
                    "FROM invoice_item AS it  " .
                    "WHERE it.active " .
                    "AND it.auction_id = a.id " .
                ") " .
                "AND p.tran_type = '" . Constants\Payment::TT_INVOICE . "' " .
                "AND p.active = true";
            $invoicePendingSql = sprintf($invoiceSum, "WHERE it.active",
                "AND i.invoice_status_id = " . Constants\Invoice::IS_PENDING  . " ");
            $invoiceOpenSql = sprintf($invoiceSum, "WHERE it.active",
                "AND i.invoice_status_id = " . Constants\Invoice::IS_OPEN  . " ");
            $invoiceCanceledSql = sprintf($invoiceSum, "WHERE it.active",
                "AND i.invoice_status_id = " . Constants\Invoice::IS_CANCELED   . " ");
            $invoiceDeletedSql = sprintf($invoiceSum, "WHERE it.active = false",
                "AND i.invoice_status_id = " . Constants\Invoice::IS_DELETED   . " ");
        }
        $this->resultFieldsMapping['account_name'] = 'acc.name AS account_name';
        $this->resultFieldsMapping['auction_name'] = 'a.name AS auction_name';

        $this->resultFieldsMapping['description'] = 'a.description AS description';

        $this->resultFieldsMapping['auction_date'] = 'IF (auction_type = \'T\', ' .
            'a.end_date, ' .
            'a.start_closing_date ' .
        ') AS auction_date';

        $this->resultFieldsMapping['start_date'] = 'a.start_date AS start_date';
        $this->resultFieldsMapping['start_closing_date'] = 'a.start_closing_date AS start_closing_date';
        $this->resultFieldsMapping['timezone'] = 'a.timezone_id AS timezone_id';
        $this->resultFieldsMapping['timezone_location'] = 'atz.location AS timezone_location';

        $this->resultFieldsMapping['end_date'] = 'a.end_date AS end_date';

        $this->resultFieldsMapping['auction_type'] = 'a.auction_type AS auction_type';
        $this->resultFieldsMapping['event_type'] = 'a.event_type AS event_type';
        $this->resultFieldsMapping['sale_num'] = 'a.sale_num AS sale_num';
        $this->resultFieldsMapping['sale_num_ext'] = 'a.sale_num_ext AS sale_num_ext';
        $this->resultFieldsMapping['page_views'] = '(' . $pageViews . ') AS page_views';//'ac.total_views AS page_views';

        $this->resultFieldsMapping['id'] = 'a.id AS id';

        $this->resultFieldsMapping['categories'] = '(' . $categoriesSql . ') AS categories';

        $this->resultFieldsMapping['lots'] = '(' . $lotsCountSql . ') AS lots'; //'ac.total_lots AS lots';
        $this->resultFieldsMapping['lots_with_bids'] = '(' . $lotsWithBidsSql . ') AS lots_with_bids';
        $this->resultFieldsMapping['total_bids'] = '(' . $totalBidsSql . ') AS total_bids';

        $this->resultFieldsMapping['current_bid_total'] =  '(' . $currentBidTotalSql . ') AS current_bid_total'; //'ac.total_bid AS current_bid_total';
        $this->resultFieldsMapping['current_bid_total_above_reserve'] = '(' . $currentBidTotalAboveReserveSql . ') AS current_bid_total_above_reserve';

        $this->resultFieldsMapping['max_bid_total'] = '(' . $maxBidTotalSql . ') AS max_bid_total'; //'ac.total_max_bid AS max_bid_total';
        $this->resultFieldsMapping['max_bid_total_above_reserve'] = '(' . $maxBidTotalAboveReserveSql . ') AS max_bid_total_above_reserve';
        $this->resultFieldsMapping['max_bid_total_above_cost'] = '(' . $maxBidTotalAboveCostSql . ') AS max_bid_total_above_cost';

        $this->resultFieldsMapping['lots_sold_total'] = '(' . $lotsSoldTotalSql . ') AS lots_sold_total';
        $this->resultFieldsMapping['lots_sold_online'] = '(' . $lotsSoldOnlineSql . ') AS lots_sold_online';

        $this->resultFieldsMapping['registered_bidders'] = '(' . $registeredBiddersSql . ') AS registered_bidders';
        $this->resultFieldsMapping['approved_bidders'] = '(' . $approvedBiddersSql . ') AS approved_bidders';
        $this->resultFieldsMapping['live_bidders'] = '(' . $liveBiddersSql . ') AS live_bidders';
        $this->resultFieldsMapping['under_bidders'] = '(' . $underBiddersSql . ') AS under_bidders';
        $this->resultFieldsMapping['winning_bidders'] = '(' . $winningBiddersSql . ') AS winning_bidders';
        $this->resultFieldsMapping['hammer_price'] = '(' . $hammerPrice . ') AS hammer_price'; //'ac.total_hammer_price AS hammer_price';
        $this->resultFieldsMapping['invoices'] = '(' . $invoicesSql . ') AS invoices';
        $this->resultFieldsMapping['invoice_shipped'] = '@invoice_shipped := (' . $invoiceShippedSql . ') AS invoice_shipped';
        $this->resultFieldsMapping['invoice_paid'] = '@invoice_paid := (' . $invoicePaidSql . ') AS invoice_paid';
        $this->resultFieldsMapping['invoice_pending'] = '@invoice_pending := (' . $invoicePendingSql . ') AS invoice_pending';
        $this->resultFieldsMapping['invoice_open'] = '@invoice_open := (' . $invoiceOpenSql . ') AS invoice_open';
        $this->resultFieldsMapping['invoice_canceled'] = '@invoice_canceled := (' . $invoiceCanceledSql . ') AS invoice_canceled';
        $this->resultFieldsMapping['invoice_total'] = '(IFNULL(@invoice_shipped,0) + ' .
                                                             'IFNULL(@invoice_paid,0) + ' .
                                                             'IFNULL(@invoice_pending,0) + ' .
                                                             'IFNULL(@invoice_open,0) + ' .
                                                             'IFNULL(@invoice_canceled,0)) AS invoice_total';
        $this->resultFieldsMapping['invoice_deleted'] = '(' . $invoiceDeletedSql . ') AS invoice_deleted';
        $this->resultFieldsMapping['currency_sign'] = '(SELECT name FROM currency WHERE currency.id = a.currency) AS currency_sign';
        $this->resultFieldsMapping['currency_code'] = '(SELECT code FROM currency WHERE currency.id = a.currency) AS currency_code';
        // @formatter:on
    }

    /**
     * Initialize SortFields
     */
    protected function initSortFields(): void
    {
        $availableReturnFields = $this->getResultFieldsMapping();
        $returnFields = $this->getDefaultReturnFields();
        $this->setAvailableReturnFields($availableReturnFields)
            ->setReturnFields($returnFields)
            ->setDefaultSortOrders();
    }
}
