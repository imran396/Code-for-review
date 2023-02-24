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

namespace Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Validate;

/**
 * Trait InvoiceItemFormInvoiceEditingValidatorCreateTrait
 * @package Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Validate
 */
trait InvoiceItemFormInvoiceEditValidatorCreateTrait
{
    protected ?InvoiceItemFormInvoiceEditValidator $invoiceItemFormInvoiceEditValidator = null;

    /**
     * @return InvoiceItemFormInvoiceEditValidator
     */
    protected function createInvoiceItemFormInvoiceEditValidator(): InvoiceItemFormInvoiceEditValidator
    {
        return $this->invoiceItemFormInvoiceEditValidator ?: InvoiceItemFormInvoiceEditValidator::new();
    }

    /**
     * @param InvoiceItemFormInvoiceEditValidator $invoiceItemFormInvoiceEditValidator
     * @return $this
     * @internal
     */
    public function setInvoiceItemFormInvoiceEditValidator(InvoiceItemFormInvoiceEditValidator $invoiceItemFormInvoiceEditValidator): static
    {
        $this->invoiceItemFormInvoiceEditValidator = $invoiceItemFormInvoiceEditValidator;
        return $this;
    }
}
