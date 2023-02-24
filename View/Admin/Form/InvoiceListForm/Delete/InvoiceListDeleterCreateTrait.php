<?php
/**
 * <Description of class>
 *
 * SAM-11048: Replace GET->POST at Admin Manage invoice item Qcodo delete operation code to JavaScript code.
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceListForm\Delete;

/**
 * Trait InvoiceListDeleterCreateTrait
 * @package Sam\View\Admin\Form\InvoiceListForm\Delete
 */
trait InvoiceListDeleterCreateTrait
{
    protected ?InvoiceListDeleter $invoiceListDeleter = null;

    /**
     * @return InvoiceListDeleter
     */
    protected function createInvoiceListDeleter(): InvoiceListDeleter
    {
        return $this->invoiceListDeleter ?: InvoiceListDeleter::new();
    }

    /**
     * @param InvoiceListDeleter $invoiceListDeleter
     * @return $this
     * @internal
     */
    public function setInvoiceListDeleter(InvoiceListDeleter $invoiceListDeleter): static
    {
        $this->invoiceListDeleter = $invoiceListDeleter;
        return $this;
    }
}
