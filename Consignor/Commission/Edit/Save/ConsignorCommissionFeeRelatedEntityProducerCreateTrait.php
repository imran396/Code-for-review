<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Save;

/**
 * Trait ConsignorCommissionFeeRelatedEntityProducerCreateTrait
 * @package Sam\Consignor\Commission\Edit\Save
 */
trait ConsignorCommissionFeeRelatedEntityProducerCreateTrait
{
    /**
     * @var ConsignorCommissionFeeRelatedEntityProducer|null
     */
    protected ?ConsignorCommissionFeeRelatedEntityProducer $consignorCommissionFeeRelatedEntityProducer = null;

    /**
     * @return ConsignorCommissionFeeRelatedEntityProducer
     */
    protected function createConsignorCommissionFeeRelatedEntityProducer(): ConsignorCommissionFeeRelatedEntityProducer
    {
        return $this->consignorCommissionFeeRelatedEntityProducer ?: ConsignorCommissionFeeRelatedEntityProducer::new();
    }

    /**
     * @param ConsignorCommissionFeeRelatedEntityProducer $consignorCommissionFeeRelatedEntityProducer
     * @return static
     * @internal
     */
    public function setConsignorCommissionFeeRelatedEntityProducer(ConsignorCommissionFeeRelatedEntityProducer $consignorCommissionFeeRelatedEntityProducer): static
    {
        $this->consignorCommissionFeeRelatedEntityProducer = $consignorCommissionFeeRelatedEntityProducer;
        return $this;
    }
}
