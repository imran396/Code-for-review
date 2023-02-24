<?php
/**
 * SAM-10948: Stacked Tax. Invoice Management pages. Prepare Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Produce\Internal\Note;

/**
 * Trait InvoiceNoteUpdaterCreateTrait
 * @package Sam\Invoice\StackedTax\Generate\Produce\Internal\Note
 */
trait InvoiceNoteUpdaterCreateTrait
{
    protected ?InvoiceNoteUpdater $invoiceNoteUpdater = null;

    /**
     * @return InvoiceNoteUpdater
     */
    protected function createInvoiceNoteUpdater(): InvoiceNoteUpdater
    {
        return $this->invoiceNoteUpdater ?: InvoiceNoteUpdater::new();
    }

    /**
     * @param InvoiceNoteUpdater $invoiceNoteUpdater
     * @return $this
     * @internal
     */
    public function setInvoiceNoteUpdater(InvoiceNoteUpdater $invoiceNoteUpdater): static
    {
        $this->invoiceNoteUpdater = $invoiceNoteUpdater;
        return $this;
    }
}
