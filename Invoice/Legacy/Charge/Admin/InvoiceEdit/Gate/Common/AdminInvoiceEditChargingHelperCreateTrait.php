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

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Common;

/**
 * Trait InvoiceEditChargingHelperCreateTrait
 * @package Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Common
 */
trait AdminInvoiceEditChargingHelperCreateTrait
{
    protected ?AdminInvoiceEditChargingHelper $adminInvoiceEditChargingHelper = null;

    /**
     * @return AdminInvoiceEditChargingHelper
     */
    protected function createAdminInvoiceEditChargingHelper(): AdminInvoiceEditChargingHelper
    {
        return $this->adminInvoiceEditChargingHelper ?: AdminInvoiceEditChargingHelper::new();
    }

    /**
     * @param AdminInvoiceEditChargingHelper $adminInvoiceEditChargingHelper
     * @return $this
     * @internal
     */
    public function setAdminInvoiceEditChargingHelper(AdminInvoiceEditChargingHelper $adminInvoiceEditChargingHelper): static
    {
        $this->adminInvoiceEditChargingHelper = $adminInvoiceEditChargingHelper;
        return $this;
    }
}
