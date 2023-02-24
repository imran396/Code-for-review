<?php
/**
 * SAM-4669: Invoice management modules
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
 * Trait InvoiceNoteGeneralInfoHintRendererAwareTrait
 * @package Sam\Invoice\StackedTax\Generate\Note\General
 */
trait InvoiceNoteGeneralInfoHintRendererAwareTrait
{
    /**
     * @var InvoiceNoteGeneralInfoHintRenderer|null
     */
    protected ?InvoiceNoteGeneralInfoHintRenderer $invoiceNoteGeneralInfoHintRenderer = null;

    /**
     * @return InvoiceNoteGeneralInfoHintRenderer
     */
    protected function getInvoiceNoteGeneralInfoHintRenderer(): InvoiceNoteGeneralInfoHintRenderer
    {
        if ($this->invoiceNoteGeneralInfoHintRenderer === null) {
            $this->invoiceNoteGeneralInfoHintRenderer = InvoiceNoteGeneralInfoHintRenderer::new();
        }
        return $this->invoiceNoteGeneralInfoHintRenderer;
    }

    /**
     * @param InvoiceNoteGeneralInfoHintRenderer $invoiceNoteGeneralInfoHintRenderer
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setInvoiceNoteGeneralInfoHintRenderer(InvoiceNoteGeneralInfoHintRenderer $invoiceNoteGeneralInfoHintRenderer): static
    {
        $this->invoiceNoteGeneralInfoHintRenderer = $invoiceNoteGeneralInfoHintRenderer;
        return $this;
    }
}
