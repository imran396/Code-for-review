<?php
/**
 * SAM-10934: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Multiple Invoice Items validation and save (#invoice-save-2)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemForm\EditMultipleItem\Save;

/**
 * Trait InvoiceItemFormMultipleItemEditUpdaterCreateTrait
 * @package Sam\View\Admin\Form\InvoiceItemForm\EditMultipleItem\Save
 */
trait InvoiceItemFormMultipleItemEditUpdaterCreateTrait
{
    protected ?InvoiceItemFormMultipleItemEditUpdater $invoiceItemFormMultipleItemEditUpdater = null;

    /**
     * @return InvoiceItemFormMultipleItemEditUpdater
     */
    protected function createInvoiceItemFormMultipleItemEditUpdater(): InvoiceItemFormMultipleItemEditUpdater
    {
        return $this->invoiceItemFormMultipleItemEditUpdater ?: InvoiceItemFormMultipleItemEditUpdater::new();
    }

    /**
     * @param InvoiceItemFormMultipleItemEditUpdater $invoiceItemFormMultipleItemEditUpdater
     * @return $this
     * @internal
     */
    public function setInvoiceItemFormMultipleItemEditUpdater(InvoiceItemFormMultipleItemEditUpdater $invoiceItemFormMultipleItemEditUpdater): static
    {
        $this->invoiceItemFormMultipleItemEditUpdater = $invoiceItemFormMultipleItemEditUpdater;
        return $this;
    }
}
