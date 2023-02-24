<?php
/**
 * SAM-11418: Avoid number formatting in API
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Normalize;

/**
 * Trait ConsignorCommissionFeeRangeNormalizerFactoryCreateTrait
 * @package Sam\Consignor\Commission\Edit\Normalize
 */
trait ConsignorCommissionFeeRangeNormalizerFactoryCreateTrait
{
    protected ?ConsignorCommissionFeeRangeNormalizerFactory $consignorCommissionFeeRangeNormalizerFactory = null;

    /**
     * @return ConsignorCommissionFeeRangeNormalizerFactory
     */
    protected function createConsignorCommissionFeeRangeNormalizerFactory(): ConsignorCommissionFeeRangeNormalizerFactory
    {
        return $this->consignorCommissionFeeRangeNormalizerFactory ?: ConsignorCommissionFeeRangeNormalizerFactory::new();
    }

    /**
     * @param ConsignorCommissionFeeRangeNormalizerFactory $consignorCommissionFeeRangeNormalizerFactory
     * @return $this
     * @internal
     */
    public function setConsignorCommissionFeeRangeNormalizerFactory(ConsignorCommissionFeeRangeNormalizerFactory $consignorCommissionFeeRangeNormalizerFactory): static
    {
        $this->consignorCommissionFeeRangeNormalizerFactory = $consignorCommissionFeeRangeNormalizerFactory;
        return $this;
    }
}
