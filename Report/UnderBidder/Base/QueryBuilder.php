<?php
/**
 * SAM-4636: Refactor under bidders report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-04-19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\UnderBidder\Base;

use OutOfRangeException;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;

/**
 * Class QueryBuilder
 */
abstract class QueryBuilder extends CustomizableClass
{
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;

    protected ?int $threshold = null;
    protected string $alias = 'ali';
    protected string $table = 'auction_lot_item';

    /** @var string[] */
    protected array $resultFieldsMapping = [
        'lot_num' => 'ali.`lot_num`, ali.`lot_num_ext`, ali.`lot_num_prefix`',
        'lot_item_id' => 'ali.`lot_item_id` AS li_id',
        'lot_item_name' => 'li.`name` AS li_name',
        'lot_item_name_short' => 'LEFT(li.`name`, 80) AS li_name',
        'auction_id' => 'ali.`auction_id` AS a_id',
        'auction_type' => 'a.`auction_type` AS a_type',
        'hammer_price' => 'li.`hammer_price` AS li_hp',
        'winning_bidder_id' => 'li.`winning_bidder_id` AS wb_id',
        'winning_bidder_customer_no' => 'wu.`customer_no` AS wb_cust_no',
        'winning_bidder_username' => 'wu.`username` AS wb_username',
        'winning_bidder_email' => 'wu.`email` AS wb_email',
        'winning_bidder_number' => 'wab.`bidder_num` AS wb_bidder_num',
        'under_bid' => 'IF(MAX(bt.max_bid)> MAX(bt.bid), MAX(bt.max_bid), MAX(bt.bid)) AS `amount`',
        'under_bidder_id' => 'bt.`user_id` AS ub_id',
        'under_bidder_customer_no' => 'u.`customer_no` AS ub_cust_no',
        'under_bidder_username' => 'u.`username` AS ub_username',
        'under_bidder_email' => 'u.`email` AS ub_email',
        'under_bidder_number' => 'ab.`bidder_num` AS ub_bidder_num',
    ];

    /**
     * @var string[]
     */
    protected array $availableReturnFields = [];
    protected array $returnFields = [];
    protected array $defaultSortOrders = [];
    protected string $sortOrderDefaultIndex = 'lot_num';
    /** @var string[] */
    protected array $defaultReturnFields = [
        'lot_num',
        'lot_item_id',
        'lot_item_name_short',
        'auction_id',
        'auction_type',
        'hammer_price',
        'winning_bidder_id',
        'winning_bidder_customer_no',
        'winning_bidder_username',
        'winning_bidder_email',
        'winning_bidder_number',
        'under_bid',
        'under_bidder_id',
        'under_bidder_customer_no',
        'under_bidder_username',
        'under_bidder_email',
        'under_bidder_number',
    ];

