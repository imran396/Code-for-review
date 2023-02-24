<?php
/**
 * SAM-4633:Refactor sales staff report
 * https://bidpath.atlassian.net/browse/SAM-4633
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/10/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SaleStaff\Base;

use Sam\AuctionLot\Order\Query\AuctionLotOrderMysqlQueryBuilderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\SaleStaffReportFormConstants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Report\SaleStaff\Common\FilterAwareTrait;

/**
 * Class QueryBuilder
 * @package Sam\Report\SaleStaff\Base
 */
abstract class QueryBuilder extends CustomizableClass
{
    use AuctionLotOrderMysqlQueryBuilderCreateTrait;
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterAwareTrait;
    use FilterDatePeriodAwareTrait;
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
    public function getSelectClause(): string
    {
        $n = "\n";
        $taHpBp = Constants\User::TAX_HP_BP;
        $taHp = Constants\User::TAX_HP;
        $taBp = Constants\User::TAX_BP;
        $tdsLegacy = Constants\Invoice::TDS_LEGACY;
        // @formatter:off
        $query =
            'SELECT ' . $n .
                'DISTINCT li.item_num AS item_num, li.item_num_ext AS item_num_ext,' . $n .
                'ucon.added_by AS sales_staff, ' . $n .
                'l.address AS office_location, ' . $n .
                'li.id AS lot_id, ' . $n .
                'li.account_id AS lot_account_id, ' . $n .
                'ucon.account_id AS account_id, ' . $n .
                'li.item_num AS item_num, ' . $n .
                'li.item_num_ext AS item_num_ext, ' . $n .
                'li.name AS lot_name, ' . $n .
                'ii.hammer_price AS hammer_price, ' . $n .
                'li.winning_bidder_id AS bidder_id, ' . $n .
                'ubid.username AS buyer_username, ' . $n .
                'uibid.company_name AS buyer_company, ' . $n .
                'li.consignor_id AS consignor_id, ' . $n .
                'ucon.username AS consignor_username, ' . $n .
                'uicon.company_name AS consignor_company, ' . $n .
                'li.date_sold AS date_sold, ' . $n .
                'a.name AS auc_name, ' . $n .
                'a.id AS auc_id, ' . $n .
                'a.test_auction AS test_auction, ' . $n .
                'ali.lot_num_prefix AS lot_num_prefix, ' . $n .
                'ali.lot_num AS lot_num, ' . $n .
                'ali.lot_num_ext AS lot_num_ext, ' . $n .
                'ii.buyers_premium AS buyers_premium, ' . $n .
                'ii.tax_application AS tax_application, ' . $n .
                'IF(i.tax_designation = ' . $tdsLegacy . ', ' . $n .
                    'CASE ii.tax_application ' . $n .
                        "WHEN {$taHpBp} THEN (ii.hammer_price + ii.buyers_premium) * (ii.sales_tax / 100) " . $n .
                        "WHEN {$taHp} THEN (ii.hammer_price) * (ii.sales_tax / 100) " . $n .
                        "WHEN {$taBp} THEN (ii.buyers_premium) * (ii.sales_tax / 100) " . $n .
                        "ELSE 0 " . $n .
                    'END,' .
                    'ii.hp_tax_amount + ii.bp_tax_amount' .
                ') AS sales_tax, ' . $n .
                'ii.subtotal AS total, ' . $n .
                'i.invoice_status_id AS inv_status, ' . $n .
                "IFNULL(i.invoice_date, i.created_on) AS inv_date, " . $n .
                'IFNULL(inv_pay_created.payment_date, null) AS payment_date, ' . $n .
                'IFNULL(inv_pay_created.pay_created, null) AS pay_created, ' . $n .
                'IFNULL(inv_pay_created.pay_date, null) AS pay_date, ' . $n .
                'CASE i.invoice_status_id ' . $n .
                    "WHEN 1 THEN 'Open' " . $n .
                    "WHEN 2 THEN 'Pending' " . $n .
                    "WHEN 3 THEN 'Paid' " . $n .
                    "WHEN 4 THEN 'Shipped' " . $n .
                    "ELSE 'Not invoiced' " . $n .
                'END 
                 AS inv_status_name, ' . $n .
                'uibid.referrer AS buyer_referrer, ' . $n .
                'uibid.referrer_host AS buyer_referrer_host ' . $n;
        // @formatter:on
        return $query;
    }

