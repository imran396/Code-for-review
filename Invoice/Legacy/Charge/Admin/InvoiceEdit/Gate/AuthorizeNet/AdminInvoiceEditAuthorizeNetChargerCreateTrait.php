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

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\AuthorizeNet;

trait AdminInvoiceEditAuthorizeNetChargerCreateTrait
{
    protected ?AdminInvoiceEditAuthorizeNetCharger $adminInvoiceEditAuthorizeNetCharger = null;

    /**
     * @return AdminInvoiceEditAuthorizeNetCharger
     */
    protected function createAdminInvoiceEditAuthorizeNetCharger(): AdminInvoiceEditAuthorizeNetCharger
    {
        return $this->adminInvoiceEditAuthorizeNetCharger ?: AdminInvoiceEditAuthorizeNetCharger::new();
    }

    /**
     * @param AdminInvoiceEditAuthorizeNetCharger $adminInvoiceEditAuthorizeNetCharger
     * @return $this
     * @internal
     */
    public function setAdminInvoiceEditAuthorizeNetCharger(AdminInvoiceEditAuthorizeNetCharger $adminInvoiceEditAuthorizeNetCharger): static
    {
        $this->adminInvoiceEditAuthorizeNetCharger = $adminInvoiceEditAuthorizeNetCharger;
        return $this;
    }
}
