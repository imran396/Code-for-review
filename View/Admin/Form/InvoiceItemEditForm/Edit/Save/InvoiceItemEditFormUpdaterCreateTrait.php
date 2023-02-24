<?php
/**
 * SAM-11091: Stacked Tax. New Invoice Edit page: Invoice Item Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Save;

/**
 * Trait InvoiceItemEditFormUpdaterCreateTrait
 * @package Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Save
 */
trait InvoiceItemEditFormUpdaterCreateTrait
{
    protected ?InvoiceItemEditFormUpdater $invoiceItemEditFormUpdater = null;

    /**
     * @return InvoiceItemEditFormUpdater
     */
    protected function createInvoiceItemEditFormUpdater(): InvoiceItemEditFormUpdater
    {
        return $this->invoiceItemEditFormUpdater ?: InvoiceItemEditFormUpdater::new();
    }

    /**
     * @param InvoiceItemEditFormUpdater $invoiceItemEditFormUpdater
     * @return static
     * @internal
     */
    public function setInvoiceItemEditFormUpdater(InvoiceItemEditFormUpdater $invoiceItemEditFormUpdater): static
    {
        $this->invoiceItemEditFormUpdater = $invoiceItemEditFormUpdater;
        return $this;
    }
}
