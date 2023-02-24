<?php
/**
 * SAM-6499: Refactor Settlement Calculator module (2020 year)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Calculate\Internal\Load\GetSettlementTotals;


use Sam\Core\Service\CustomizableClass;
use QMySqli5DatabaseResult;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Settlement\Calculate\Internal\Load\Dto\SettlementTotalsDto;

/**
 * Class GetSettlementTotalsQueryHandler
 * @package Sam\Settlement\Calculate\Internal\Load\GetSettlementTotal
 * @internal
 */
class GetSettlementTotalsQueryHandler extends CustomizableClass
{
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param GetSettlementTotalsQuery $query
     * @return SettlementTotalsDto|null
     */
    public function handle(GetSettlementTotalsQuery $query): ?SettlementTotalsDto
    {
        $sqlQuery = $this->buildSqlQuery($query);
        $dbResult = $this->query($sqlQuery);
        $row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        if ($row) {
            $totals = SettlementTotalsDto::new()->fromDbRow($row);
        } else {
            $totals = null;
        }
        return $totals;
    }

    /**
     * @param GetSettlementTotalsQuery $query
     * @return string
     */
    protected function buildSqlQuery(GetSettlementTotalsQuery $query): string
    {
        $taxInclusiveSelect = $this->buildTaxInclusiveSelectExpr($query);
        $taxExclusiveSelect = $this->buildTaxExclusiveSelectExpr($query);
        $taxServiceSelect = $this->buildTaxServiceSelectExpr($query);

        $query = <<<SQL
SELECT s.id                                                   AS id,
       s.created_on                                           AS created_on,
       s.settlement_status_id                                 AS settlement_status_id,

       @total_hp := IFNULL(set_sum.total_hp, 0)               AS total_hp,
       @total_comm := IFNULL(set_sum.total_comm, 0)           AS total_comm,
       @total_fee := IFNULL(set_sum.total_fee, 0)             AS total_fee,
       @total_cost := IFNULL(set_sum.total_cost, 0)           AS total_cost,

       @total_charge := IFNULL(set_charge.total_charge, 0)    AS total_charge,
       @total_payment := IFNULL(set_payment.total_payment, 0) AS total_payment,
       {$taxInclusiveSelect}                                  AS tax_inclusive,
       {$taxExclusiveSelect}                                  AS tax_exclusive,
       {$taxServiceSelect}                                    AS tax_service
FROM settlement AS s
LEFT JOIN (
    SELECT si.settlement_id                AS settlement_id,
           SUM(IFNULL(si.hammer_price, 0)) AS total_hp,
           SUM(IFNULL(si.commission, 0))   AS total_comm,
           SUM(IFNULL(si.fee, 0))          AS total_fee,
           SUM(IFNULL(li.cost, 0))         AS total_cost
    FROM settlement_item AS si
             INNER JOIN lot_item AS li ON si.lot_item_id = li.id
    WHERE si.active = TRUE
    GROUP BY si.settlement_id
) AS set_sum ON s.id = set_sum.settlement_id
LEFT JOIN (
    SELECT settlement_id,
           SUM(amount) AS total_charge
    FROM settlement_additional
    GROUP BY settlement_id
) AS set_charge ON s.id = set_charge.settlement_id
LEFT JOIN (
    SELECT tran_id,
           SUM(amount) AS total_payment
    FROM payment
    WHERE tran_type = {$this->escape(Constants\Payment::TT_SETTLEMENT)} AND active = true
    GROUP BY tran_id
) AS set_payment ON s.id = set_payment.tran_id
WHERE s.id = {$this->escape($query->getSettlementId())}
SQL;
        return $query;
    }

    /**
     * @param GetSettlementTotalsQuery $query
     * @return string
     */
    protected function buildTaxServiceSelectExpr(GetSettlementTotalsQuery $query): string
    {
        $additions = ['0'];
        if ($query->isConsignorTaxServices()) {
            $additions[] = '@total_charge';
        }
        $consignorTax = $query->getConsignorTax() / 100;
        $sumExpr = implode(' + ', $additions);
        return "({$sumExpr}) * {$consignorTax}";
    }

    /**
     * @param GetSettlementTotalsQuery $query
     * @return string
     */
    protected function buildTaxInclusiveSelectExpr(GetSettlementTotalsQuery $query): string
    {
        $inclusiveTax = 1 + $query->getConsignorTax() / 100;
        $additions = ['0'];
        if ($query->isConsignorTaxHpInclusive()) {
            $additions[] = "ROUND(@total_hp - ROUND((@total_hp/$inclusiveTax),2),2)";
        }
        if ($query->isConsignorTaxCommissionInclusive()) {
            $additions[] = "ROUND((@total_comm + @total_fee) - ROUND(((@total_comm + @total_fee)/$inclusiveTax),2),2)";
        }
        $sumExpr = implode(' + ', $additions);
        return $sumExpr;
    }

    /**
     * @param GetSettlementTotalsQuery $query
     * @return string
     */
    protected function buildTaxExclusiveSelectExpr(GetSettlementTotalsQuery $query): string
    {
        $additions = ['0'];
        if ($query->isConsignorTaxHpExclusive()) {
            $additions[] = '@total_hp';
        }
        $consignorTax = $query->getConsignorTax() / 100;
        $sumExpr = implode(' + ', $additions);
        return "({$sumExpr}) * {$consignorTax}";
    }
}
