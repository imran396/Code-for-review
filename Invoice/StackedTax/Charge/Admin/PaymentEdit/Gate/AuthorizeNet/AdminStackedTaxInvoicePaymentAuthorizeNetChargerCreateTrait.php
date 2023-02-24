<?php
/**
 * SAM-10915: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Authorize.Net invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet;

trait AdminStackedTaxInvoicePaymentAuthorizeNetChargerCreateTrait
{
    protected ?AdminStackedTaxInvoicePaymentAuthorizeNetCharger $adminStackedTaxInvoicePaymentAuthorizeNetCharger = null;

    /**
     * @return AdminStackedTaxInvoicePaymentAuthorizeNetCharger
     */
    protected function createAdminStackedTaxInvoicePaymentAuthorizeNetCharger(): AdminStackedTaxInvoicePaymentAuthorizeNetCharger
    {
        return $this->adminStackedTaxInvoicePaymentAuthorizeNetCharger ?: AdminStackedTaxInvoicePaymentAuthorizeNetCharger::new();
    }

    /**
     * @param AdminStackedTaxInvoicePaymentAuthorizeNetCharger $adminStackedTaxInvoicePaymentAuthorizeNetCharger
     * @return $this
     * @internal
     */
    public function setAdminStackedTaxInvoicePaymentAuthorizeNetCharger(AdminStackedTaxInvoicePaymentAuthorizeNetCharger $adminStackedTaxInvoicePaymentAuthorizeNetCharger): static
    {
        $this->adminStackedTaxInvoicePaymentAuthorizeNetCharger = $adminStackedTaxInvoicePaymentAuthorizeNetCharger;
        return $this;
    }
}
