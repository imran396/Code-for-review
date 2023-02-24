<?php
/**
 * SAM-4635 : Refactor tax report
 * https://bidpath.atlassian.net/browse/SAM-4635
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           4/7/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Tax\Base;

use Sam\AuctionLot\Order\Query\AuctionLotOrderMysqlQueryBuilderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\TaxReportFormConstants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Entity\FilterCurrencyAwareTrait;
use Sam\Core\Filter\Entity\FilterLocationAwareTrait;
use Sam\Core\Filter\Entity\FilterUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class QueryBuilder
 * @package Sam\Report\Tax\Base
 */
abstract class QueryBuilder extends CustomizableClass
{
    use AuctionLotOrderMysqlQueryBuilderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CurrencyLoaderAwareTrait;
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterCurrencyAwareTrait;
    use FilterDatePeriodAwareTrait;
    use FilterLocationAwareTrait;
    use FilterUserAwareTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;

    /** @var string[]|null */
    protected ?array $queryParts = null;

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

    /**
     * @return string
     */
    protected function getGroupClause(): string
    {
        return '';
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
     * @return string
     */
    protected function getSelectClause(): string
    {
        $n = "\n";
        $taHpBp = Constants\User::TAX_HP_BP;
        $taHp = Constants\User::TAX_HP;
        $taBp = Constants\User::TAX_BP;
        $tdsLegacy = Constants\Invoice::TDS_LEGACY;
        $query =
            // @formatter:off
            'SELECT ' .
                'i.created_on AS invoice_date, ' .
                'i.id AS invoice_id, ' .
                'i.invoice_no AS invoice_no, ' .
                'i.tax_designation AS tax_designation, ' .
                'u.id AS user_id, ' .
                'i.bidder_id AS bidder_id, ' .
                'ab.bidder_num AS bidder_num, ' .
                'u.username AS username, ' .
                'l.id AS location_id, ' .
                'l.name AS location_name, ' .
                'ali.lot_num AS lot_number, ' .
                'ali.lot_num_ext AS lot_number_ext, ' .
                'ali.lot_num_prefix AS lot_number_prefix, ' .
                'li.id AS lot_id, ' .
                'li.name AS lot_name, ' .
                'ii.hammer_price AS hammer_price, ' .
                'ii.buyers_premium AS premium_price, ' .
                "If(ii.sales_tax > 0, ii.sales_tax, 0) AS sales_tax, " .
                '(ii.hammer_price + ii.buyers_premium) AS sub_total, ' .
                'IF(i.tax_designation = ' . $tdsLegacy . ', ' .
                    'CASE ii.tax_application ' .
                        "WHEN {$taHpBp} THEN (ROUND((If(ii.sales_tax > 0,ii.sales_tax, 0)  / 100) * (ii.hammer_price + ii.buyers_premium),2)) " .
                        "WHEN {$taHp} THEN (ROUND((If(ii.sales_tax > 0,ii.sales_tax, 0)  / 100) * ii.hammer_price,2)) " .
                        "WHEN {$taBp} THEN (ROUND((If(ii.sales_tax > 0,ii.sales_tax, 0)  / 100) * ii.buyers_premium,2)) " .
                        "ELSE 0 " .
                    'END,' .
                    'ii.hp_tax_amount + ii.bp_tax_amount' .
                ') AS tax, ' .
                'ii.subtotal AS total, ' .
                'a.sale_num AS sale_num, ' .
                'a.sale_num_ext AS sale_num_ext, ' .
                'c.sign AS currency_sign, ' .
                'c.id AS currency_id, ' .
                'c.ex_rate AS ex_rate, ' .
                'i.account_id, '.
                'a.id AS auction_id ' . $n;
            // @formatter:on
        return $query;
    }

    /**
     * @return string
     */
    public function getFromClause(): string
    {
        $n = "\n";
        $from =
            // @formatter:off
            'FROM invoice i ' . $n .
            'INNER JOIN account ac_invoice ' .
                'ON ac_invoice.id = i.account_id AND ac_invoice.active ' . $n .
            'LEFT JOIN invoice_item ii ' .
                'ON ii.invoice_id = i.id ' . $n .
            'LEFT JOIN lot_item li ' .
                'ON li.id = ii.lot_item_id ' . $n .
            'LEFT JOIN auction a ' .
                'ON a.id = ii.auction_id ' . $n .
            'LEFT JOIN auction_lot_item ali ' .
                'ON ali.auction_id = a.id and ali.lot_item_id = li.id ' . $n .
            'LEFT JOIN currency c ' .
                'ON c.id = IFNULL(a.currency, ' .
                '(SELECT id ' .
                'FROM currency ' .
                'WHERE sign = "' . $this->getCurrencyLoader()->detectDefaultSign() . '" ' .
                    'AND account_id = ' . $this->cfg()->get('core->portal->mainAccountId') . ' ' .
                    'AND active) ' .
                ') ' . $n .
            'LEFT JOIN location l 
                ON l.id = a.invoice_location_id ' . $n .
            'LEFT JOIN user u 
                ON u.id = i.bidder_id ' . $n .
            'LEFT JOIN auction_bidder ab 
                ON ab.auction_id = a.id AND ab.user_id = u.id ';
            // @formatter:on
        return $from;
    }

    /**
     * @return string
     */
    public function getWhereClause(): string
    {
        $n = "\n";
        $where =
            'WHERE ' . $n .
            'ii.active = 1 ' . $n .
            'AND i.invoice_status_id IN ( ' . implode(',', Constants\Invoice::$openInvoiceStatuses) . ' ) ' . $n .
            'AND ali.lot_status_id IN ( ' . implode(',', Constants\Lot::$availableLotStatuses) . ' ) ' . $n;

        $accountId = $this->getFilterAccountId();
        if (is_array($accountId)) {
            $accountId = reset($accountId);
        }
        if ($accountId > 0) {
            $where .= 'AND i.account_id = ' . $this->escape($this->getFilterAccountId()) . ' ' . $n;
        }

        if ($this->isFilterDatePeriod()) {
            $where .= 'AND i.created_on BETWEEN ' . $this->escape($this->getFilterStartDateUtcIso()) .
                'AND ' . $this->escape($this->getFilterEndDateUtcIso()) . ' ' . $n;
        }

        if ($this->getFilterCurrencyId()) {
            $where .= 'AND c.id = ' . $this->escape($this->getFilterCurrencyId()) . ' ' . $n;
        }

        if ($this->getFilterUserId()) {
            $where .= 'AND i.bidder_id = ' . $this->escape($this->getFilterUserId()) . ' ' . $n;
        }

        if ($this->getFilterLocationId()) {
            $where .= 'AND l.id = ' . $this->escape($this->getFilterLocationId()) . ' ' . $n;
        }

        if ($this->getFilterAuctionId()) {
            $where .= 'AND a.id = ' . $this->escape($this->getFilterAuctionId()) . ' ' . $n;
        }

        return $where;
    }

    /**
     * @return string
     */
    protected function getOrderClause(): string
    {
        $n = "\n";
        $orderColumn = '';

        if ($this->isAscendingOrder()) {
            $sortDirection = 'ASC';
            $secondaryOrdering = $this->createAuctionLotOrderMysqlQueryBuilder()->buildLotOrderClause();
        } else {
            $sortDirection = 'DESC';
            $secondaryOrdering = $this->createAuctionLotOrderMysqlQueryBuilder()->buildLotOrderClause(false);
        }
        $column = $this->getSortColumn();
        $column = isset(TaxReportFormConstants::$orderKeysToColumns[$column]) ? $column : TaxReportFormConstants::ORD_CREATED_ON;
        foreach (TaxReportFormConstants::$orderKeysToColumns[$column] as $columnField) {
            $orderColumn .= $columnField . ' ' . $sortDirection . ',';
        }
        $sortOrder = $orderColumn . $secondaryOrdering;
        return ' ORDER BY ' . $sortOrder . $n;
    }
}
