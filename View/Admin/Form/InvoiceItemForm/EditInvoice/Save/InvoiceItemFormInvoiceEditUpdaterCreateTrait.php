<?php
/**
 * SAM-10923: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract invoice General validation and save (#invoice-save-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 05, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Save;

/**
 * Trait InvoiceItemFormInvoiceEditingUpdaterCreateTrait
 * @package Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Save
 */
trait InvoiceItemFormInvoiceEditUpdaterCreateTrait
{
    protected ?InvoiceItemFormInvoiceEditUpdater $invoiceItemFormInvoiceEditUpdater = null;

    /**
     * @return InvoiceItemFormInvoiceEditUpdater
     */
    protected function createInvoiceItemFormInvoiceEditUpdater(): InvoiceItemFormInvoiceEditUpdater
    {
        return $this->invoiceItemFormInvoiceEditUpdater ?: InvoiceItemFormInvoiceEditUpdater::new();
    }

    /**
     * @param InvoiceItemFormInvoiceEditUpdater $invoiceItemFormInvoiceEditUpdater
     * @return $this
     * @internal
     */
    public function setInvoiceItemFormInvoiceEditUpdater(InvoiceItemFormInvoiceEditUpdater $invoiceItemFormInvoiceEditUpdater): static
    {
        $this->invoiceItemFormInvoiceEditUpdater = $invoiceItemFormInvoiceEditUpdater;
        return $this;
    }
}
