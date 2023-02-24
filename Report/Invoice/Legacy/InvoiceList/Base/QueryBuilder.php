<?php
/**
 * SAM-4623 : Refactor invoice list report
 * https://bidpath.atlassian.net/browse/SAM-4623
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/17/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Invoice\Legacy\InvoiceList\Base;

use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Model\Invoice\Status\InvoiceStatusPureChecker;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Entity\FilterCurrencyAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Report\Invoice\Legacy\InvoiceList\Csv\FilterAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotCustomFieldsAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserCustomFieldsAwareTrait;
use UserCustField;

/**
 * Class QueryBuilder
 * @package Sam\Report\Invoice\Legacy\InvoiceList\Base
 */
abstract class QueryBuilder extends CustomizableClass
{
    use CurrencyLoaderAwareTrait;
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterAwareTrait;
    use FilterCurrencyAwareTrait;
    use LimitInfoAwareTrait;
    use LotCustomFieldsAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;
    use UserCustomFieldsAwareTrait;

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

        if ($this->isCustomFieldRender()) {
            $params['lotCustFldSelect'] = $this->getLotCustomFields();
            $params['userCustFldSelect'] = $this->getUserCustomFields();
            $this->queryParts = $this->addCustomFieldSelect($params, $this->queryParts);
        }
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
        return '';
    }

    /**
     * Get Form Clause
     * @return string
     */
    protected function getFromClause(): string
    {
        $n = "\n";
        $invoiceStatusList = implode(',', Constants\Invoice::$availableInvoiceStatuses);
        // @formatter:off
        $from =
            ' FROM invoice AS i' . $n
            . ' INNER JOIN account AS ac'
                . ' ON i.account_id = ac.id'
                . ' AND ac.active' . $n
            . ' INNER JOIN user AS u'
                . ' ON u.id = i.bidder_id' . $n
            . ' LEFT JOIN user_info AS ui'
                . ' ON ui.user_id = i.bidder_id' . $n
            . ' LEFT JOIN invoice_user_billing AS iub'
                . ' ON iub.invoice_id = i.id' . $n
            . ' LEFT JOIN invoice_user_shipping AS ius'
                . ' ON ius.invoice_id = i.id' . $n;

        if ($this->isCustomFieldRender()) {
           $from .=
               ' INNER JOIN invoice_item AS ii0'
                    . ' ON ii0.invoice_id = i.id' . $n;
        }

        $taHpBp = Constants\User::TAX_HP_BP;
        $taHp = Constants\User::TAX_HP;
        $taBp = Constants\User::TAX_BP;
        $from .=
            ' LEFT JOIN' . $n
                . ' ('
                    . ' SELECT' . $n
                        . ' i.id AS invoice_id,' . $n
                        . ' SUM(ii.hammer_price) AS bid_total,' . $n
                        . ' SUM(CASE WHEN tax != 0 THEN ii.hammer_price ELSE 0 END) taxable,' . $n
                        . ' SUM(CASE WHEN tax = 0 THEN ii.hammer_price ELSE 0 END) non_taxable,' . $n
                        . ' SUM(ii.buyers_premium) AS premium,' . $n
                        . '('
                            . ' SELECT'
                                . ' SUM(' . $n
                                    . " IF(ii2.tax_application = {$taBp}," . $n
                                        . 'ii2.buyers_premium * ((SELECT IF (ii2.sales_tax IS NULL, i.sales_tax, ii2.sales_tax)) / 100),' . $n
                                        . "IF(ii2.tax_application = {$taHp}," . $n
                                            . 'ii2.hammer_price * ((SELECT IF (ii2.sales_tax IS NULL, i.sales_tax, ii2.sales_tax)) / 100),' . $n
                                            . '(ii2.hammer_price + ii2.buyers_premium) * ((SELECT IF (ii2.sales_tax IS NULL, i.sales_tax, ii2.sales_tax)) / 100)' . $n
                                        . ')' . $n
                                    . ')' . $n
                                . ')' . $n
                            . ' FROM invoice_item ii2'
                            . ' WHERE ii2.invoice_id = i.id'
                                . ' AND ii2.active = true'
                        . ') AS tax' . $n
                    . ' FROM invoice AS i' . $n
                    . ' LEFT JOIN invoice_item AS ii ON i.id = ii.invoice_id' . $n
                    . ' WHERE'
                        . " i.invoice_status_id IN ({$invoiceStatusList})" . $n
                        . ' AND ii.active = true' . $n
                    . ' GROUP BY i.id'
                . ') AS inv_sum'
                . ' ON i.id = inv_sum.invoice_id' . $n;

        $from .=
            ' LEFT JOIN'
                . ' ('
                    . ' SELECT' . $n
                        . ' id AS invoice_id,' . $n
                        . ' SUM(shipping) AS shipping_charge' . $n
                    . ' FROM invoice' . $n
                    . ' WHERE'
                        . ' invoice_status_id IN (' . $invoiceStatusList . ')' . $n
                    . ' GROUP BY id'
                . ') AS inv_shipping'
                . ' ON i.id = inv_shipping.invoice_id' . $n;

        $from .=
            'LEFT JOIN'
                . ' ('
                    . ' SELECT'
                        . ' ii.invoice_id  AS invoice_id,'
                        . ' SUM(ii.hammer_price) AS sum'
                    . ' FROM invoice i' . $n
                    . ' INNER JOIN setting_system AS setsys'
                        . ' ON setsys.account_id = i.account_id' . $n
                    . ' LEFT JOIN invoice_user_shipping AS ius'
                        . ' ON ius.invoice_id = i.id' . $n
                    . ' INNER JOIN invoice_item AS ii'
                        . ' ON ii.invoice_id = i.id AND ii.active' . $n
                    . ' WHERE'
                        . " (ii.sales_tax = 0 OR ii.tax_application = {$taBp})" . $n
                        . " AND (ius.country = '' OR setsys.default_country = ius.country)" . $n
                    . 'GROUP BY invoice_id' . $n
                . ') AS non_taxable_sum' . $n
                . ' ON i.id = non_taxable_sum.invoice_id' . $n;

        $from .=
            'LEFT JOIN'
                .' ('
                    . 'SELECT'
                        . ' ii.invoice_id  AS invoice_id,'
                        . ' SUM(ii.hammer_price) AS sum'
                    . ' FROM invoice i' . $n
                    . ' INNER JOIN setting_system AS setsys'
                        . ' ON setsys.account_id = i.account_id ' . $n
                    . ' LEFT JOIN invoice_user_shipping AS ius'
                        . ' ON ius.invoice_id = i.id ' . $n
                    . ' INNER JOIN invoice_item AS ii'
                        . ' ON ii.invoice_id = i.id AND ii.active ' . $n
                    . ' WHERE'
                        . " ius.country != ''"
                        . ' AND setsys.default_country != ius.country' . $n
                    . ' GROUP BY invoice_id' . $n
                . ') AS export' . $n
                . ' ON i.id = export.invoice_id ' . $n;

        $from .=
            'LEFT JOIN'
                . ' ('
                    . 'SELECT'
                        . ' ii.invoice_id  AS invoice_id,'
                        . ' SUM(ii.hammer_price) AS sum'
                    . ' FROM invoice i' . $n
                    . ' INNER JOIN setting_system AS setsys'
                        . ' ON setsys.account_id = i.account_id' . $n
                    . ' LEFT JOIN invoice_user_shipping AS ius'
                        . ' ON ius.invoice_id = i.id ' . $n
                    . ' INNER JOIN invoice_item AS ii'
                        . ' ON ii.invoice_id = i.id AND ii.active ' . $n
                    . ' WHERE'
                        . " ii.tax_application IN ({$taHp}, {$taHpBp}) " . $n
                        . " AND ii.sales_tax != 0 AND (ius.country = '' OR setsys.default_country = ius.country) " . $n
                    . ' GROUP BY invoice_id'
                . ') AS taxable_sum' . $n
                . ' ON i.id = taxable_sum.invoice_id' . $n;

        $from .=
            'LEFT JOIN'
                . ' ('
                    . 'SELECT' . $n
                        . ' invoice_id,'
                        . ' SUM(amount) AS total_charge' . $n
                    . ' FROM invoice_additional iadd' . $n
                    . ' WHERE iadd.active' . $n
                    . ' GROUP BY invoice_id'
                . ') AS inv_charge' . $n
                . ' ON i.id = inv_charge.invoice_id' . $n;

        $from .=
            'LEFT JOIN'
                . ' ('
                    . 'SELECT'
                        . ' tran_id,'
                        . ' SUM(amount) AS total_payment'
                    . ' FROM payment ' . $n
                    . ' WHERE'
                        . ' tran_type = ' . $this->escape(Constants\Payment::TT_INVOICE)
                        . ' AND active = true'
                    . ' GROUP BY tran_id'
                . ') AS inv_payment'
                . ' ON i.id = inv_payment.tran_id ' . $n;

        // @formatter:on

        return $from;
    }

    /**
     * Get Where Clause
     * @return string
     */
    protected function getWhereClause(): string
    {
        $n = "\n";
        $where = "WHERE " . $n . " ";

        if ($this->isAccountFiltering()) {
            $accountUrlParamId = $this->getFilterAccountId();
            $where = $accountUrlParamId
                ? $where . "i.account_id = " . $this->escape($accountUrlParamId) . " " . $n
                : $where . "i.account_id > 0 " . $n;
        } else { //In case sam portal has been disabled again
            $where .= "i.account_id = " . $this->escape($this->getSystemAccountId()) . " " . $n;
        }

        $where .= "AND i.tax_designation = " . Constants\Invoice::TDS_LEGACY . " " . $n;

        $searchKey = $this->getSearchKey();
        if ($searchKey !== '') {
            $searchClause = $this->escape('%' . $searchKey . '%');
            $where .= "AND (i.id LIKE {$searchClause} " . $n .
                "OR u.username LIKE {$searchClause} " . $n .
                "OR ui.first_name LIKE {$searchClause} " . $n .
                "OR ui.last_name LIKE {$searchClause} " . $n .
                "OR (SELECT COUNT(1) FROM lot_item AS li, invoice_item AS ii " . $n .
                "WHERE li.id = ii.lot_item_id AND ii.invoice_id = i.id AND ii.active " . $n .
                "AND (li.name LIKE {$searchClause} OR li.item_num LIKE {$searchClause})) > 0 " . $n .
                "OR iub.state LIKE {$searchClause} " . $n .
                "OR iub.zip LIKE {$searchClause} ) " . $n;
        }
        $winningUserId = $this->getWinningUserId();
        if ($winningUserId) {
            $where .= " AND u.id = " . $this->escape($winningUserId) . $n;
        } else {
            $winningUserSearchKey = $this->getWinningUserSearchKey();
            if ($winningUserSearchKey !== '') {
                $winningUserSearchKeys = explode(' ', $winningUserSearchKey);
                foreach ($winningUserSearchKeys as $winSearchKey) {
                    $term = '%' . $winSearchKey . '%';
                    $term = $this->escape($term);
                    $where .= " AND (u.customer_no like " . $term
                        . " OR u.username like " . $term
                        . " OR u.email like " . $term
                        . " OR ui.first_name like " . $term
                        . " OR ui.last_name like " . $term
                        . " OR u.email like " . $term . ")";
                }
            }
        }

        $currencySign = $this->getFilterCurrencySign();
        if ($currencySign) {
            $currencyFilter = $this->escape($currencySign);
            $primaryCurrencySign = $this->getCurrencyLoader()->findPrimaryCurrencySign();
            $where .= "AND IF(i.currency_sign <> '', i.currency_sign, '{$primaryCurrencySign}') LIKE {$currencyFilter} " . $n;
        }

        $invoiceNum = $this->getInvoiceNo();
        if ($invoiceNum) {
            $invoiceNoFilter = $this->escape('%' . $invoiceNum . '%');
            $where .= " AND i.invoice_no LIKE {$invoiceNoFilter} " . $n;
        }

        $auctionId = $this->getFilterAuctionId();
        if ($auctionId) {
            $where .= "AND (SELECT COUNT(1) FROM invoice_item AS ii " .
                "LEFT JOIN auction AS a ON a.id = ii.auction_id " .
                "WHERE ii.invoice_id = i.id " .
                "AND ii.active = true " .
                "AND a.auction_status_id IN (" . implode(',', Constants\Auction::$availableAuctionStatuses) . ") " .
                "AND a.id = " . $this->escape($auctionId) . ") " . $n;
        }

        //list action
        $invoiceStatus = $this->getInvoiceStatus();
        $invoiceStatusPureChecker = InvoiceStatusPureChecker::new();
        if ($invoiceStatusPureChecker->isAmongAvailableInvoiceStatuses($invoiceStatus)) {
            $where .= "AND i.invoice_status_id = {$invoiceStatus} " . $n;
        } else {
            $invoiceStatusList = implode(',', Constants\Invoice::$availableInvoiceStatuses);
            $where .= "AND i.invoice_status_id IN (" . $invoiceStatusList . ") " . $n;
        }

        return $where;
    }

    /**
     * Sort Query part by Column
     * @param string $sortIndex
     * @return string
     */
    protected function getSortOrder(string $sortIndex): string
    {
        return match ($sortIndex) {
            'ISSUED', 'DATE' => "IFNULL(i.invoice_date, i.created_on) ASC ",
            'ID' => "i.id ASC ",
            'USER' => "u.username ASC ",
            'NAME' => "ui.last_name ASC, ui.first_name ASC ",
            'ST.' => "iub.state ASC ",
            'ZIP' => "iub.zip ASC ",
            'STATUS' => "invoice_status_id ASC ",
            'SALE' => "sale_name ASC ",
            'BIDDER #' => "bidder_num ASC ",
            'SENT' => "i.first_sent_on ASC ",
            'NONTAXABLE' => "non_taxable ASC ",
            'TAXABLE' => "taxable ASC ",
            'EXPORT' => "export ASC ",
            'PREMIUM' => "premium ASC ",
            'TAX' => "tax ASC ",
            'SHIPPING' => "shipping_fees ASC ",
            'EXTRACHARGES' => "extra_charges_fees ASC ",
            'PAYMENT' => "total_payment ASC ",
            'BALANCE' => "balance ASC ",
            'TOTAL' => "total ASC ",
            'CURRENCY' => '',
            'INTERNALNOTE' => 'internal_note ASC',
            default => '',
        };
    }

    /**
     * Get Order clause
     * @return string
     */
    public function getOrderClause(): string
    {
        $n = "\n";
        $sort = '';
        $order = '';
        //primary sort
        $sort .= $this->getSortOrder($this->getPrimarySort());
        if ($this->getPrimarySort() === 'CURRENCY') {
            $this->setPrimarySort('');
        }

        if ($this->getSecondarySort() === 'CURRENCY') {
            $this->setSecondarySort('');
        }

        if (
            $this->getPrimarySort()
            && $this->getSecondarySort()
        ) {
            $sort .= ", ";
        }

        //secondary sort
        $sort .= $this->getSortOrder($this->getSecondarySort());

        $sortColumn = $this->getSortColumnIndex();
        $sortDirection = $this->getSortDirection();

        $direction = ($sortDirection === 0) ? 'ASC' : 'DESC';

        if (
            $sortColumn > 0
            && ($this->getPrimarySort() || $this->getSecondarySort())
        ) {
            $sort .= ", ";
        }
        if ($sortColumn === 1) {
            $sort .= "i.id $direction " . $n;
        } elseif ($sortColumn === 2) {
            $sort .= "i.created_on $direction " . $n;
        } elseif ($sortColumn === 4) {
            if ($this->isMultipleSaleInvoice()) {
                $sort .= "u.username $direction " . $n;
            } else {
                $sort .= "sale_name " . $n;
            }
        } elseif ($sortColumn === 5) {
            if ($this->isMultipleSaleInvoice()) {
                $sort .= "iub.state $direction " . $n;
            } else {
                $sort .= "bidder_num $direction " . $n;
            }
        } elseif ($sortColumn === 6) {
            $sort .= "iub.zip $direction " . $n;
        } elseif ($sortColumn === 7) {
            $sort .= "iub.state $direction " . $n;
        } elseif ($sortColumn === 8) {
            $sort .= "iub.zip $direction " . $n;
        } elseif ($sortColumn === 17 || $sortColumn === 19) {
            $sort .= "invoice_status_id $direction " . $n;
        }
        if ($sortColumn > 0) {
            $order .= " ORDER BY {$sort} " . $n;
        } else {
            if (
                !$this->getPrimarySort()
                && !$this->getSecondarySort()
            ) {
                $order .= " ORDER BY i.id DESC " . $n;
            } elseif (trim($sort) !== '') {
                $order .= " ORDER BY {$sort} " . $n;
            }
        }
        return $order;
    }

    /**
     * Add query part for selecting lot item custom field values
     *
     * @param array $params :
     *                         - CustFldSelect LotItemCustField[]
     * @param array $queryParts
     * @return array $queryParts
     */
    public function addCustomFieldSelect(array $params, array $queryParts): array
    {
        $lotCustomFields = $params['lotCustFldSelect'] ?? null;
        $userCustomFields = $params['userCustFldSelect'] ?? null;

        if ($lotCustomFields === null && $userCustomFields === null) {
            return $queryParts;
        }

        $n = "\n";
        $customFieldSelect = '';
        $dbTransformer = DbTextTransformer::new();

        if ($lotCustomFields !== null) {
            foreach ($lotCustomFields as $lotCustomField) {
                $alias = sprintf('licf%s', $dbTransformer->toDbColumn($lotCustomField->Name));
                $customFieldSelect .= ', ' . $this->getLotCustomFieldSelectExpr($lotCustomField)
                    . ' AS ' . $alias . ' ' . $n;
            }
        }

        if ($userCustomFields !== null) {
            foreach ($userCustomFields as $userCustomField) {
                $alias = sprintf('ucf%s', $dbTransformer->toDbColumn($userCustomField->Name));
                $customFieldSelect .= ', ' . $this->getUserCustomFieldSelectExpr($userCustomField)
                    . ' AS ' . $alias . ' ' . $n;
            }
        }

        if (!empty($customFieldSelect)) {
            $queryParts['select'] .= $customFieldSelect;
        }

        return $queryParts;
    }

    /**
     * Return select expression (without alias) for custom field data value select
     * @param LotItemCustField $lotCustomField
     * @return string
     */
    public function getLotCustomFieldSelectExpr(LotItemCustField $lotCustomField): string
    {
        $n = "\n";
        if ($lotCustomField->isNumeric()) {
            $selectExpr = "(SELECT licd.`numeric` FROM lot_item_cust_data AS licd " .
                "WHERE licd.lot_item_id = ii0.lot_item_id AND licd.active = true " .
                "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " LIMIT 1)" . $n;
        } else {
            $selectExpr = "(SELECT licd.`text` FROM lot_item_cust_data AS licd " .
                "WHERE licd.lot_item_id = ii0.lot_item_id AND licd.active = true " .
                "AND licd.lot_item_cust_field_id = " . $this->escape($lotCustomField->Id) . " LIMIT 1)" . $n;
        }
        return $selectExpr;
    }

    /**
     * Return select expression (without alias) for custom field data value select
     * @param UserCustField $userCustomField
     * @return string
     */
    public function getUserCustomFieldSelectExpr(UserCustField $userCustomField): string
    {
        $n = "\n";
        if ($userCustomField->isNumeric()) {
            $selectExpr = "(SELECT ucd.`numeric` FROM user_cust_data AS ucd " .
                "WHERE ucd.user_id = u.id AND ucd.active = true " .
                "AND ucd.user_cust_field_id = " . $this->escape($userCustomField->Id) . " LIMIT 1)" . $n;
        } else {
            $selectExpr = "(SELECT ucd.`text` FROM user_cust_data AS ucd " .
                "WHERE ucd.user_id = u.id AND ucd.active = true " .
                "AND ucd.user_cust_field_id = " . $this->escape($userCustomField->Id) . " LIMIT 1)" . $n;
        }
        return $selectExpr;
    }
}
