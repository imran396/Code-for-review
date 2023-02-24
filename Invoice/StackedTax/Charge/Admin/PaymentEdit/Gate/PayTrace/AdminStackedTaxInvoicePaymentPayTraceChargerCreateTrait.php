<?php
/**
 * <Description of class>
 *
 * SAM-10918: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract PayTrace invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\PayTrace;

/**
 * Trait AdminInvoiceEditPayTraceChargerCreateTrait
 * @package
 */
trait AdminStackedTaxInvoicePaymentPayTraceChargerCreateTrait
{
    protected ?AdminStackedTaxInvoicePaymentPayTraceCharger $adminStackedTaxInvoicePaymentPayTraceCharger = null;

    /**
     * @return AdminStackedTaxInvoicePaymentPayTraceCharger
     */
    protected function createAdminStackedTaxInvoicePaymentPayTraceCharger(): AdminStackedTaxInvoicePaymentPayTraceCharger
    {
        return $this->adminStackedTaxInvoicePaymentPayTraceCharger ?: AdminStackedTaxInvoicePaymentPayTraceCharger::new();
    }

    /**
     * @param AdminStackedTaxInvoicePaymentPayTraceCharger $adminStackedTaxInvoicePaymentPayTraceCharger
     * @return $this
     * @internal
     */
    public function setAdminStackedTaxInvoicePaymentPayTraceCharger(AdminStackedTaxInvoicePaymentPayTraceCharger $adminStackedTaxInvoicePaymentPayTraceCharger): static
    {
        $this->adminStackedTaxInvoicePaymentPayTraceCharger = $adminStackedTaxInvoicePaymentPayTraceCharger;
        return $this;
    }
}