    /**
     * @var string[][]
     */
    protected array $orderFieldsMapping = [
        'lot_num' => [
            'asc' => 'ali.lot_num_prefix ASC, ali.lot_num ASC, ali.lot_num_ext ASC, amount ASC',
            'desc' => 'ali.lot_num_prefix DESC, ali.lot_num DESC, ali.lot_num_ext DESC, amount ASC',
        ],
        'lot_item_name' => [
            'asc' => 'li.name ASC, ali.lot_num_prefix ASC, ali.lot_num ASC, ali.lot_num_ext ASC, amount ASC',
            'desc' => 'li.name DESC, ali.lot_num_prefix DESC, ali.lot_num DESC, ali.lot_num_ext DESC, amount DESC',
        ],
        'hammer_price' => [
            'asc' => 'li.hammer_price ASC, ali.lot_num_prefix ASC, ali.lot_num ASC, ali.lot_num_ext ASC, amount ASC',
            'desc' => 'li.hammer_price DESC, ali.lot_num_prefix DESC, ali.lot_num DESC, ali.lot_num_ext DESC, amount DESC',
        ],
        'winning_bidder_number' => [
            'asc' => 'wab.bidder_num ASC, ali.lot_num_prefix ASC, ali.lot_num ASC, ali.lot_num_ext ASC, amount ASC',
            'desc' => 'wab.bidder_num DESC, ali.lot_num_prefix DESC, ali.lot_num DESC, ali.lot_num_ext DESC, amount DESC',
        ],
        'winning_bidder_username' => [
            'asc' => 'wu.username ASC, ali.lot_num_prefix ASC, ali.lot_num ASC, ali.lot_num_ext ASC, amount ASC',
            'desc' => 'wu.username DESC, ali.lot_num_prefix DESC, ali.lot_num DESC, ali.lot_num_ext DESC, amount DESC',
        ],
        'under_bidder_number' => [
            'asc' => 'ab.bidder_num ASC, ali.lot_num_prefix ASC, ali.lot_num ASC, ali.lot_num_ext ASC, amount ASC',
            'desc' => 'ab.bidder_num DESC, ali.lot_num_prefix DESC, ali.lot_num DESC, ali.lot_num_ext DESC, amount DESC',
        ],
        'under_bidder_username' => [
            'asc' => 'u.username ASC, ali.lot_num_prefix ASC, ali.lot_num ASC, ali.lot_num_ext ASC, amount ASC',
            'desc' => 'u.username DESC, ali.lot_num_prefix DESC, ali.lot_num DESC, ali.lot_num_ext DESC, amount DESC',
        ],
        'amount' => [
            'asc' => 'amount ASC, ali.lot_num_prefix ASC, ali.lot_num ASC, ali.lot_num_ext ASC',
            'desc' => 'amount DESC, ali.lot_num_prefix DESC, ali.lot_num DESC, ali.lot_num_ext DESC',
        ],
    ];

    /**
     * @var int[]
     */
    protected array $lotCategoryIds = [];
    /**
     * @var string[]|null
     */
    protected ?array $queryParts = null;

