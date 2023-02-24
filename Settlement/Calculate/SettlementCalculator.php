<?php
/**
 * SAM-4365: Settlement Calculator module
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/9/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Calculate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settlement\Calculate\Internal\Load\SettlementCalculatorDataLoaderAwareTrait;
use Sam\Settlement\Load\SettlementLoaderAwareTrait;
use SettlementItem;

/**
 * Class SettlementCalculator
 * @package Sam\Settlement\Calculate
 */
class SettlementCalculator extends CustomizableClass
{
    use SettingsManagerAwareTrait;
    use SettlementCalculatorDataLoaderAwareTrait;
    use SettlementLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calc BalanceDue
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcBalanceDue(int $settlementId, bool $isReadOnlyDb = false): float
    {
        $totalDue = $this->calcTotalDue($settlementId, $isReadOnlyDb);
        $totalPayments = $this->calcTotalPayments($settlementId, $isReadOnlyDb);
        $balanceDue = $totalDue - $totalPayments;
        return $balanceDue;
    }

    /**
     * Calc BalanceDue
     * @param int $settlementId
     * @param int $precision
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcRoundedBalanceDue(int $settlementId, int $precision = 2, bool $isReadOnlyDb = false): float
    {
        $totalDue = $this->calcTotalDue($settlementId, $isReadOnlyDb);
        $totalPayments = $this->calcTotalPayments($settlementId, $isReadOnlyDb);
        $balanceDue = round($totalDue, $precision) - $totalPayments;
        return $balanceDue;
    }

    /**
     * Get Grand Total
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float|null
     */
    public function calcTotalDue(int $settlementId, bool $isReadOnlyDb = false): ?float
    {
        $settlement = $this->getSettlementLoader()->load($settlementId, $isReadOnlyDb);
        if (!$settlement) {
            log_debug('Settlement info not found!');
            return null;
        }

        $accountId = $settlement->AccountId;

        $isSettlementUnpaidLots = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::SETTLEMENT_UNPAID_LOTS, $accountId);

        if ($settlement->ConsignorTax) { // Has consignor tax
            $charges = $this->calcTotalChargesWithTax($settlementId, $isReadOnlyDb);
        } else {
            $charges = $this->calcTotalCharges($settlementId, $isReadOnlyDb);
        }

        $total = $isSettlementUnpaidLots
            ? (float)$this->calcPaidLots($settlementId, $isReadOnlyDb)
            : $this->calcTotal($settlementId, $isReadOnlyDb);

        $grandTotal = $total - $charges;
        return $grandTotal;
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float|null
     */
    public function calcPaidLots(int $settlementId, bool $isReadOnlyDb = false): ?float
    {
        $settlement = $this->getSettlementLoader()->load($settlementId, $isReadOnlyDb);
        if (!$settlement) {
            log_debug('Settlement info not found;');
            return null;
        }

        $isChargeConsignorCommission = $this->getSettingsManager()
            ->get(Constants\Setting::CHARGE_CONSIGNOR_COMMISSION, $settlement->AccountId);
        $result = $isChargeConsignorCommission
            ? $this->getSettlementCalculatorDataLoader()->calcPaidLotsConsignmentCommission($settlementId, $isReadOnlyDb)
            : $this->getSettlementCalculatorDataLoader()->calcPaidLotsSubTotal($settlementId, $isReadOnlyDb);
        return $result;
    }

    /**
     * Calculate settlement_item.subtotal value for passed SettlementItem
     *
     * @param SettlementItem $settlementItem
     * @param array $optionals = [
     *  'settlement' => Settlement, pass to prevent load
     * ]
     * @return float
     */
    public function calcSubtotal(SettlementItem $settlementItem, array $optionals = []): float
    {
        $hammerPrice = $settlementItem->HammerPrice;
        $commission = $settlementItem->Commission;
        $fee = $settlementItem->Fee;

        $settlement = $optionals['settlement'] ?? $this->getSettlementLoader()->load($settlementItem->SettlementId);
        if (
            $settlement
            && $settlement->ConsignorTax // Has consignor tax
        ) {
            $taxRate = $settlement->ConsignorTax / 100;
            if (
                $settlement->ConsignorTaxHp
                && $settlement->isConsignorTaxHpExclusive()
                && $settlementItem->hasHammerPrice()
            ) {  // Tax applied to hammer price
                $taxOnHp = $hammerPrice * $taxRate;
                log_debug("hpWithTax: $hammerPrice + $taxOnHp");
                $hammerPrice += $taxOnHp;
            }

            $taxInclusive = $settlement->ConsignorTaxHp
                && $settlement->isConsignorTaxHpInclusive();
            if (
                $settlement->ConsignorTaxComm
                && !$taxInclusive
            ) {
                $taxOnComm = $commission * $taxRate;
                log_debug("commWithTax: $commission + $taxOnComm");
                $commission += $taxOnComm;

                $taxOnFee = $fee * $taxRate;
                log_debug("feeWithTax: $fee + $taxOnFee");
                $fee += $taxOnFee;
            }
        }

        $subtotal = $this->calcSubtotalByHpWithoutCommissionAndFee($hammerPrice, $commission, $fee);
        return $subtotal;
    }