    /**
     * @return string
     */
    public function getFromClause(): string
    {
        $n = "\n";
        $invoiceStatusList = implode(',', Constants\Invoice::$openInvoiceStatuses);
        // @formatter:off
        $from =
            'FROM ' . $n .
            'lot_item AS li ' . $n .
            'INNER JOIN account AS ac_lot 
                ON ac_lot.id = li.account_id AND ac_lot.active ' . $n .
            'LEFT JOIN auction AS a 
                ON a.id = li.auction_id ' . $n .
            'LEFT JOIN auction_lot_item AS ali 
                ON ali.auction_id = li.auction_id AND ali.lot_item_id = li.id AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ')' . $n .
            'LEFT JOIN user AS ubid 
                ON ubid.id = li.winning_bidder_id ' . $n .
            'LEFT JOIN user AS ucon 
                ON ucon.id = li.consignor_id ' . $n .
            'LEFT JOIN user AS usales 
                ON usales.id = ucon.added_by ' . $n .
            'LEFT JOIN user_info AS uisales 
                ON uisales.user_id = usales.id ' . $n .
            'LEFT JOIN location AS l 
                ON l.id = uisales.location_id ' . $n .
            'LEFT JOIN invoice_item AS ii 
                ON ii.lot_item_id = li.id AND ii.active ' .
            "AND (SELECT COUNT(1) FROM invoice WHERE id = ii.invoice_id AND invoice_status_id IN ({$invoiceStatusList})) > 0 " . $n .
            "LEFT JOIN invoice AS i 
                ON i.id = ii.invoice_id AND i.invoice_status_id IN ({$invoiceStatusList}) " . $n .
            'LEFT JOIN user_info AS uibid 
                ON uibid.user_id = li.winning_bidder_id ' . $n .
            'LEFT JOIN user_info AS uicon 
                ON uicon.user_id = li.consignor_id ' . $n .
            "LEFT JOIN (
                    SELECT tran_id, 
                        MAX(created_on) AS pay_created, 
                        MAX(paid_on) AS pay_date, 
                        IF (paid_on IS NULL,MAX(created_on), MAX(paid_on)) AS payment_date
                    FROM payment 
                    WHERE tran_type = '" . Constants\Payment::TT_INVOICE . "' AND active = true GROUP BY tran_id 
                    ) AS inv_pay_created 
               ON inv_pay_created.tran_id = ii.invoice_id " . $n;
        // @formatter:on
        return $from;
    }

    /**
     * @return string
     */
    public function getWhereClause(): string
    {
        $n = "\n";
        $conds = 'WHERE ' . $n .
            'li.active = true ' . $n .
            'AND ii.hammer_price IS NOT NULL ' . $n .
            'AND li.auction_id > 0 ' . $n .
            'AND li.winning_bidder_id IS NOT NULL ' . $n;

        $accountId = $this->getFilterAccountId();
        if (is_array($accountId)) {
            $accountId = reset($accountId);
        }
        if ($accountId > 0) {
            $conds .= 'AND li.account_id = ' . $this->escape($accountId) . ' ';
        }

        $conds .= match ($this->getDateRangeType()) {
            SaleStaffReportFormConstants::DR_INVOICE_DATE => "AND (IFNULL(i.invoice_date, i.created_on)"
                . ' >= ' . $this->escape($this->getFilterStartDateUtcIso())
                . " AND IFNULL(i.invoice_date, i.created_on)"
                . ' <= ' . $this->escape($this->getFilterEndDateUtcIso()) . ') ' . $n,
            SaleStaffReportFormConstants::DR_PAYMENT_DATE => 'AND ((pay_created >= ' . $this->escape($this->getFilterStartDateUtcIso())
                . ' AND pay_created <= ' . $this->escape($this->getFilterEndDateUtcIso()) . ')'
                . ' OR (pay_date >= ' . $this->escape($this->getFilterStartDateUtcIso())
                . ' AND pay_date <= ' . $this->escape($this->getFilterEndDateUtcIso()) . ')) ' . $n,
            default => 'AND (li.date_sold >= ' . $this->escape($this->getFilterStartDateUtcIso())
                . ' AND li.date_sold <= ' . $this->escape($this->getFilterEndDateUtcIso()) . ') ' . $n,
        };
        if ($this->getSalesStaff()) {
            $conds .= 'AND ucon.added_by = ' . $this->escape($this->getSalesStaff()) . ' ' . $n;
        }
        return $conds;
    }

    /**
     * @return string
     */
    public function getOrderClause(): string
    {
        $n = "\n";
        $sortOrder = '';
        $lotOrderClause = '';

        $column = $this->getSortColumn();
        $sortDirection = $this->isAscendingOrder() ? 'ASC' : 'DESC';
        if (!in_array($column, [SaleStaffReportFormConstants::ORD_LOT_NO, SaleStaffReportFormConstants::ORD_ITEM_NO], true)) {
            $lotOrderClause = $this->createAuctionLotOrderMysqlQueryBuilder()->buildLotOrderClause($this->isAscendingOrder());
        }
        if (isset(SaleStaffReportFormConstants::$orderKeysToColumns[$column])) {
            foreach (SaleStaffReportFormConstants::$orderKeysToColumns[$column] as $columnField) {
                $sortOrder .= $columnField . ' ' . $sortDirection . ',';
            }
            $sortOrder = $lotOrderClause ? $sortOrder . $lotOrderClause : rtrim($sortOrder, ',');
        } else {
            $sortOrder = 'ali.lot_num_prefix ASC, ali.lot_num ASC, ali.lot_num_ext ASC';
        }
        return ' ORDER BY ' . $sortOrder . $n;
    }
}
