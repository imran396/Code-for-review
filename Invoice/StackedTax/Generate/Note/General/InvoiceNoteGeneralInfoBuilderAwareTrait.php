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

namespace Sam\Invoice\StackedTax\Generate\Note\General;

/**
 * Trait InvoiceNoteGeneralInfoBuilderAwareTrait
 * @package Sam\Invoice\StackedTax\Generate\Note\General
 */
trait InvoiceNoteGeneralInfoBuilderAwareTrait
{
    /**
     * @var InvoiceNoteGeneralInfoBuilder|null
     */
    protected ?InvoiceNoteGeneralInfoBuilder $invoiceNoteGeneralInfoBuilder = null;

    /**
     * @return InvoiceNoteGeneralInfoBuilder
     */
    protected function getInvoiceNoteGeneralInfoBuilder(): InvoiceNoteGeneralInfoBuilder
    {
        if ($this->invoiceNoteGeneralInfoBuilder === null) {
            $this->invoiceNoteGeneralInfoBuilder = InvoiceNoteGeneralInfoBuilder::new();
        }
        return $this->invoiceNoteGeneralInfoBuilder;
    }

    /**
     * @param InvoiceNoteGeneralInfoBuilder $invoiceNoteGeneralInfoBuilder
     * @return static
     * @internal
     */
    public function setInvoiceNoteGeneralInfoBuilder(InvoiceNoteGeneralInfoBuilder $invoiceNoteGeneralInfoBuilder): static
    {
        $this->invoiceNoteGeneralInfoBuilder = $invoiceNoteGeneralInfoBuilder;
        return $this;
    }
}
