<?php
/**
 * SAM-11127: Stacked Tax. New Invoice Edit page: Payment Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Validate;

/**
 * Trait InvoicePaymentEditFormValidatorCreateTrait
 * @package Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Validate
 */
trait InvoicePaymentEditFormValidatorCreateTrait
{
    protected ?InvoicePaymentEditFormValidator $invoicePaymentEditFormValidator = null;

    /**
     * @return InvoicePaymentEditFormValidator
     */
    protected function createInvoicePaymentEditFormValidator(): InvoicePaymentEditFormValidator
    {
        return $this->invoicePaymentEditFormValidator ?: InvoicePaymentEditFormValidator::new();
    }

    /**
     * @param InvoicePaymentEditFormValidator $invoicePaymentEditFormValidator
     * @return static
     * @internal
     */
    public function setInvoicePaymentEditFormValidator(InvoicePaymentEditFormValidator $invoicePaymentEditFormValidator): static
    {
        $this->invoicePaymentEditFormValidator = $invoicePaymentEditFormValidator;
        return $this;
    }
}
