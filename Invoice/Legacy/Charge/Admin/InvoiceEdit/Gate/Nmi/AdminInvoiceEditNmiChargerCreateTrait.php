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


namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Nmi;

/**
 * Trait AdminInvoiceEditNmiChargerCreateTrait
 * @package namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Nmi;
 */
trait AdminInvoiceEditNmiChargerCreateTrait
{
    protected ?AdminInvoiceEditNmiCharger $adminInvoiceEditNmiCharger = null;

    /**
     * @return AdminInvoiceEditNmiCharger
     */
    protected function createAdminInvoiceEditNmiCharger(): AdminInvoiceEditNmiCharger
    {
        return $this->adminInvoiceEditNmiCharger ?: AdminInvoiceEditNmiCharger::new();
    }

    /**
     * @param AdminInvoiceEditNmiCharger $adminInvoiceEditNmiCharger
     * @return $this
     * @internal
     */
    public function setAdminInvoiceEditNmiCharger(AdminInvoiceEditNmiCharger $adminInvoiceEditNmiCharger): static
    {
        $this->adminInvoiceEditNmiCharger = $adminInvoiceEditNmiCharger;
        return $this;
    }
}
