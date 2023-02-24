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

namespace Sam\Invoice\StackedTax\Generate\Note;

/**
 * Trait InvoiceNoteBuilderAwareTrait
 * @package Sam\Invoice\StackedTax\Generate\Note
 */
trait StackedTaxInvoiceNoteBuilderAwareTrait
{
    /**
     * @var StackedTaxInvoiceNoteBuilder|null
     */
    protected ?StackedTaxInvoiceNoteBuilder $stackedTaxInvoiceNoteBuilder = null;

    /**
     * @return StackedTaxInvoiceNoteBuilder
     */
    protected function getStackedTaxInvoiceNoteBuilder(): StackedTaxInvoiceNoteBuilder
    {
        if ($this->stackedTaxInvoiceNoteBuilder === null) {
            $this->stackedTaxInvoiceNoteBuilder = StackedTaxInvoiceNoteBuilder::new();
        }
        return $this->stackedTaxInvoiceNoteBuilder;
    }

    /**
     * @param StackedTaxInvoiceNoteBuilder $stackedTaxInvoiceNoteBuilder
     * @return static
     * @internal
     */
    public function setStackedTaxInvoiceNoteBuilder(StackedTaxInvoiceNoteBuilder $stackedTaxInvoiceNoteBuilder): static
    {
        $this->stackedTaxInvoiceNoteBuilder = $stackedTaxInvoiceNoteBuilder;
        return $this;
    }
}
