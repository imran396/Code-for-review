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

namespace Sam\Consignor\Commission\Delete;

/**
 * Trait ConsignorCommissionFeeRangeDeleterCreateTrait
 * @package Sam\Consignor\Commission\Delete
 */
trait ConsignorCommissionFeeRangeDeleterCreateTrait
{
    /**
     * @var ConsignorCommissionFeeRangeDeleter|null
     */
    protected ?ConsignorCommissionFeeRangeDeleter $consignorCommissionFeeRangeDeleter = null;

    /**
     * @return ConsignorCommissionFeeRangeDeleter
     */
    protected function createConsignorCommissionFeeRangeDeleter(): ConsignorCommissionFeeRangeDeleter
    {
        return $this->consignorCommissionFeeRangeDeleter ?: ConsignorCommissionFeeRangeDeleter::new();
    }

    /**
     * @param ConsignorCommissionFeeRangeDeleter $consignorCommissionFeeRangeDeleter
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeRangeDeleter(ConsignorCommissionFeeRangeDeleter $consignorCommissionFeeRangeDeleter): static
    {
        $this->consignorCommissionFeeRangeDeleter = $consignorCommissionFeeRangeDeleter;
        return $this;
    }
}
