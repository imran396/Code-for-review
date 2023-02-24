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

namespace Sam\Consignor\Commission\Edit\Validate;

/**
 * Trait ConsignorCommissionFeeRangesValidatorCreateTrait
 * @package Sam\Consignor\Commission\Edit
 */
trait ConsignorCommissionFeeRangesValidatorCreateTrait
{
    /**
     * @var ConsignorCommissionFeeRangesValidator|null
     */
    protected ?ConsignorCommissionFeeRangesValidator $consignorCommissionFeeRangesValidator = null;

    /**
     * @return ConsignorCommissionFeeRangesValidator
     */
    protected function createConsignorCommissionFeeRangesValidator(): ConsignorCommissionFeeRangesValidator
    {
        return $this->consignorCommissionFeeRangesValidator ?: ConsignorCommissionFeeRangesValidator::new();
    }

    /**
     * @param ConsignorCommissionFeeRangesValidator $consignorCommissionFeeRangesValidator
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeRangesValidator(ConsignorCommissionFeeRangesValidator $consignorCommissionFeeRangesValidator): static
    {
        $this->consignorCommissionFeeRangesValidator = $consignorCommissionFeeRangesValidator;
        return $this;
    }
}
