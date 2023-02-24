<?php
/**
 * SAM-10995: Stacked Tax. New Invoice Edit page: Initial layout and header section
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Validate;

/**
 * Trait InvoiceHeaderValidatorCreateTrait
 * @package Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Validate
 */
trait InvoiceHeaderValidatorCreateTrait
{
    protected ?InvoiceHeaderValidator $invoiceHeaderValidator = null;

    /**
     * @return InvoiceHeaderValidator
     */
    protected function createInvoiceHeaderValidator(): InvoiceHeaderValidator
    {
        return $this->invoiceHeaderValidator ?: InvoiceHeaderValidator::new();
    }

    /**
     * @param InvoiceHeaderValidator $invoiceHeaderValidator
     * @return static
     * @internal
     */
    public function setInvoiceHeaderValidator(InvoiceHeaderValidator $invoiceHeaderValidator): static
    {
        $this->invoiceHeaderValidator = $invoiceHeaderValidator;
        return $this;
    }
}
