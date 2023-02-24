<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           27.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Generate\Note\General;

/**
 * Trait InvoiceNoteGeneralInfoHintRendererAwareTrait
 * @package Sam\Invoice\Legacy\Generate\Note\General
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
