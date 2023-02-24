<?php
/**
 * SAM-11079: Stacked Tax. Tax aggregation. Admin Invoice List page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\StackedTaxInvoiceListForm\Action\MarkPaid\Single\Validate;

/**
 * Trait SingleStackedTaxInvoiceMarkPaidValidatorCreateTrait
 * @package Sam\View\Admin\Form\StackedTaxInvoiceListForm\Action\MarkPaid\Single\Validate
 */
trait SingleStackedTaxInvoiceMarkPaidValidatorCreateTrait
{
    protected ?SingleStackedTaxInvoiceMarkPaidValidator $singleStackedTaxInvoiceMarkPaidValidator = null;

    /**
     * @return SingleStackedTaxInvoiceMarkPaidValidator
     */
    protected function createSingleStackedTaxInvoiceMarkPaidValidator(): SingleStackedTaxInvoiceMarkPaidValidator
    {
        return $this->singleStackedTaxInvoiceMarkPaidValidator ?: SingleStackedTaxInvoiceMarkPaidValidator::new();
    }

    /**
     * @param SingleStackedTaxInvoiceMarkPaidValidator $singleStackedTaxInvoiceMarkPaidValidator
     * @return $this
     * @internal
     */
    public function setSingleStackedTaxInvoiceMarkPaidValidator(SingleStackedTaxInvoiceMarkPaidValidator $singleStackedTaxInvoiceMarkPaidValidator): static
    {
        $this->singleStackedTaxInvoiceMarkPaidValidator = $singleStackedTaxInvoiceMarkPaidValidator;
        return $this;
    }
}
