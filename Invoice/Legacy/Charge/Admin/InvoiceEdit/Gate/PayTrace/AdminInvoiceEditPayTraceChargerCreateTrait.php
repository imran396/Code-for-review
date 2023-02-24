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

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\PayTrace;

/**
 * Trait AdminInvoiceEditPayTraceChargerCreateTrait
 * @package
 */
trait AdminInvoiceEditPayTraceChargerCreateTrait
{
    protected ?AdminInvoiceEditPayTraceCharger $adminInvoiceEditPayTraceCharger = null;

    /**
     * @return AdminInvoiceEditPayTraceCharger
     */
    protected function createAdminInvoiceEditPayTraceCharger(): AdminInvoiceEditPayTraceCharger
    {
        return $this->adminInvoiceEditPayTraceCharger ?: AdminInvoiceEditPayTraceCharger::new();
    }

    /**
     * @param AdminInvoiceEditPayTraceCharger $adminInvoiceEditPayTraceCharger
     * @return $this
     * @internal
     */
    public function setAdminInvoiceEditPayTraceCharger(AdminInvoiceEditPayTraceCharger $adminInvoiceEditPayTraceCharger): static
    {
        $this->adminInvoiceEditPayTraceCharger = $adminInvoiceEditPayTraceCharger;
        return $this;
    }
}
