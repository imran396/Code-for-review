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

namespace Sam\Consignor\Commission\Edit\Save;

/**
 * Trait ConsignorCommissionFeeRangesProducerCreateTrait
 * @package Sam\Consignor\Commission\Edit\Save
 */
trait ConsignorCommissionFeeRangesProducerCreateTrait
{
    /**
     * @var ConsignorCommissionFeeRangesProducer|null
     */
    protected ?ConsignorCommissionFeeRangesProducer $consignorCommissionFeeRangesProducer = null;

    /**
     * @return ConsignorCommissionFeeRangesProducer
     */
    protected function createConsignorCommissionFeeRangesProducer(): ConsignorCommissionFeeRangesProducer
    {
        return $this->consignorCommissionFeeRangesProducer ?: ConsignorCommissionFeeRangesProducer::new();
    }

    /**
     * @param ConsignorCommissionFeeRangesProducer $consignorCommissionFeeRangesProducer
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeRangesProducer(ConsignorCommissionFeeRangesProducer $consignorCommissionFeeRangesProducer): static
    {
        $this->consignorCommissionFeeRangesProducer = $consignorCommissionFeeRangesProducer;
        return $this;
    }
}
