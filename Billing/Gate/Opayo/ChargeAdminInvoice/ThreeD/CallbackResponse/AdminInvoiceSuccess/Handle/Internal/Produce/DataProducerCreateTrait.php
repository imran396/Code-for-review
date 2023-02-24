<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Sept 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceSuccess\Handle\Internal\Produce;

trait DataProducerCreateTrait
{
    /**
     * @var DataProducer|null
     */
    protected ?DataProducer $producer = null;

    /**
     * @return DataProducer
     */
    protected function createDataProducer(): DataProducer
    {
        return $this->producer ?: DataProducer::new();
    }

    /**
     * @param DataProducer $producer
     * @return static
     * @internal
     */
    public function setDataProducer(DataProducer $producer): static
    {
        $this->producer = $producer;
        return $this;
    }
}
