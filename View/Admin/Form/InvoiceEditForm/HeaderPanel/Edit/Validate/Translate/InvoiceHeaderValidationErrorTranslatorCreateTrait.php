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

namespace Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Validate\Translate;

/**
 * Trait InvoiceHeaderValidationErrorTranslatorCreateTrait
 * @package Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Validate\Translate
 */
trait InvoiceHeaderValidationErrorTranslatorCreateTrait
{
    protected ?InvoiceHeaderValidationErrorTranslator $invoiceHeaderValidationErrorTranslator = null;

    /**
     * @return InvoiceHeaderValidationErrorTranslator
     */
    protected function createInvoiceHeaderValidationErrorTranslator(): InvoiceHeaderValidationErrorTranslator
    {
        return $this->invoiceHeaderValidationErrorTranslator ?: InvoiceHeaderValidationErrorTranslator::new();
    }

    /**
     * @param InvoiceHeaderValidationErrorTranslator $invoiceHeaderValidationErrorTranslator
     * @return static
     * @internal
     */
    public function setInvoiceHeaderValidationErrorTranslator(InvoiceHeaderValidationErrorTranslator $invoiceHeaderValidationErrorTranslator): static
    {
        $this->invoiceHeaderValidationErrorTranslator = $invoiceHeaderValidationErrorTranslator;
        return $this;
    }
}
