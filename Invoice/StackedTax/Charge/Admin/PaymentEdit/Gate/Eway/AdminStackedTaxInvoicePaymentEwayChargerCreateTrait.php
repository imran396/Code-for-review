<?php
/**
 * SAM-10912: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Eway invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Eway;

/**
 * Trait InvoiceEditEwayChargerCreateTrait
 * @package
 */
trait AdminStackedTaxInvoicePaymentEwayChargerCreateTrait
{
    protected ?AdminStackedTaxInvoicePaymentEwayCharger $adminStackedTaxInvoicePaymentEwayCharger = null;

    /**
     * @return AdminStackedTaxInvoicePaymentEwayCharger
     */
    protected function createAdminStackedTaxInvoicePaymentEwayCharger(): AdminStackedTaxInvoicePaymentEwayCharger
    {
        return $this->adminStackedTaxInvoicePaymentEwayCharger ?: AdminStackedTaxInvoicePaymentEwayCharger::new();
    }

    /**
     * @param AdminStackedTaxInvoicePaymentEwayCharger $adminStackedTaxInvoicePaymentEwayCharger
     * @return $this
     * @internal
     */
    public function setAdminStackedTaxInvoicePaymentEwayCharger(AdminStackedTaxInvoicePaymentEwayCharger $adminStackedTaxInvoicePaymentEwayCharger): static
    {
        $this->adminStackedTaxInvoicePaymentEwayCharger = $adminStackedTaxInvoicePaymentEwayCharger;
        return $this;
    }
}
