<?php
/**
 * SAM-5151: Invoice generation and reverse proxy timeout improvements
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/26/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\StackedTaxInvoice\Validate;

trait StackedTaxInvoiceControllerValidatorCreateTrait
{
    /**
     * @var StackedTaxInvoiceControllerValidator|null
     */
    protected ?StackedTaxInvoiceControllerValidator $stackedTaxInvoiceControllerValidator = null;

    /**
     * @return StackedTaxInvoiceControllerValidator
     */
    protected function createStackedTaxInvoiceControllerValidator(): StackedTaxInvoiceControllerValidator
    {
        return $this->stackedTaxInvoiceControllerValidator ?: StackedTaxInvoiceControllerValidator::new();
    }

    /**
     * @param StackedTaxInvoiceControllerValidator $invoiceControllerValidator
     * @return static
     * @internal
     */
    public function setStackedTaxInvoiceControllerValidator(StackedTaxInvoiceControllerValidator $invoiceControllerValidator): static
    {
        $this->stackedTaxInvoiceControllerValidator = $invoiceControllerValidator;
        return $this;
    }
}
