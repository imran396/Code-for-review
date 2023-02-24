<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Validate;

/**
 * Trait ConsignorCommissionFeeValidatorCreateTrait
 * @package Sam\Consignor\Commission\Edit\Validate
 */
trait ConsignorCommissionFeeValidatorCreateTrait
{
    /**
     * @var ConsignorCommissionFeeValidator|null
     */
    protected ?ConsignorCommissionFeeValidator $consignorCommissionFeeValidator = null;

    /**
     * @return ConsignorCommissionFeeValidator
     */
    protected function createConsignorCommissionFeeValidator(): ConsignorCommissionFeeValidator
    {
        return $this->consignorCommissionFeeValidator ?: ConsignorCommissionFeeValidator::new();
    }

    /**
     * @param ConsignorCommissionFeeValidator $consignorCommissionFeeValidator
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeValidator(ConsignorCommissionFeeValidator $consignorCommissionFeeValidator): static
    {
        $this->consignorCommissionFeeValidator = $consignorCommissionFeeValidator;
        return $this;
    }
}
