<?php
/**
 * SAM-11127: Stacked Tax. New Invoice Edit page: Payment Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Validate\Translate;

/**
 * Trait InvoicePaymentEditFormValidationResultTranslatorCreateTrait
 * @package Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Validate\Translate
 */
trait InvoicePaymentEditFormValidationResultTranslatorCreateTrait
{
    protected ?InvoicePaymentEditFormValidationResultTranslator $invoicePaymentEditFormValidationResultTranslator = null;

    /**
     * @return InvoicePaymentEditFormValidationResultTranslator
     */
    protected function createInvoicePaymentEditFormValidationResultTranslator(): InvoicePaymentEditFormValidationResultTranslator
    {
        return $this->invoicePaymentEditFormValidationResultTranslator ?: InvoicePaymentEditFormValidationResultTranslator::new();
    }

    /**
     * @param InvoicePaymentEditFormValidationResultTranslator $invoicePaymentEditFormValidationResultTranslator
     * @return static
     * @internal
     */
    public function setInvoicePaymentEditFormValidationResultTranslator(InvoicePaymentEditFormValidationResultTranslator $invoicePaymentEditFormValidationResultTranslator): static
    {
        $this->invoicePaymentEditFormValidationResultTranslator = $invoicePaymentEditFormValidationResultTranslator;
        return $this;
    }
}
