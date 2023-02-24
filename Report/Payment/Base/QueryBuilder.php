<?php
/**
 * SAM-4632 : Refactor payment report
 * https://bidpath.atlassian.net/browse/SAM-4632
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/1/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Payment\Base;

use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Entity\FilterCurrencyAwareTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Entity\FilterLocationAwareTrait;
use Sam\Core\Filter\Entity\FilterUserAwareTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Constants;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class QueryBuilder
 * @package Sam\Report\Payment\Base
 */
abstract class QueryBuilder extends CustomizableClass
{
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
    public function getSelectClause(): string
    {
        $n = "\n";
        // @formatter:off
        $query =
            'SELECT ' . $n .
            'IFNULL(p.paid_on, p.created_on) AS payment_date, ' . $n .
            'u.username AS username, ' . $n .
            'CONVERT(ab.bidder_num,UNSIGNED INTEGER) AS bidder_num, ' . $n .
            'a.id AS auction_id, ' . $n .
            'a.sale_num AS sale_num, ' . $n .
            'a.sale_num_ext AS sale_num_ext, ' . $n .
            'i.id AS invoice_id, ' . $n .
            'i.invoice_no AS invoice_no, ' . $n .
            'cc.name AS credit_card_type, ' . $n .
            'p.payment_method_id, ' . $n .
            'p.note AS note, ' . $n .
            'p.amount AS amount, ' . $n .
            'p.id AS payment_id, ' . $n .
            'p.credit_card_id AS credit_card_id, ' . $n .
            'u.id as user_id, ' . $n .
            'l.id as location_id, ' . $n .
            'c.sign AS currency_sign, ' . $n .
            'c.id AS currency_id, ' . $n .
            'c.ex_rate AS ex_rate, ' . $n .
            'l.name as location, ' . $n .
            'ui.referrer as referrer, ' . $n .
            'ui.referrer_host as referrer_host ' . $n;
        // @formatter::on
        return $query;
    }

    /**
     * @return string
     */
    public function getFromClause(): string
    {
        $n = "\n";
        // @formatter:off
        $from =
            'FROM payment p ' . $n .
            'INNER JOIN account AS ac_payment 
                ON ac_payment.id = p.account_id AND ac_payment.active ' . $n .
            'LEFT JOIN invoice i 
                ON i.id = p.tran_id AND p.tran_type = ' . $this->escape(Constants\Payment::TT_INVOICE) . ' ' . $n .
            'LEFT JOIN auction a 
                ON a.id = (SELECT auction_id FROM invoice_item WHERE invoice_id = p.tran_id AND active LIMIT 1) ' . $n .
            "LEFT JOIN currency c 
                ON c.id = IFNULL(a.currency, 
                (SELECT id 
                FROM currency
                WHERE sign = '" . $this->getCurrencyLoader()->detectDefaultSign() .
            "' AND account_id = '" . $this->cfg()->get('core->portal->mainAccountId') .
            "' AND active) 
                ) " . $n .
            'LEFT JOIN user u 
                ON u.id = i.bidder_id ' . $n .
            'LEFT JOIN user_info ui 
                ON ui.user_id = u.id ' . $n .
            'LEFT JOIN auction_bidder ab 
                ON ab.auction_id = a.id 
                AND ab.user_id = u.id ' . $n .
            'LEFT JOIN credit_card cc 
                ON cc.id = p.credit_card_id ' . $n .
            'LEFT JOIN location l 
                ON l.id = a.invoice_location_id ' . $n;
        // @formatter:on
        return $from;
    }

    /**
     * @return string
     */
    public function getWhereClause(): string
    {
        $n = "\n";
        $where = 'WHERE ' . $n . 'i.invoice_status_id IN ( ' . implode(',', Constants\Invoice::$openInvoiceStatuses) . ') ' . $n;
        $where .= 'AND u.user_status_id = ' . Constants\User::US_ACTIVE . ' ' . $n;
        $where .= 'AND p.active = true ' . $n;

        $accountId = $this->getFilterAccountId();
        if (is_array($accountId)) {
            $accountId = reset($accountId);
        }
        if ($accountId) {
            $where .= 'AND i.account_id = ' . $this->escape($accountId) . ' ' . $n;
        }

        if ($this->isFilterDatePeriod()) {
            $where .= 'AND IFNULL(p.paid_on, p.created_on) BETWEEN ' . $this->escape($this->getFilterStartDateUtcIso()) . ' AND ' . $this->escape($this->getFilterEndDateUtcIso()) . ' ' . $n;
        }

        if ($this->getFilterUserId()) {
            $where .= 'AND u.id = ' . $this->escape($this->getFilterUserId()) . ' ' . $n;
        }

        if ($this->getFilterLocationId()) {
            $where .= 'AND l.id = ' . $this->escape($this->getFilterLocationId()) . ' ' . $n;
        }

        if ($this->getFilterAuctionId()) {
            $where .= 'AND a.id = ' . $this->escape($this->getFilterAuctionId()) . ' ' . $n;
        }

        if ($this->getFilterCurrencyId()) {
            $where .= 'AND c.id = ' . $this->escape($this->getFilterCurrencyId()) . ' ' . $n;
        }
        return $where;
    }

    /**
     * @return string
     */
    public function getOrderClause(): string
    {
        $n = "\n";
        $column = $this->getSortColumn();
        $direction = $this->isAscendingOrder() ? 'ASC' : 'DESC';

        $sort = match ($column) {
            'paid_on' => "payment_date {$direction} " . $n,
            'username' => "username {$direction} " . $n,
            'referrer' => "referrer {$direction} " . $n,
            'referrer_host' => "referrer_host {$direction} " . $n,
            'bidder_num' => "bidder_num {$direction} " . $n,
            'auction_id' => "auction_id {$direction} " . $n,
            'invoice_no' => "invoice_no {$direction} " . $n,
            'payment_method_id' => "payment_method_id {$direction}" . $n,
            'note' => "note {$direction}" . $n,
            'amount' => "amount {$direction}" . $n,
            default => 'p.paid_on DESC, u.username ASC ' . $n,
        };
        return " ORDER BY {$sort} " . $n;
    }

}
