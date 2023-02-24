<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May. 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Calculate\CommissionFeeCalculator;

/**
 * Trait ConsignorCommissionFeeCalculatorCreateTrait
 * @package Sam\Consignor\Commission\Calculate
 */
trait ConsignorCommissionFeeCalculatorCreateTrait
{
    /**
     * @var ConsignorCommissionFeeCalculator|null
     */
    protected ?ConsignorCommissionFeeCalculator $consignorCommissionFeeCalculator = null;

    /**
     * @return ConsignorCommissionFeeCalculator
     */
    protected function createConsignorCommissionFeeCalculator(): ConsignorCommissionFeeCalculator
    {
        return $this->consignorCommissionFeeCalculator ?: ConsignorCommissionFeeCalculator::new();
    }

    /**
     * @param ConsignorCommissionFeeCalculator $consignorCommissionFeeCalculator
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeCalculator(ConsignorCommissionFeeCalculator $consignorCommissionFeeCalculator): static
    {
        $this->consignorCommissionFeeCalculator = $consignorCommissionFeeCalculator;
        return $this;
    }
}
