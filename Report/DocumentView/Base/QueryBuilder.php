<?php
/**
 * SAM-4630: Refactor document view report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-05-07
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\DocumentView\Base;

use OutOfRangeException;
use RangeException;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Entity\FilterUserAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class QueryBuilder
 */
abstract class QueryBuilder extends CustomizableClass
{
    use FilterUserAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterDatePeriodAwareTrait;
    use DbConnectionTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;

    protected string $nl = "\n";
    protected string $alias = 'udv';
    protected string $table = 'user_document_views';
    protected string $sortOrderDefaultIndex = 'date_viewed';

    /**
     * @var string[]
     */
    protected array $resultFieldsMapping = [
        'date' => 'udv.`date_viewed` AS date',
        'item_num' => 'li.`item_num` AS item_num, li.`item_num_ext` AS item_num_ext',
        'test_auction' => 'a.`test_auction`',
        'auction_name' => 'a.`name` AS auction_name',
        'sale_num' => 'a.`sale_num`',
        'sale_num_ext' => 'a.`sale_num_ext`',
        'lot_number' => 'ali.`lot_num`, ali.`lot_num_ext`, ali.`lot_num_prefix`',
        'lot_name' => 'li.`name` AS lot_name',
        'custom_field' => 'licf.`name` AS custom_field',
        'document_name' => 'document_name AS document_name',
        'user_id' => 'usr.`id` AS user_id',
        'username' => 'usr.`username` AS username',
        'first_name' => 'uinf.`first_name` AS first_name',
        'last_name' => 'uinf.`last_name` AS last_name',
        'address' => "(CONCAT(
                        IF(TRIM(`ubill`.`address`) = '','',`ubill`.`address`),
                        IF(TRIM(`ubill`.`address2`) = '','',CONCAT(',',`ubill`.`address2`) ),
                        IF(TRIM(`ubill`.`address3`) = '','',CONCAT(',',`ubill`.`address3`) )
                        )
                     ) AS address",
        'city' => 'ubill.`city` AS city',
        'state' => 'ubill.`state` AS state',
        'zip' => 'ubill.`zip` AS zip',
        'phone' => 'ubill.`phone` AS phone',
    ];

    /**
     * @var string[]
     */
    protected array $availableReturnFields = [];
    protected array $returnFields = [];
    protected array $defaultSortOrders = [];

    /**
     * @var string[]
     */
    protected array $defaultReturnFields = [
        'date',
        'item_num',
        'test_auction',
        'auction_name',
        'sale_num',
        'sale_num_ext',
        'lot_number',
        'lot_name',
        'custom_field',
        'document_name',
        'user_id',
        'username',
        'first_name',
        'last_name',
        'address',
        'city',
        'state',
        'zip',
        'phone',
    ];

    /**
     * @var string[][]
     */
    protected array $orderFieldsMapping = [
        'date' => [
            'asc' => 'udv.date_viewed ASC ',
            'desc' => 'udv.date_viewed DESC '
        ],
        'item_num' => [
            'asc' => 'li.item_num ASC, li.item_num_ext ASC',
            'desc' => 'li.item_num DESC, li.item_num_ext DESC'
        ],
        'test_auction' => [
            'asc' => 'a.test_auction ASC',
            'desc' => 'a.test_auction DESC'
        ],
        'auction_name' => [
            'asc' => 'a.name ASC',
            'desc' => 'a.name DESC'
        ],
        'sale_num' => [
            'asc' => 'a.sale_num ASC',
            'desc' => 'a.sale_num DESC'
        ],
        'sale_num_ext' => [
            'asc' => 'a.sale_num_ext ASC',
            'desc' => 'a.sale_num_ext DESC'
        ],
        'lot_number' => [
            'asc' => 'ali.lot_num ASC, ali.lot_num_ext ASC, ali.lot_num_prefix ASC',
            'desc' => 'ali.lot_num DESC, ali.lot_num_ext DESC, ali.lot_num_prefix DESC'
        ],
        'lot_name' => [
            'asc' => 'li.name ASC',
            'desc' => 'li.name DESC'
        ],
        'custom_field' => [
            'asc' => 'licf.name ASC',
            'desc' => 'licf.name DESC'
        ],
        'document_name' => [
            'asc' => 'document_name ASC',
            'desc' => 'document_name DESC'
        ],
        'user_id' => [
            'asc' => 'usr.id ASC',
            'desc' => 'usr.id DESC'
        ],
        'username' => [
            'asc' => 'usr.username ASC',
            'desc' => 'usr.username DESC'
        ],
        'first_name' => [
            'asc' => 'uinf.first_name ASC',
            'desc' => 'uinf.first_name DESC'
        ],
        'last_name' => [
            'asc' => 'uinf.last_name ASC',
            'desc' => 'uinf.last_name DESC'
        ],
        'address' => [
            'asc' => 'ubill.address ASC',
            'desc' => 'ubill.address DESC'
        ],
        'city' => [
            'asc' => 'ubill.city ASC',
            'desc' => 'ubill.city DESC'
        ],
        'state' => [
            'asc' => 'ubill.state ASC',
            'desc' => 'ubill.state DESC'
        ],
        'zip' => [
            'asc' => 'ubill.zip ASC',
            'desc' => 'ubill.zip DESC'
        ],
        'phone' => [
            'asc' => 'ubill.phone ASC',
            'desc' => 'ubill.phone DESC'
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
        $this->availableReturnFields = [];
        $this->returnFields = [];
        $this->defaultSortOrders = [];
        $this->lotCategoryIds = [];
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
     * Get Select Clause
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
        return "FROM `user_document_views` `udv`
            INNER JOIN `auction_lot_item` `ali` ON `ali`.`id` = `udv`.`auction_lot_item_id` AND ali.lot_status_id IN (" . implode(', ', Constants\Lot::$availableLotStatuses) . ")
            INNER JOIN `lot_item` `li` ON `li`.`id` = `ali`.`lot_item_id` AND li.active
            INNER JOIN `auction` `a` ON `a`.`id` = `ali`.`auction_id` AND a.auction_status_id IN (" . implode(', ', Constants\Auction::$availableAuctionStatuses) . ")
            INNER JOIN `lot_item_cust_data` `licd` ON `licd`.id = `udv`.`lot_item_cust_data_id`
            INNER JOIN `lot_item_cust_field` `licf` ON `licf`.`id` = `licd`.`lot_item_cust_field_id` AND licf.active
            INNER JOIN `user` `usr` ON `usr`.id = `udv`.`user_id` AND `usr`.`user_status_id` = " . Constants\User::US_ACTIVE . "
            INNER JOIN `user_info` `uinf` ON `uinf`.`user_id` = `udv`.`user_id`
            LEFT OUTER JOIN `user_billing` `ubill` ON `ubill`.`user_id` = `udv`.`user_id` ";
    }

    /**
     * Get SQL Where Clause
     * @return string
     */
    protected function getWhereClause(): string
    {
        $n = $this->nl;
        $query = "WHERE 1=1 " . $n;

        if ($this->isFilterDatePeriod()) {
            $query .= "AND `udv`.`date_viewed` BETWEEN " . $this->escape($this->getFilterStartDateNoTimeSysIso() . ' 00:00:00') .
                " AND " . $this->escape($this->getFilterEndDateNoTimeSysIso() . ' 23:59:59') . " " . $n;
        }

        if ($this->getFilterUserId()) {
            $query .= "AND `udv`.`user_id` = " . $this->escape($this->getFilterUserId()) . " " . $n;
        }

        if ($this->getFilterAuctionId()) {
            $query .= "AND `a`.`id` = " . $this->escape($this->getFilterAuctionId()) . " " . $n;
        }

        if ($this->getFilterAccountId()) {
            $query .= "AND a.account_id = " . $this->escape($this->getFilterAccountId()) . " " . $n;
        }

        return $query;
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
            case 'date':
            case 'item_num':
            case 'auction_name':
            case 'lot_number':
            case 'lot_name':
            case 'custom_field':
            case 'document_name':
            case 'username':
            case 'first_name':
            case 'last_name':
            case 'address':
            case 'city':
            case 'state':
            case 'zip':
            case 'phone':
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
     * @noinspection PhpUnused
     */
    protected function isGroupClause(): bool
    {
        return (bool)strpos($this->getQueryParts()['from'], 'JOIN');
    }
}
