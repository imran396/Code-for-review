<?php
/**
 * SAM-10909: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. General adjustments
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

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Validate;

/**
 * Trait AdminInvoiceEditChargingValidatorCreateTrait
 * @package Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Validate
 */
trait AdminInvoiceEditChargingValidatorCreateTrait
{
    protected ?AdminInvoiceEditChargingValidator $adminInvoiceEditChargingValidator = null;

    /**
     * @return AdminInvoiceEditChargingValidator
     */
    protected function createAdminInvoiceEditChargingValidator(): AdminInvoiceEditChargingValidator
    {
        return $this->adminInvoiceEditChargingValidator ?: AdminInvoiceEditChargingValidator::new();
    }

    /**
     * @param AdminInvoiceEditChargingValidator $adminInvoiceEditChargingValidator
     * @return $this
     * @internal
     */
    public function setAdminInvoiceEditChargingValidator(AdminInvoiceEditChargingValidator $adminInvoiceEditChargingValidator): static
    {
        $this->adminInvoiceEditChargingValidator = $adminInvoiceEditChargingValidator;
        return $this;
    }
}
