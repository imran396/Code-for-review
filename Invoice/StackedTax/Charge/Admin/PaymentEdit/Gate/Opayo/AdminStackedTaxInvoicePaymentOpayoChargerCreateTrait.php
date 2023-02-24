<?php
/**
 * Stacked Tax. Admin - Add to Stacked Tax Payment page (Invoice) the functionality from Pay Invoice page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Opayo;

trait AdminStackedTaxInvoicePaymentOpayoChargerCreateTrait
{
    protected ?AdminStackedTaxInvoicePaymentOpayoCharger $adminStackedTaxInvoicePaymentOpayoCharger = null;

    /**
     * @return AdminStackedTaxInvoicePaymentOpayoCharger
     */
    protected function createAdminStackedTaxInvoicePaymentOpayoCharger(): AdminStackedTaxInvoicePaymentOpayoCharger
    {
        return $this->adminStackedTaxInvoicePaymentOpayoCharger ?: AdminStackedTaxInvoicePaymentOpayoCharger::new();
    }

    /**
     * @param AdminStackedTaxInvoicePaymentOpayoCharger $adminStackedTaxInvoicePaymentOpayoCharger
     * @return $this
     * @internal
     */
    public function setAdminStackedTaxInvoicePaymentOpayoCharger(AdminStackedTaxInvoicePaymentOpayoCharger $adminStackedTaxInvoicePaymentOpayoCharger): static
    {
        $this->adminStackedTaxInvoicePaymentOpayoCharger = $adminStackedTaxInvoicePaymentOpayoCharger;
        return $this;
    }
}
