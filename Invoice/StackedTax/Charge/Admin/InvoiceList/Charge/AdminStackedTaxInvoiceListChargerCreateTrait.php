<?php
/**
 * SAM-10909: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. General adjustments
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\InvoiceList\Charge;

/**
 * Trait InvoiceChargerForAllCreateTrait
 * @package Sam\Invoice\StackedTax\Charge\Admin\InvoiceList
 */
trait AdminStackedTaxInvoiceListChargerCreateTrait
{
    protected ?AdminStackedTaxInvoiceListCharger $adminStackedTaxInvoiceListCharger = null;

    /**
     * @return AdminStackedTaxInvoiceListCharger
     */
    protected function createAdminStackedTaxInvoiceListCharger(): AdminStackedTaxInvoiceListCharger
    {
        return $this->adminStackedTaxInvoiceListCharger ?: AdminStackedTaxInvoiceListCharger::new();
    }

    /**
     * @param AdminStackedTaxInvoiceListCharger $adminStackedTaxInvoiceListCharger
     * @return static
     * @internal
     */
    public function setAdminStackedTaxInvoiceListCharger(AdminStackedTaxInvoiceListCharger $adminStackedTaxInvoiceListCharger): static
    {
        $this->adminStackedTaxInvoiceListCharger = $adminStackedTaxInvoiceListCharger;
        return $this;
    }
}
