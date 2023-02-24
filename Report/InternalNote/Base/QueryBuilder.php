<?php
/**
 * SAM-4631 : Refactor internal notes report
 * https://bidpath.atlassian.net/browse/SAM-4631
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           4/24/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\InternalNote\Base;

use OutOfBoundsException;
use OutOfRangeException;
use RangeException;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class QueryBuilder
 * @package Sam\Report\InternalNote\Base
 */
abstract class QueryBuilder extends CustomizableClass
{
    use DbConnectionTrait;
    use FilterAuctionAwareTrait;
    use FilterDatePeriodAwareTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;

    /** @var string[] */
    protected array $availableReturnFields = [
        'user_id' => 'DISTINCT(u.id) AS user_id ',
        'invoice_id' => 'i.id AS invoice_id',
        'invoice_number' => 'i.invoice_no AS invoice_number',
        'username' => 'u.username AS username ',
        'customer_no' => 'u.customer_no AS customer_no ',
        'internal_note' => 'i.internal_note AS internal_note ',
        'internal_note_modified' => 'i.internal_note_modified AS modified_on',
    ];
    /** @var string[] */
    protected array $defaultReturnFields = [
        'user_id',
        'invoice_id',
        'invoice_number',
        'username',
        'customer_no',
        'internal_note',
        'internal_note_modified',
    ];
    /** @var string[] */
    protected array $returnFields = [];
    /** @var string[][] */
    protected array $orderFieldsMapping = [
        'invoice_number' => [
            'asc' => 'i.invoice_no ASC ',
            'desc' => 'i.invoice_no DESC ',
        ],
        'username' => [
            'asc' => 'u.username ASC ',
            'desc' => 'u.username DESC ',
        ],
        'customer_no' => [
            'asc' => 'u.customer_no ASC ',
            'desc' => 'u.customer_no DESC '
        ],
        'internal_note' => [
            'asc' => 'i.internal_note ASC ',
            'desc' => 'i.internal_note DESC '
        ],
        'internal_note_modified' => [
            'asc' => 'i.internal_note_modified ASC ',
            'desc' => 'i.internal_note_modified DESC '
        ],
    ];
    /** @var string */
    protected string $sortOrderDefaultIndex = 'invoice_number';

    /** @var string[]|null */
    protected ?array $queryParts = null;

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->returnFields = $this->defaultReturnFields;
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
                . $queryParts['where']
                . $queryParts['group'];
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

    /**
     * Build query
     */
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

    // /**
    //  * @param mixed $sortColumnIndex
    //  * @return string
    //  */
    // public function getOrderColumnByIndex($sortColumnIndex)
    // {
    //     $sortColumnIndex = Cast::toInt($sortColumnIndex);
    //     $orderColumn = $this->sortOrderDefaultIndex;
    //     if ($sortColumnIndex !== null
    //         && $sortColumnIndex < count($this->sortOrders)
    //     ) {
    //         $orderColumns = array_keys($this->sortOrders);
    //         $orderColumn = $orderColumns[$sortColumnIndex];
    //     }
    //     return $orderColumn;
    // }

    /**
     * Return SELECT part of query depending on
     * which ReturnFields are selected
     *
     * @return string
     * @throws RangeException
     */
    protected function getSelectClause(): string
    {
        $query = '';
        foreach ($this->returnFields as $strReturnFieldIndex) {
            $query .= ($query ? ', ' : '');
            $query .= $this->availableReturnFields[$strReturnFieldIndex];
        }

        if ($query === '') {
            throw new RangeException('No ReturnFields defined');
        }
        return sprintf('SELECT %s ', $query);
    }

    /**
     * Return FROM part of query with JOINs depending on
     * which ReturnFields are selected
     *
     * @return string
     */
    protected function getFromClause(): string
    {
        $query = ' FROM  invoice i ' .
            'LEFT JOIN user u ON u.id = i.bidder_id ';
        return $query;
    }

    /**
     * Return WHERE part of the query
     * @return string
     */
    protected function getWhereClause(): string
    {
        $n = "\n";
        $query = ' WHERE i.invoice_status_id IN ( ' . implode(',', Constants\Invoice::$openInvoiceStatuses) . ' ) ';
        $query .= " AND i.internal_note <> '' ";

        if ($this->isFilterDatePeriod()) {
            $query .= ' AND i.internal_note_modified'
                . ' BETWEEN ' . $this->escape($this->getFilterStartDateSysIso())
                . ' AND ' . $this->escape($this->getFilterEndDateSysIso()) . ' ';
        }
        if ($this->getFilterAuctionId()) {
            $query .= ' AND i.id IN (SELECT invoice_id ' . $n .
                'FROM invoice_item ' . $n .
                'WHERE active AND auction_id = ' . $this->escape($this->getFilterAuctionId()) . ') ';
        }

        return $query;
    }

    /**
     * Return LIMIT part of the query
     * @return string
     */
    protected function getLimitClause(): string
    {
        if ($this->getLimit() === null) {
            return '';
        }
        if ($this->getLimit() > 0) {
            $query = $this->getLimit();
        } else {
            throw new OutOfBoundsException(sprintf('Query limit can\'t be %s', $this->getLimit()));
        }

        if ($this->getOffset() > 0) {
            $query = $this->getOffset() . ',' . $query;
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
            case 'user_id':
            case 'invoice_id':
            case 'username':
            case 'customer_no':
            case 'internal_note_modified':
                if (!in_array($sortOrder, $this->returnFields, true)) {
                    throw new OutOfRangeException(sprintf('Can\'t sort by %s if it is not in ReturnFields', $sortOrder));
                }
                break;
        }
        return sprintf('ORDER BY %s ', $this->orderFieldsMapping[$sortOrder][$this->isAscendingOrder() ? 'asc' : 'desc']);
    }

    /**
     * @return string
     */
    protected function getGroupClause(): string
    {
        return '';
    }
}
