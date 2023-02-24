<?php
/**
 * SAM-11525: Stacked Tax. Actions at the Admin Invoice List page. Extract general validation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\InvoiceList\Validate;

/**
 * Trait AdminStackedTaxInvoiceListChargingValidatorCreateTrait
 * @package Sam\Invoice\StackedTax\Charge\Admin\InvoiceList\Validate
 */
trait AdminStackedTaxInvoiceListChargingValidatorCreateTrait
{
    protected ?AdminStackedTaxInvoiceListChargingValidator $adminStackedTaxInvoiceListChargingValidator = null;

    /**
     * @return AdminStackedTaxInvoiceListChargingValidator
     */
    protected function createAdminStackedTaxInvoiceListChargingValidator(): AdminStackedTaxInvoiceListChargingValidator
    {
        return $this->adminStackedTaxInvoiceListChargingValidator ?: AdminStackedTaxInvoiceListChargingValidator::new();
    }

    /**
     * @param AdminStackedTaxInvoiceListChargingValidator $adminStackedTaxInvoiceListChargingValidator
     * @return $this
     * @internal
     */
    public function setAdminStackedTaxInvoiceListChargingValidator(AdminStackedTaxInvoiceListChargingValidator $adminStackedTaxInvoiceListChargingValidator): static
    {
        $this->adminStackedTaxInvoiceListChargingValidator = $adminStackedTaxInvoiceListChargingValidator;
        return $this;
    }
}
