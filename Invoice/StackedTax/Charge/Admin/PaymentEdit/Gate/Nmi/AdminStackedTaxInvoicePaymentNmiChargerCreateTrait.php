<?php
/**
 * <Description of class>
 *
 * SAM-10919: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Nmi invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Nmi;

trait AdminStackedTaxInvoicePaymentNmiChargerCreateTrait
{
    protected ?AdminStackedTaxInvoicePaymentNmiCharger $adminStackedTaxInvoiceEditNmiCharger = null;

    /**
     * @return AdminStackedTaxInvoicePaymentNmiCharger
     */
    protected function createAdminStackedTaxInvoiceEditNmiCharger(): AdminStackedTaxInvoicePaymentNmiCharger
    {
        return $this->adminStackedTaxInvoiceEditNmiCharger ?: AdminStackedTaxInvoicePaymentNmiCharger::new();
    }

    /**
     * @param AdminStackedTaxInvoicePaymentNmiCharger $adminStackedTaxInvoiceEditNmiCharger
     * @return $this
     * @internal
     */
    public function setAdminStackedTaxInvoiceEditNmiCharger(AdminStackedTaxInvoicePaymentNmiCharger $adminStackedTaxInvoiceEditNmiCharger): static
    {
        $this->adminStackedTaxInvoiceEditNmiCharger = $adminStackedTaxInvoiceEditNmiCharger;
        return $this;
    }
}
