<?php
/**
 *
 * SAM-4625: Refactor settlement list report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-03-18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Settlement\SettlementList;

use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class QueryBuilder
 * @package Sam\Report\Settlement\SettlementList
 */
class QueryBuilder extends CustomizableClass
{
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterAwareTrait;
    use NumberFormatterAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;

    protected bool $isChargeConsignorCommission = false;
    /** @var string[]|null */
    protected ?array $queryParts = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function isChargeConsignorCommission(): bool
    {
        return $this->isChargeConsignorCommission;
    }

    /**
     * @param bool $isChargeConsignorCommission
     * @return static
     */
    public function enableChargeConsignorCommission(bool $isChargeConsignorCommission): static
    {
        $this->isChargeConsignorCommission = $isChargeConsignorCommission;
        return $this;
    }

    /**
     * @return string
     */
    protected function getSelectClause(): string
    {
        $n = "\n";
        $isChargeConsignorCommission = $this->isChargeConsignorCommission();

        $query = '';
        if ($isChargeConsignorCommission) {
            $query .= "IFNULL(s.comm_total, 0) + IFNULL(s.extra_charges, 0) + si.fee_total AS owed_total, ";
        } else {
            $query .= "IFNULL(s.hp_total, 0) + IFNULL(s.tax_exclusive, 0) AS owed_total, ";
        }
        $query .= " s.id AS id, " . $n .
            "u.customer_no AS customer_no, " . $n .
            "s.settlement_no AS settlement_no, " . $n .
            "IF(s.settlement_date IS NULL OR s.settlement_date = '', "
            . "s.created_on, s.settlement_date) AS settlement_date, " . $n .
            "s.settlement_status_id AS settlement_status_id, " . $n .
            "s.consignor_id AS consignor_id, " . $n .
            "s.consignment_commission AS consignment_commission, " . $n .
            "u.username AS username, " . $n .
            "ui.first_name AS first_name, " . $n .
            "ui.last_name AS last_name, " . $n .
            "s.cost_total AS cost_total, " . $n .
            "s.taxable_total AS taxable_total, " . $n .
            "s.non_taxable_total AS non_taxable_total, " . $n .
            "s.export_total AS export_total, " . $n .
            "IFNULL(s.comm_total, 0) + IFNULL(s.extra_charges, 0) + si.fee_total AS fees_comm_total, " . $n .
            "s.tax_inclusive AS tax_inclusive, " . $n .
            "s.tax_exclusive AS tax_exclusive, " . $n .
            "p.amount AS paid_total, " . $n .
            "s.tax_services AS tax_services " . $n;

        return sprintf('SELECT %s ', $query);
    }

    /**
     * @return string
     */
    protected function getFromClause(): string
    {
        $n = "\n";
        $ttSettlement = Constants\Payment::TT_SETTLEMENT;
        $from = "settlement s " . $n .
            "INNER JOIN account AS ac ON s.account_id = ac.id AND ac.active " . $n .
            "LEFT JOIN user u ON u.id = s.consignor_id " . $n .
            "LEFT JOIN (SELECT SUM(amount) AS amount,tran_id FROM payment " .
            "WHERE tran_type = '{$ttSettlement}' AND active = true GROUP BY tran_id) AS p ON p.tran_id = s.id " . $n .
            "LEFT JOIN (SELECT SUM(ROUND(IFNULL(fee, 0),2)) AS fee_total, settlement_id FROM settlement_item " .
            "WHERE active GROUP BY settlement_id) AS si ON s.id = si.settlement_id " . $n .
            "LEFT JOIN user_info ui ON ui.user_id = s.consignor_id " . $n;
        return sprintf('FROM %s ', $from);
    }

    /**
     * @return string
     */
    protected function getWhereClause(): string
    {
        $cond = '';
        $n = "\n";
        if ($this->isAccountFiltering) {
            $filterAccountId = $this->getFilterAccountId();
            $cond = $filterAccountId
                ? $cond . "s.account_id = " . $this->escape($filterAccountId) . " "
                : $cond . "s.account_id > 0 ";
        } else { //In case sam portal has been disabled
            $cond .= "s.account_id = " . $this->escape($this->getSystemAccountId()) . " ";
        }

        $settlementStatusId = $this->getSettlementStatusId();
        if (isset(Constants\Settlement::$settlementStatusNames[$settlementStatusId])) {
            $cond .= "AND s.settlement_status_id = " . $this->escape($settlementStatusId) . " " . $n;
        } else {
            $settlementStatusList = implode(',', Constants\Settlement::$availableSettlementStatuses);
            $cond .= "AND s.settlement_status_id IN (" . $settlementStatusList . ") " . $n;
        }

        $consignorUserId = $this->getConsignorUserId();
        if ($consignorUserId > 0) {
            $cond .= "AND s.consignor_id = " . $this->escape($consignorUserId) . " " . $n;
        }

        $auctionId = $this->getFilterAuctionId();
        if (is_array($auctionId)) {
            $auctionId = reset($auctionId);
        }
        if ($auctionId > 0) {
            $cond .= "AND (SELECT COUNT(1) FROM settlement_item " .
                "WHERE settlement_id = s.id AND auction_id = " .
                $this->escape($auctionId) . ") > 0 " . $n;
        }

        return sprintf('WHERE %s ', $cond);
    }

    /**
     * @return string
     */
    protected function getOrderClause(): string
    {
        $sortColumnIndex = $this->getSortColumnIndex() ?: 0;
        $direction = $this->isAscendingOrder() ? 'ASC' : 'DESC';
        $n = "\n";

        $orderExpr = match ($sortColumnIndex) {
            1 => "s.settlement_no $direction " . $n,
            2 => "settlement_date $direction " . $n,
            3 => "u.username $direction, ui.first_name $direction, ui.last_name $direction " . $n,
            4 => "s.cost_total $direction " . $n,
            5 => "s.taxable_total $direction " . $n,
            6 => "s.non_taxable_total $direction " . $n,
            7 => "s.export_total $direction " . $n,
            9 => "IFNULL(s.comm_total, 0) + IFNULL(s.extra_charges, 0) $direction " . $n,
            10 => "s.tax_exclusive $direction " . $n,
            11 => "s.tax_inclusive $direction " . $n,
            12 => "s.tax_services $direction " . $n,
            default => '',
        };

        if ($sortColumnIndex > 0) {
            $order = " {$orderExpr} " . $n;
        } else {
            $order = " s.settlement_no DESC " . $n;
        }

        return sprintf('ORDER BY %s ', $order);
    }

    /**
     * Get Query Parts
     * @return array
     */
    protected function getQueryParts(): array
    {
        if ($this->queryParts === null) {
            $this->buildQueryParts();
        }
        return $this->queryParts;
    }

    protected function buildQueryParts(): void
    {
        $this->queryParts = [
            'select' => $this->getSelectClause(),
            'from' => $this->getFromClause(),
            'where' => $this->getWhereClause(),
            'order' => $this->getOrderClause(),
        ];
    }

    /**
     * @return string
     */
    public function buildResultQuery(): string
    {
        $this->getNumberFormatter()->construct($this->getSystemAccountId());
        $resultQuery = null;
        $queryParts = $this->getQueryParts();
        if ($queryParts) {
            $resultQuery = $queryParts['select']
                . $queryParts['from']
                . $queryParts['where']
                . $queryParts['order'];
        }
        return $resultQuery;
    }
}
