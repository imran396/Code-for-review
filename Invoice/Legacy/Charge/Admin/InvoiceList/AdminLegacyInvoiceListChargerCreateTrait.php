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

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceList;

use Sam\View\Admin\Form\InvoiceListPanel;

/**
 * Trait InvoiceChargerForAllCreateTrait
 * @package Sam\Invoice\Legacy\Charge\Admin\InvoiceList
 */
trait AdminLegacyInvoiceListChargerCreateTrait
{
    protected ?AdminLegacyInvoiceListCharger $adminLegacyInvoiceListCharger = null;

    /**
     * @return AdminLegacyInvoiceListCharger
     */
    protected function createAdminLegacyInvoiceListCharger(): AdminLegacyInvoiceListCharger
    {
        return $this->adminLegacyInvoiceListCharger ?: AdminLegacyInvoiceListCharger::new();
    }

    /**
     * @param AdminLegacyInvoiceListCharger $adminLegacyInvoiceListCharger
     * @return static
     * @internal
     */
    public function setAdminLegacyInvoiceListCharger(AdminLegacyInvoiceListCharger $adminLegacyInvoiceListCharger): static
    {
        $this->adminLegacyInvoiceListCharger = $adminLegacyInvoiceListCharger;
        return $this;
    }
}
