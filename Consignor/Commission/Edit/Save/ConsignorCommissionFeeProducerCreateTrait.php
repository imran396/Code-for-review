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
 * Trait ConsignorCommissionFeeProducerCreateTrait
 * @package Sam\Consignor\Commission\Edit\Save
 */
trait ConsignorCommissionFeeProducerCreateTrait
{
    /**
     * @var ConsignorCommissionFeeProducer|null
     */
    protected ?ConsignorCommissionFeeProducer $consignorCommissionFeeProducer = null;

    /**
     * @return ConsignorCommissionFeeProducer
     */
    protected function createConsignorCommissionFeeProducer(): ConsignorCommissionFeeProducer
    {
        return $this->consignorCommissionFeeProducer ?: ConsignorCommissionFeeProducer::new();
    }

    /**
     * @param ConsignorCommissionFeeProducer $consignorCommissionFeeProducer
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeProducer(ConsignorCommissionFeeProducer $consignorCommissionFeeProducer): static
    {
        $this->consignorCommissionFeeProducer = $consignorCommissionFeeProducer;
        return $this;
    }
}
