<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Translate;

/**
 * Trait ConsignorCommissionFeeCalculationModeTranslatorCreateTrait
 * @package Sam\Consignor\Commission\Translate
 */
trait ConsignorCommissionFeeCalculationMethodTranslatorCreateTrait
{
    protected ?ConsignorCommissionFeeCalculationMethodTranslator $consignorCommissionFeeCalculationMethodTranslator = null;

    /**
     * @return ConsignorCommissionFeeCalculationMethodTranslator
     */
    protected function createConsignorCommissionFeeCalculationMethodTranslator(): ConsignorCommissionFeeCalculationMethodTranslator
    {
        return $this->consignorCommissionFeeCalculationMethodTranslator ?: ConsignorCommissionFeeCalculationMethodTranslator::new();
    }

    /**
     * @param ConsignorCommissionFeeCalculationMethodTranslator $consignorCommissionFeeCalculationMethodTranslator
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeCalculationMethodTranslator(ConsignorCommissionFeeCalculationMethodTranslator $consignorCommissionFeeCalculationMethodTranslator): static
    {
        $this->consignorCommissionFeeCalculationMethodTranslator = $consignorCommissionFeeCalculationMethodTranslator;
        return $this;
    }
}