    /**
     * Calculate settlement_item.subtotal value for passed values.
     * Negative value is possible.
     *
     * @param float|null $hammerPrice null means 0
     * @param float|null $commission null means 0
     * @param float|null $fee null means 0
     * @return float
     */
    protected function calcSubtotalByHpWithoutCommissionAndFee(?float $hammerPrice, ?float $commission, ?float $fee): float
    {
        return (float)$hammerPrice - (float)$commission - (float)$fee;
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcTotal(int $settlementId, bool $isReadOnlyDb = false): float
    {
        return $this->getSettlementCalculatorDataLoader()->calcTotal($settlementId, $isReadOnlyDb);
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float
     */
    protected function calcTotalCharges(int $settlementId, bool $isReadOnlyDb = false): float
    {
        return $this->getSettlementCalculatorDataLoader()->calcTotalCharges($settlementId, $isReadOnlyDb);
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float|null
     */
    public function calcTotalChargesWithTax(int $settlementId, bool $isReadOnlyDb = false): ?float
    {
        $settlement = $this->getSettlementLoader()->load($settlementId);
        if (!$settlement) {
            log_debug('Settlement info not found!;');
            return null;
        }
        $charges = $this->calcTotalCharges($settlementId, $isReadOnlyDb);
        if (
            $settlement->ConsignorTax // Has consignor tax
            && $settlement->ConsignorTaxServices //Tax applied to services
        ) {
            $taxRate = $settlement->ConsignorTax / 100;
            $taxOnCharges = $charges * $taxRate;
            log_debug("chargesWithTax: $charges + $taxOnCharges");
            $charges += $taxOnCharges;
        }
        return $charges;
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float|null
     */
    public function calcTotalCommission(int $settlementId, bool $isReadOnlyDb = false): ?float
    {
        return $this->getSettlementCalculatorDataLoader()->calcTotalCommission($settlementId, $isReadOnlyDb);
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float|null
     */
    public function calcTotalCommissionWithTax(int $settlementId, bool $isReadOnlyDb = false): ?float
    {
        $settlement = $this->getSettlementLoader()->load($settlementId);
        if (!$settlement) {
            log_debug('Settlement info not found!;');
            return null;
        }
        $commission = $this->calcTotalCommission($settlementId, $isReadOnlyDb);
        $taxInclusive = $settlement->ConsignorTaxHp
            && $settlement->isConsignorTaxHpInclusive();
        if (
            $settlement->ConsignorTax // Has consignor tax
            && $settlement->ConsignorTaxComm //Tax applied to commission
            && !$taxInclusive // Not tax inclusive
        ) {
            $taxRate = $settlement->ConsignorTax / 100;
            $taxOnComm = $commission * $taxRate;
            log_debug("commWithTax: $commission + $taxOnComm");
            $commission += $taxOnComm;
        }
        return $commission;
    }

    /**
     * Calculate total fee for settlement
     *
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcTotalFee(int $settlementId, bool $isReadOnlyDb = false): float
    {
        return $this->getSettlementCalculatorDataLoader()->calcTotalFee($settlementId, $isReadOnlyDb);
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcTotalHammerPrice(int $settlementId, bool $isReadOnlyDb = false): float
    {
        return $this->getSettlementCalculatorDataLoader()->calcTotalHammerPrice($settlementId, $isReadOnlyDb);
    }

    /**
     * @param int $tranId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function calcTotalPayments(int $tranId, bool $isReadOnlyDb = false): float
    {
        return $this->getSettlementCalculatorDataLoader()->calcTotalPayments($tranId, $isReadOnlyDb);
    }

    /**
     * @param int $settlementId
     * @param bool $isReadOnlyDb
     * @return float|null
     */
    public function calcUnpaidLots(int $settlementId, bool $isReadOnlyDb = false): ?float
    {
        $settlement = $this->getSettlementLoader()->load($settlementId, $isReadOnlyDb);
        if (!$settlement) {
            log_debug('Settlement info not found;');
            return null;
        }

        $paid = $this->calcPaidLots($settlementId, $isReadOnlyDb);
        $isChargeConsignorCommission = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::CHARGE_CONSIGNOR_COMMISSION, $settlement->AccountId);
        $subTotal = $this->calcTotal($settlementId, $isReadOnlyDb);
        if ($isChargeConsignorCommission) {
            $subTotal = $this->calcTotalCommission($settlementId, $isReadOnlyDb);
        }
        $total = $subTotal - $paid;
        return $total;
    }
}
