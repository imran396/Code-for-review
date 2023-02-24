<?php
/**
 * SAM-10967: Stacked Tax. Public My Invoice pages. Extract Opayo invoice charging.
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 20, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\Common;


trait ResponsiveInvoiceViewChargingHelperCreateTrait
{
    protected ?ResponsiveInvoiceViewChargingHelper $responsiveInvoiceViewChargingHelper = null;

    /**
     * @return ResponsiveInvoiceViewChargingHelper
     */
    protected function createResponsiveInvoiceViewChargingHelper(): ResponsiveInvoiceViewChargingHelper
    {
        return $this->responsiveInvoiceViewChargingHelper ?: ResponsiveInvoiceViewChargingHelper::new();
    }

    /**
     * @param ResponsiveInvoiceViewChargingHelper $responsiveInvoiceViewChargingHelper
     * @return $this
     * @internal
     */
    public function setResponsiveInvoiceViewChargingHelper(ResponsiveInvoiceViewChargingHelper $responsiveInvoiceViewChargingHelper): static
    {
        $this->responsiveInvoiceViewChargingHelper = $responsiveInvoiceViewChargingHelper;
        return $this;
    }
}
