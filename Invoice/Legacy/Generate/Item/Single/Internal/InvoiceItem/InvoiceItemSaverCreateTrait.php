<?php
/**
 * SAM-9721: Refactor and implement unit test for single invoice producer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Generate\Item\Single\Internal\InvoiceItem;

/**
 * Trait InvoiceItemSaverCreateTrait
 * @package Sam\Invoice\Legacy\Generate\Item\Single\Internal\InvoiceItem
 */
trait InvoiceItemSaverCreateTrait
{
    protected ?InvoiceItemSaver $invoiceItemSaver = null;

    /**
     * @return InvoiceItemSaver
     */
    protected function createInvoiceItemSaver(): InvoiceItemSaver
    {
        return $this->invoiceItemSaver ?: InvoiceItemSaver::new();
    }

    /**
     * @param InvoiceItemSaver $invoiceItemSaver
     * @return $this
     * @internal
     */
    public function setInvoiceItemSaver(InvoiceItemSaver $invoiceItemSaver): static
    {
        $this->invoiceItemSaver = $invoiceItemSaver;
        return $this;
    }
}
