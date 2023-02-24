<?php
/**
 * SAM-4856: Refactor Referrer report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-07-31
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Referrer\General\Html;

use OutOfRangeException;
use RangeException;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterCurrencyAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;

/**
 * Class QueryBuilder
 */
class QueryBuilder extends CustomizableClass
{
    use CurrencyLoaderAwareTrait;
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterCurrencyAwareTrait;
    use FilterDatePeriodAwareTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;

    public bool $isPurchasedChecked = false;
    public bool $isCollectedChecked = false;
    protected string $alias = 'ui';
    protected string $table = 'user_info';
    protected string $sortOrderDefaultIndex = 'user_cnt';

    /**
     * @var string[]
     */
    protected array $resultFieldsMapping = [
        'referrer_host' => 'ui.referrer_host',
        'user_cnt' => 'COUNT(1) AS `user_cnt`',
        'purchased' => 'SUM(IFNULL(li.hammer_price, 0)) AS purchased',
        'collected' => 'SUM(IFNULL(ii.hammer_price, 0)) AS collected',
    ];

    /**
     * @var string[]
     */
    protected array $availableReturnFields = [];
    protected array $returnFields = [];
    protected array $defaultSortOrders = [];
    protected array $orderFieldsMapping = [];
    /**
     * @var string[]
     */
    protected array $defaultReturnFields = [
        'referrer_host',
        'user_cnt',
        'purchased',
        'collected',
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
    public static function new(): static
    {
        return parent::_new(self::class);
    }

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
                . $queryParts['group']
                . $queryParts['order']
                . $queryParts['limit'];
        }
        return $resultQuery;
    }

    /**
     * @param bool $isPurchasedChecked
     * @return static
     */
    public function enablePurchasedChecked(bool $isPurchasedChecked): static
    {
        $this->isPurchasedChecked = $isPurchasedChecked;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPurchasedChecked(): bool
    {
        return $this->isPurchasedChecked;
    }

    /**
     * @param bool $isCollectedChecked
     * @return static
     */
    public function enableCollectedChecked(bool $isCollectedChecked): static
    {
        $this->isCollectedChecked = $isCollectedChecked;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCollectedChecked(): bool
    {
        return $this->isCollectedChecked;
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
            'select_count' => 'SELECT COUNT(DISTINCT IFNULL(ui.referrer_host, "N/A")) AS `referrer_host_total`',
            'from' => $this->getFromClause(),
            'where' => $this->getWhereClause(),
            'order' => $this->getOrderClause(),
            'group' => $this->getGroupClause(),
        ];
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
        $returnFields = $this->getReturnFields();
        $query = '';
        foreach ($returnFields as $returnFieldIndex) {
            if ($returnFieldIndex !== 'purchased' && $returnFieldIndex !== 'collected') {
                $query .= ($query ? ', ' : '');
                $query .= $this->availableReturnFields[$returnFieldIndex];
            }
            if ($returnFieldIndex === 'purchased' && $this->isPurchasedChecked()) {
                $query .= ($query ? ', ' : '');
                $query .= $this->availableReturnFields[$returnFieldIndex];
            }
            if ($returnFieldIndex === 'collected' && $this->isCollectedChecked()) {
                $query .= ($query ? ', ' : '');
                $query .= $this->availableReturnFields[$returnFieldIndex];
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
        $n = "\n";
        $join = " LEFT JOIN `user` u ON u.id = ui.user_id";

        if ($this->isCollectedChecked() || $this->isPurchasedChecked()) {
            $join .= ' LEFT JOIN lot_item li ON li.winning_bidder_id = u.id' . $n
                . ' LEFT JOIN auction a '
                . ' ON ((li.auction_id IS NOT NULL AND li.auction_id = a.id)'
                . ' OR (li.auction_id IS NULL AND a.id ='
                . ' (SELECT ali.auction_id FROM auction_lot_item ali'
                . ' WHERE ali.lot_item_id=li.id AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ')'
                . ' ORDER BY ali.created_on DESC LIMIT 1)'
                . ' ))' . $n;
            if ($this->isCollectedChecked()) {
                $join .= ' LEFT JOIN invoice_item ii ON ii.lot_item_id = li.id AND ii.`active`' . $n
                    . ' LEFT JOIN invoice i ON ii.invoice_id = i.id'
                    . ' AND i.invoice_status_id = ' . Constants\Invoice::IS_PAID;
                if ($this->getFilterAccountId()) {
                    $join .= ' AND (i.account_id = ' . $this->escape($this->getFilterAccountId()) . ')';
                }
            }
        }

        $query = " FROM `$this->table` as $this->alias ";

        $query .= $join;

        return $query;
    }

    /**
     * Get SQL Where Clause
     * @return string
     */
    protected function getWhereClause(): string
    {
        $n = "\n";
        $accountId = $this->getFilterAccountId();
        $dateFrom = $this->getFilterStartDateSysIso();
        $dateTo = $this->getFilterEndDateSysIso();

        if ($this->isCollectedChecked() || $this->isPurchasedChecked()) {
            $conditions =
                '((li.date_sold IS NOT NULL'
                . ' AND li.date_sold BETWEEN ' . $this->escape($dateFrom)
                . ' AND ' . $this->escape($dateTo) . ')' . $n
                . ' OR (li.date_sold IS NULL'
                . ' AND li.created_on BETWEEN ' . $this->escape($dateFrom)
                . ' AND ' . $this->escape($dateTo) . '))' . $n;
            // Add currency condition
            $defaultCurrencyId = $this->getCurrencyLoader()->detectDefaultCurrencyId();
            $currencyCond = ' AND (a.currency = ' . $this->escape($this->getFilterCurrencyId());
            if ($this->getFilterCurrencyId() === $defaultCurrencyId) {
                $currencyCond .= ' OR a.currency IS NULL';
            }
            $currencyCond .= ')';
            $conditions .= $currencyCond;
            if ($accountId) {
                $conditions .= ' AND (li.account_id = ' . $this->escape($accountId) . ')';
            }
        } else {
            $userPeriodCond = '(u.created_on BETWEEN ' . $this->escape($dateFrom) .
                ' AND ' . $this->escape($dateTo) . ')' . $n;
            $conditions = $userPeriodCond;
        }

        if ($accountId) {
            $conditions .= ' AND (u.account_id = ' . $this->escape($accountId) . ')';
        }

        $conditions .= ' AND (u.user_status_id = ' . Constants\User::US_ACTIVE . ')' . $n;

        return ' WHERE ' . $conditions;
    }

    /**
     * Get Group Clause
     * @return string
     */
    protected function getGroupClause(): string
    {
        return 'GROUP BY ui.referrer_host ';
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
        if ($sortOrder === 'username') {
            if (!in_array($sortOrder, $this->getReturnFields(), true)) {
                throw new OutOfRangeException(sprintf("Can't sort by %s if it is not in ReturnFields", $sortOrder));
            }
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
}
