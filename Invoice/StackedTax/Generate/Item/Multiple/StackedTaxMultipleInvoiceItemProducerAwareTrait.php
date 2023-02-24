<?php
/**
 * SAM-10824: Stacked Tax. Tax calculation on invoicing
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           25.08.2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\StackedTax\Generate\Item\Multiple;

/**
 * Trait MultipleInvoiceItemProducerAwareTrait
 * @package Sam\Invoice\StackedTax\Generate\Item
 */
trait StackedTaxMultipleInvoiceItemProducerAwareTrait
{
    /**
     * @var StackedTaxMultipleInvoiceItemProducer|null
     */
    protected ?StackedTaxMultipleInvoiceItemProducer $stackedTaxMultipleInvoiceItemProducer = null;

    /**
     * @return StackedTaxMultipleInvoiceItemProducer
     */
    protected function getStackedTaxMultipleInvoiceItemProducer(): StackedTaxMultipleInvoiceItemProducer
    {
        if ($this->stackedTaxMultipleInvoiceItemProducer === null) {
            $this->stackedTaxMultipleInvoiceItemProducer = StackedTaxMultipleInvoiceItemProducer::new();
        }
        return $this->stackedTaxMultipleInvoiceItemProducer;
    }

    /**
     * @param StackedTaxMultipleInvoiceItemProducer $stackedTaxMultipleInvoiceItemProducer
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setStackedTaxMultipleInvoiceItemProducer(StackedTaxMultipleInvoiceItemProducer $stackedTaxMultipleInvoiceItemProducer): static
    {
        $this->stackedTaxMultipleInvoiceItemProducer = $stackedTaxMultipleInvoiceItemProducer;
        return $this;
    }
}
