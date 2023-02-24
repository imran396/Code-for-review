<?php
/**
 * SAM-5412: Validations at controller layer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/24/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Invoice\Validate;

/**
 * Trait InvoiceControllerValidatorCreateTrait
 * @package
 */
trait InvoiceControllerValidatorCreateTrait
{
    /**
     * @var InvoiceControllerValidator|null
     */
    protected ?InvoiceControllerValidator $invoiceControllerValidator = null;

    /**
     * @return InvoiceControllerValidator
     */
    protected function createInvoiceControllerValidator(): InvoiceControllerValidator
    {
        return $this->invoiceControllerValidator ?: InvoiceControllerValidator::new();
    }

    /**
     * @param InvoiceControllerValidator $invoiceControllerValidator
     * @return static
     * @internal
     */
    public function setInvoiceControllerValidator(InvoiceControllerValidator $invoiceControllerValidator): static
    {
        $this->invoiceControllerValidator = $invoiceControllerValidator;
        return $this;
    }
}
