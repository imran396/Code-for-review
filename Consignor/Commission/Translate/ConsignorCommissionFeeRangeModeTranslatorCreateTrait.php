<?php
/**
 * SAM-7974: Multiple Consignor commission rates and unsold commission extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Translate;

/**
 * Trait ConsignorCommissionFeeRangeModeTranslatorCreateTrait
 * @package Sam\Consignor\Commission\Translate
 */
trait ConsignorCommissionFeeRangeModeTranslatorCreateTrait
{
    protected ?ConsignorCommissionFeeRangeModeTranslator $consignorCommissionFeeRangeModeTranslator = null;

    /**
     * @return ConsignorCommissionFeeRangeModeTranslator
     */
    protected function createConsignorCommissionFeeRangeModeTranslator(): ConsignorCommissionFeeRangeModeTranslator
    {
        return $this->consignorCommissionFeeRangeModeTranslator ?: ConsignorCommissionFeeRangeModeTranslator::new();
    }

    /**
     * @param ConsignorCommissionFeeRangeModeTranslator $consignorCommissionFeeRangeModeTranslator
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeRangeModeTranslator(ConsignorCommissionFeeRangeModeTranslator $consignorCommissionFeeRangeModeTranslator): static
    {
        $this->consignorCommissionFeeRangeModeTranslator = $consignorCommissionFeeRangeModeTranslator;
        return $this;
    }
}
