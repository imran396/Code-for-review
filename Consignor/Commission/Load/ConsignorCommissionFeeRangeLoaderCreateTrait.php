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

namespace Sam\Consignor\Commission\Load;

/**
 * Trait ConsignorCommissionFeeRangeLoaderCreateTrait
 * @package Sam\Consignor\Commission\Load
 */
trait ConsignorCommissionFeeRangeLoaderCreateTrait
{
    /**
     * @var ConsignorCommissionFeeRangeLoader|null
     */
    protected ?ConsignorCommissionFeeRangeLoader $consignorCommissionFeeRangeLoader = null;

    /**
     * @return ConsignorCommissionFeeRangeLoader
     */
    protected function createConsignorCommissionFeeRangeLoader(): ConsignorCommissionFeeRangeLoader
    {
        return $this->consignorCommissionFeeRangeLoader ?: ConsignorCommissionFeeRangeLoader::new();
    }

    /**
     * @param ConsignorCommissionFeeRangeLoader $consignorCommissionFeeRangeLoader
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeRangeLoader(ConsignorCommissionFeeRangeLoader $consignorCommissionFeeRangeLoader): static
    {
        $this->consignorCommissionFeeRangeLoader = $consignorCommissionFeeRangeLoader;
        return $this;
    }
}
