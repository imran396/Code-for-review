<?php
/**
 * SAM-4623 : Refactor invoice list report
 * https://bidpath.atlassian.net/browse/SAM-4623
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/18/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Invoice\StackedTax\InvoiceList\Csv;

use LotItemCustField;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Model\Invoice\Status\InvoiceStatusPureChecker;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Entity\FilterCurrencyAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotCustomFieldsAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserCustomFieldsAwareTrait;
use UserCustField;

/**
 * Class QueryBuilder
 * @package Sam\Report\Invoice\StackedTax\InvoiceList\Csv
 */
class QueryBuilder extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
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


    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

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
     * Get Form Clause
     * @return string
     */
    protected function getFromClause(): string
    {
        $n = "\n";
        // @formatter:off
        $from =
            ' FROM invoice AS i' . $n
            . ' INNER JOIN account AS ac'
            . ' ON i.account_id = ac.id'
            . ' AND ac.active' . $n
            . ' LEFT JOIN invoice_user AS iu'
            . ' ON iu.invoice_id = i.id' . $n
            . ' LEFT JOIN invoice_auction AS ia'
            . ' ON ia.invoice_id = i.id' . $n
            . ' LEFT JOIN invoice_item AS ii'
            . ' ON ii.invoice_id = i.id' . $n
            . ' LEFT JOIN invoice_user_billing AS iub'
            . ' ON iub.invoice_id = i.id' . $n
            . ' LEFT JOIN invoice_user_shipping AS ius'
            . ' ON ius.invoice_id = i.id' . $n;

        if ($this->isCustomFieldRender()) {
            $from .= ' INNER JOIN invoice_item AS ii0' . ' ON ii0.invoice_id = i.id' . $n;
        }
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

        $where .= "AND i.tax_designation = " . Constants\Invoice::TDS_STACKED_TAX . " " . $n;

        $searchKey = $this->getSearchKey();
        if ($searchKey !== '') {
            $searchClause = $this->escape('%' . $searchKey . '%');
            $where .= "AND (i.id LIKE {$searchClause} " . $n .
                "OR iu.username LIKE {$searchClause} " . $n .
                "OR iu.first_name LIKE {$searchClause} " . $n .
                "OR iu.last_name LIKE {$searchClause} " . $n .
                "OR (SELECT COUNT(1) FROM lot_item AS li, invoice_item AS ii " . $n .
                "WHERE li.id = ii.lot_item_id AND ii.invoice_id = i.id AND ii.active " . $n .
                "AND (li.name LIKE {$searchClause} OR li.item_num LIKE {$searchClause})) > 0 " . $n .
                "OR iub.state LIKE {$searchClause} " . $n .
                "OR iub.zip LIKE {$searchClause} ) " . $n;
        }
        $winningUserId = $this->getWinningUserId();
        if ($winningUserId) {
            $where .= " AND i.bidder_id = " . $this->escape($winningUserId) . $n;
        } else {
            $winningUserSearchKey = $this->getWinningUserSearchKey();
            if ($winningUserSearchKey !== '') {
                $winningUserSearchKeys = explode(' ', $winningUserSearchKey);
                foreach ($winningUserSearchKeys as $winSearchKey) {
                    $term = '%' . $winSearchKey . '%';
                    $term = $this->escape($term);
                    $where .= " AND (iu.customer_no like " . $term
                        . " OR iu.username like " . $term
                        . " OR iu.email like " . $term
                        . " OR iu.first_name like " . $term
                        . " OR iu.last_name like " . $term
                        . " OR iu.email like " . $term . ")";
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
            'USER' => "iu.username ASC ",
            'NAME' => "iu.last_name ASC, iu.first_name ASC ",
            'ST.' => "iub.state ASC ",
            'ZIP' => "iub.zip ASC ",
            'STATUS' => "invoice_status_id ASC ",
            'SALE' => "sale_name ASC ",
            'BIDDER #' => "bidder_num ASC ",
            'SENT' => "i.first_sent_on ASC ",
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
                $sort .= "iu.username $direction " . $n;
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
                "WHERE ucd.user_id = i.bidder_id AND ucd.active = true " .
                "AND ucd.user_cust_field_id = " . $this->escape($userCustomField->Id) . " LIMIT 1)" . $n;
        } else {
            $selectExpr = "(SELECT ucd.`text` FROM user_cust_data AS ucd " .
                "WHERE ucd.user_id = i.bidder_id AND ucd.active = true " .
                "AND ucd.user_cust_field_id = " . $this->escape($userCustomField->Id) . " LIMIT 1)" . $n;
        }
        return $selectExpr;
    }

    /**
     * Get SQL Select Clause
     * @return string
     */
    protected function getSelectClause(): string
    {
        $n = "\n";
        // @formatter:off
        $query = "SELECT " . $n .
            "i.id AS id, " . $n .
            "i.invoice_no AS invoice_no, " . $n .
            "i.created_on AS created_on, " . $n .
            "i.invoice_date AS invoice_date, " . $n .
            "i.first_sent_on AS first_sent_on, " . $n .
            "i.invoice_status_id AS invoice_status_id, " . $n .
            "i.shipping AS shipping, " . $n .
            "i.bidder_id AS bidder_id, " . $n .
            "i.internal_note AS internal_note, " . $n .
            "i.extra_charges, " . $n .
            "i.tax AS tax_total, " . $n .
            "i.bid_total, "  . $n .
            "i.buyers_premium, " . $n .
            "i.hp_tax_total, " . $n .
            "i.bp_tax_total, " . $n .
            "i.services_tax_total, " . $n .
            "i.total_payment, " . $n .
            "(i.hp_country_tax_total + i.bp_country_tax_total + i.services_country_tax_total) AS country_tax_total, " . $n .
            "(i.hp_state_tax_total + i.bp_state_tax_total + i.services_state_tax_total) AS state_tax_total, " . $n .
            "(i.hp_county_tax_total + i.bp_county_tax_total + i.services_county_tax_total) AS county_tax_total, " . $n .
            "(i.hp_city_tax_total + i.bp_city_tax_total + i.services_city_tax_total) AS city_tax_total, " . $n .
            "@invoice_total := (i.bid_total + i.hp_tax_total + i.buyers_premium + i.bp_tax_total + i.extra_charges + i.services_tax_total) AS invoice_total, " . $n .
             "(@invoice_total - i.total_payment) AS balance_due, " . $n .
            "ia.bidder_num AS bidder_num, " . $n .
            "ii.item_no AS item_no, " . $n .
            "ii.lot_no AS lot_no, " . $n .
            "ii.quantity AS quantity, " . $n .
            "ii.quantity_digits AS quantity_scale, " . $n .
            "iu.username AS username, " . $n .
            "iu.email AS email, " . $n .
            "iu.customer_no AS customer_no, " . $n .
            "iu.first_name AS first_name, " . $n .
            "iu.last_name AS last_name, " . $n .
            "iu.phone AS iphone, " . $n .
            "iu.referrer AS referrer, " . $n .
            "iu.referrer_host AS referrer_host, " . $n .
            "iub.company_name AS bcompany_name, " . $n .
            "iub.first_name AS bfirst_name, " . $n .
            "iub.last_name AS blast_name, " . $n .
            "iub.phone AS bphone, " . $n .
            "iub.address AS baddress, " . $n .
            "iub.address2 AS baddress2, " . $n .
            "iub.address3 AS baddress3, " . $n .
            "iub.city AS bcity, " . $n .
            "iub.state AS bstate, " . $n .
            "iub.zip AS bzip, " . $n .
            "iub.country AS bcountry, " . $n .
            "ius.company_name AS scompany_name, " . $n .
            "ius.first_name AS sfirst_name, " . $n .
            "ius.last_name AS slast_name, " . $n .
            "ius.phone AS sphone, " . $n .
            "ius.address AS saddress, " . $n .
            "ius.address2 AS saddress2, " . $n .
            "ius.address3 AS saddress3, " . $n .
            "ius.city AS scity, " . $n .
            "ius.state AS sstate, " . $n .
            "ius.zip AS szip, " . $n .
            "ius.country AS scountry, " . $n ;
        // @formatter:on

        if (!$this->isMultipleSaleInvoice()) {
            // TODO: temporary solutions, we should extract logic to invoice list query builder
            // @formatter:off
            $query .=
                "(SELECT sale_no FROM invoice_auction WHERE auction_id = " . $n .
                "(SELECT auction_id FROM invoice_item WHERE " . $n .
                "active AND invoice_id = i.id AND auction_id > 0 LIMIT 1) LIMIT 1) AS sale_no, " . $n .

                "(SELECT name FROM invoice_auction WHERE auction_id = " . $n .
                "(SELECT auction_id FROM invoice_item WHERE " . $n .
                "active AND invoice_id = i.id AND auction_id > 0 LIMIT 1) LIMIT 1) AS sale_name, " . $n .

                "(SELECT description FROM auction WHERE id = " . $n .
                "(SELECT auction_id FROM invoice_item WHERE " . $n .
                "active AND invoice_id = i.id AND auction_id > 0 LIMIT 1)) AS sale_desc, " . $n ;
            // @formatter:on
        }

        $query .=
            "(SELECT auction_id FROM invoice_auction WHERE auction_id = " . $n .
            "(SELECT auction_id FROM invoice_item WHERE " . $n .
            "active AND invoice_id = i.id AND auction_id > 0 LIMIT 1) LIMIT 1) AS sale_id, " . $n;

        $query .= "iub.state AS state, " . $n .
            "iub.zip AS zip " . $n;

        return $query;
    }
}

