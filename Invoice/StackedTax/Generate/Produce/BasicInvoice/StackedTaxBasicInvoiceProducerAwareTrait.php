<?php
/**
 * SAM-4377: Invoice producer
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01.08.2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\StackedTax\Generate\Produce\BasicInvoice;

trait StackedTaxBasicInvoiceProducerAwareTrait
{
    /**
     * @var StackedTaxBasicInvoiceProducer|null
     */
    protected ?StackedTaxBasicInvoiceProducer $stackedTaxBasicInvoiceProducer = null;

    /**
     * @return StackedTaxBasicInvoiceProducer
     */
    protected function getStackedTaxBasicInvoiceProducer(): StackedTaxBasicInvoiceProducer
    {
        if ($this->stackedTaxBasicInvoiceProducer === null) {
            $this->stackedTaxBasicInvoiceProducer = StackedTaxBasicInvoiceProducer::new();
        }
        return $this->stackedTaxBasicInvoiceProducer;
    }

    /**
     * @param StackedTaxBasicInvoiceProducer $stackedTaxBasicInvoiceProducer
     * @return static
     * @internal
     */
    public function setStackedTaxBasicInvoiceProducer(StackedTaxBasicInvoiceProducer $stackedTaxBasicInvoiceProducer): static
    {
        $this->stackedTaxBasicInvoiceProducer = $stackedTaxBasicInvoiceProducer;
        return $this;
    }
}
