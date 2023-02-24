<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Calculate\CommissionFeeCalculator;

use Sam\Consignor\Commission\Calculate\CommissionFeeCalculator\Internal\Load\ConsignorCommissionFeeApplicableRangeLoaderCreateTrait;
use Sam\Consignor\Commission\Load\ConsignorCommissionFeeLoaderCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * This class is responsible for calculating commission or fee amount
 * based on the reference amount and the parameters from ConsignorCommissionFee entity.
 *
 * Tiered/sliding calculation methods described in the ticket https://bidpath.atlassian.net/browse/SAM-3382
 *
 * Class ConsignorCommissionFeeCalculator
 * @package Sam\Consignor\Commission\Calculate
 */
class ConsignorCommissionFeeCalculator extends CustomizableClass
{
    use ConsignorCommissionFeeLoaderCreateTrait;
    use ConsignorCommissionFeeApplicableRangeLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calculate commission or fee amount based on calculation method.
     * The basis of calculation is a reference amount:
     *  - Hammer price for sold lot commission
     *  - Result of Sam/Consignor/Commission/FeeReference/ConsignorFeeReferenceAmountDetector::detect for sold/unsold fee
     *
     * @param float $referenceAmount
     * @param int $commissionFeeId
     * @return float
     */
    public function calculate(float $referenceAmount, int $commissionFeeId): float
    {
        $rule = $this->createConsignorCommissionFeeLoader()->load($commissionFeeId);
        if (!$rule) {
            return 0;
        }
        $commission = $rule->isTieredCalculationMethod()
            ? $this->calculateTiered($referenceAmount, $commissionFeeId)
            : $this->calculateSliding($referenceAmount, $commissionFeeId);
        return $commission;
    }

    /**
     * @param float $referenceAmount
     * @param int $consignorCommissionFeeId
     * @return float
     */
    protected function calculateSliding(float $referenceAmount, int $consignorCommissionFeeId): float
    {
        $range = $this->createConsignorCommissionFeeApplicableRangeLoader()->loadForSliding($consignorCommissionFeeId, $referenceAmount);
        if (!$range) {
            return 0.;
        }
        $percentAmount = $referenceAmount * $range->Percent / 100;
        $result = $range->isGreaterMode()
            ? max($range->Fixed, $percentAmount)
            : $range->Fixed + $percentAmount;
        return $result;
    }

    /**
     * @param float $referenceAmount
     * @param int $consignorCommissionFeeId
     * @return float
     */
    protected function calculateTiered(float $referenceAmount, int $consignorCommissionFeeId): float
    {
        $total = 0.;
        $ranges = $this->createConsignorCommissionFeeApplicableRangeLoader()->loadForTiered($consignorCommissionFeeId, $referenceAmount);
        foreach ($ranges as $index => $range) {
            $previousRangeStartAmount = $ranges[$index - 1]->Amount ?? $referenceAmount;
            $rangeReferenceAmount = $previousRangeStartAmount - $range->Amount;

            $percentAmount = $rangeReferenceAmount * $range->Percent / 100;
            if ($range->isGreaterMode()) {
                $total += max($range->Fixed, $percentAmount);
            } else {
                $total += $range->Fixed + $percentAmount;
            }
        }
        return $total;
    }
}
