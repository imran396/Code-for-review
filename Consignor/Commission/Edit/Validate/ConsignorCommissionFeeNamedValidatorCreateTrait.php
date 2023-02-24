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
 * Trait ConsignorCommissionFeeNamedValidatorCreateTrait
 * @package Sam\Consignor\Commission\Edit
 */
trait ConsignorCommissionFeeNamedValidatorCreateTrait
{
    /**
     * @var ConsignorCommissionFeeNamedValidator|null
     */
    protected ?ConsignorCommissionFeeNamedValidator $consignorCommissionFeeNamedValidator = null;

    /**
     * @return ConsignorCommissionFeeNamedValidator
     */
    protected function createConsignorCommissionFeeNamedValidator(): ConsignorCommissionFeeNamedValidator
    {
        return $this->consignorCommissionFeeNamedValidator ?: ConsignorCommissionFeeNamedValidator::new();
    }

    /**
     * @param ConsignorCommissionFeeNamedValidator $consignorCommissionFeeNamedValidator
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeNamedValidator(ConsignorCommissionFeeNamedValidator $consignorCommissionFeeNamedValidator): static
    {
        $this->consignorCommissionFeeNamedValidator = $consignorCommissionFeeNamedValidator;
        return $this;
    }
}
