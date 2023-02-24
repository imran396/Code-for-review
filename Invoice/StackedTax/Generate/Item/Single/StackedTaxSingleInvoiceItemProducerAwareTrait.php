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

namespace Sam\Invoice\StackedTax\Generate\Item\Single;

trait StackedTaxSingleInvoiceItemProducerAwareTrait
{
    /**
     * @var StackedTaxSingleInvoiceItemProducer|null
     */
    protected ?StackedTaxSingleInvoiceItemProducer $stackedTaxSingleInvoiceItemProducer = null;

    /**
     * @return StackedTaxSingleInvoiceItemProducer
     */
    protected function getStackedTaxSingleInvoiceItemProducer(): StackedTaxSingleInvoiceItemProducer
    {
        if ($this->stackedTaxSingleInvoiceItemProducer === null) {
            $this->stackedTaxSingleInvoiceItemProducer = StackedTaxSingleInvoiceItemProducer::new();
        }
        return $this->stackedTaxSingleInvoiceItemProducer;
    }

    /**
     * @param StackedTaxSingleInvoiceItemProducer $stackedTaxSingleInvoiceItemProducer
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setStackedTaxSingleInvoiceItemProducer(StackedTaxSingleInvoiceItemProducer $stackedTaxSingleInvoiceItemProducer): static
    {
        $this->stackedTaxSingleInvoiceItemProducer = $stackedTaxSingleInvoiceItemProducer;
        return $this;
    }
}
