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

namespace Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Validate;

/**
 * Trait InvoiceItemEditFormValidatorCreateTrait
 * @package Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Validate
 */
trait InvoiceItemEditFormValidatorCreateTrait
{
    protected ?InvoiceItemEditFormValidator $invoiceItemEditFormValidator = null;

    /**
     * @return InvoiceItemEditFormValidator
     */
    protected function createInvoiceItemEditFormValidator(): InvoiceItemEditFormValidator
    {
        return $this->invoiceItemEditFormValidator ?: InvoiceItemEditFormValidator::new();
    }

    /**
     * @param InvoiceItemEditFormValidator $invoiceItemEditFormValidator
     * @return static
     * @internal
     */
    public function setInvoiceItemEditFormValidator(InvoiceItemEditFormValidator $invoiceItemEditFormValidator): static
    {
        $this->invoiceItemEditFormValidator = $invoiceItemEditFormValidator;
        return $this;
    }
}