    /**
     * @return static
     */
    public function initInstance(): static
    {
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
            if ($this->isGroupClause()) {
                $countQuery = $this->buildCountQueryForGrouping();
            } else {
                $countQuery = $queryParts['select_count']
                    . $queryParts['from']
                    . $queryParts['where']
                    . $queryParts['group'];
            }
        }
        return $countQuery;
    }

    /**
     * @return string
     */
    public function buildCountQueryForGrouping(): string
    {
        $queryParts = $this->getQueryParts();
        return $queryParts['select_count']
            . "FROM `{$this->table}` AS {$this->alias}2 "
            . "WHERE EXISTS ("
            . "SELECT {$this->alias}.id "
            . $queryParts['from']
            . $queryParts['where'] . ($queryParts['where'] ? ' AND' : ' WHERE')
            . " {$this->alias}2.id = {$this->alias}.id "
            . $queryParts['group'] . ")";
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
                . $queryParts['group']
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
            'group' => $this->getGroupClause(),
        ];
    }

    /**
     * @param int|null $threshold null leads to threshold out of range exception
     * @return static
     */
    public function setThreshold(?int $threshold): static
    {
        $this->threshold = $threshold;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getThreshold(): ?int
    {
        return $this->threshold;
    }

    /**
     * @param string[] $queryParts
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
     */
    public function getReturnFields(): array
    {
        return $this->returnFields;
    }

    /**
     * Set Return Fields
     * @param string[] $returnFields
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
     * @param string[] $availableReturnFields
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
        return '';
    }

    /**
     * Get From Clause
     * @param bool $isCountQuery
     * @return string
     */
    protected function getFromClause(bool $isCountQuery = false): string
    {
        $threshold = $this->getThreshold();
        if (
            $threshold === null
            || Floating::lt($threshold, 0)
        ) {
            throw new OutOfRangeException('Threshold out of range: ' . $threshold);
        }

        $query = "FROM auction_lot_item ali " .
            "INNER JOIN lot_item li ON li.id=ali.lot_item_id AND li.auction_id = ali.auction_id AND li.active " .
            "INNER JOIN account ac_lot ON ac_lot.id = li.account_id AND ac_lot.active ";

        if (
            !$isCountQuery
            && count(
                array_intersect(
                    $this->returnFields,
                    [
                        'winning_bidder_customer_no',
                        'winning_bidder_username',
                        'winning_bidder_email'
                    ]
                )
            ) > 0
        ) {
            $query .= "LEFT JOIN user wu ON wu.id = li.winning_bidder_id ";
        }

        if (
            !$isCountQuery
            && count(array_intersect($this->returnFields, ['winning_bidder_number'])) > 0
        ) {
            $query .= "LEFT JOIN auction_bidder wab ON wab.auction_id=ali.auction_id AND wab.user_id = li.winning_bidder_id ";
        }

        $auctionStatusList = implode(',', Constants\Auction::$notDeletedAuctionStatuses);
        $query .= "INNER JOIN auction a ON a.id = li.auction_id AND a.auction_status_id IN (" . $auctionStatusList . ") "
            . "INNER JOIN bid_transaction bt ON bt.auction_id = li.auction_id "
            . "AND bt.lot_item_id=li.id "
            . "AND bt.deleted = false "
            . "AND ((bt.user_id IS NOT NULL "
            . "AND bt.user_id!=li.winning_bidder_id) OR li.winning_bidder_id IS NULL)";

        $thresholdPercent = $threshold / 100;
        if (Floating::gt($thresholdPercent, 0)) {
            $query .= "AND li.hammer_price <= IF(a.auction_type='" . Constants\Auction::TIMED . "', bt.max_bid, bt.bid)/" . $thresholdPercent . " ";
        }

        if (
            !$isCountQuery
            && count(
                array_intersect(
                    $this->returnFields,
                    ['under_bidder_customer_no', 'under_bidder_username', 'under_bidder_email']
                )
            ) > 0
        ) {
            $query .= "INNER JOIN user u ON u.id=bt.user_id ";
        }

        if (
            !$isCountQuery
            && count(array_intersect($this->returnFields, ['under_bidder_number'])) > 0
        ) {
            $query .= "INNER JOIN auction_bidder ab ON ab.auction_id = ali.auction_id AND ab.user_id = bt.user_id ";
        }

        return $query;
    }

    /**
     * Get SQL Where Clause
     * @return string
     */
    protected function getWhereClause(): string
    {
        $auctionId = $this->getFilterAuctionId();
        if (is_array($auctionId)) {
            $auctionId = reset($auctionId);
        }
        if ($auctionId === null || $auctionId <= 0) {
            throw new OutOfRangeException('AuctionId out of range: ' . $this->getFilterAuctionId());
        }

        $lotStatusList = implode(',', Constants\Lot::$wonLotStatuses);
        $query = 'WHERE ali.lot_status_id IN (' . $lotStatusList . ') ' .
            'AND ali.auction_id = ' . $this->escape($auctionId) . ' ';

        $accountId = $this->getFilterAccountId();
        if (is_array($accountId)) {
            $accountId = reset($accountId);
        }
        if ($accountId > 0) {
            $query .= 'AND li.account_id = ' . $this->escape($accountId) . ' ';
        }

        return $query;
    }

    /**
     * Get Group Clause
     * @return string
     */
    protected function getGroupClause(): string
    {
        return 'GROUP BY li.id, bt.user_id ';
    }

    /**
     * Get Limit Clause
     * @return string
     */
    protected function getLimitClause(): string
    {
        $limit = $this->getLimit();
        if ($limit === null) {
            return '';
        }
        $query = $limit;

        $offset = $this->getOffset();
        if ($offset) {
            $query = $offset . ',' . $query;
        }
        return sprintf('LIMIT %s ', $query);
    }

    /**
     * Return ORDER part of the query
     * @return string
     */
    protected function getOrderClause(): string
    {
        $sortOrder = $this->getSortColumn() ?: $this->sortOrderDefaultIndex;
        switch ($sortOrder) {
            case 'winning_bidder_number':
            case 'winning_bidder_username':
            case 'under_bidder_number':
            case 'under_bidder_username':
                if (!in_array($sortOrder, $this->returnFields, true)) {
                    throw new OutOfRangeException(sprintf('Can\'t sort by %s if it is not in ReturnFields', $sortOrder));
                }
                break;
        }
        return sprintf('ORDER BY %s ', $this->orderFieldsMapping[$sortOrder][$this->isAscendingOrder() ? 'asc' : 'desc']);
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

    /**
     * @return bool
     */
    protected function isGroupClause(): bool
    {
        return (bool)strpos($this->getQueryParts()['from'], 'JOIN');
    }
}
