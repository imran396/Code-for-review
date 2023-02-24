<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Delete;

/**
 * Trait ConsignorCommissionFeeDeleterCreateTrait
 * @package Sam\Consignor\Commission\Delete
 */
trait ConsignorCommissionFeeDeleterCreateTrait
{
    /**
     * @var ConsignorCommissionFeeDeleter|null
     */
    protected ?ConsignorCommissionFeeDeleter $consignorCommissionFeeDeleter = null;

    /**
     * @return ConsignorCommissionFeeDeleter
     */
    protected function createConsignorCommissionFeeDeleter(): ConsignorCommissionFeeDeleter
    {
        return $this->consignorCommissionFeeDeleter ?: ConsignorCommissionFeeDeleter::new();
    }

    /**
     * @param ConsignorCommissionFeeDeleter $consignorCommissionFeeDeleter
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeDeleter(ConsignorCommissionFeeDeleter $consignorCommissionFeeDeleter): static
    {
        $this->consignorCommissionFeeDeleter = $consignorCommissionFeeDeleter;
        return $this;
    }
}
