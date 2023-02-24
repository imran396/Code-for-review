<?php
/**
 * SAM-10971: Stacked Tax. Public My Invoice pages. Extract Authorize.net invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\AuthorizeNet\Charge;

trait ResponsiveInvoiceViewAuthorizeNetChargerCreateTrait
{
    protected ?ResponsiveInvoiceViewAuthorizeNetCharger $responsiveInvoiceViewAuthorizeNetCharger = null;

    /**
     * @return ResponsiveInvoiceViewAuthorizeNetCharger
     */
    protected function createResponsiveInvoiceViewAuthorizeNetCharger(): ResponsiveInvoiceViewAuthorizeNetCharger
    {
        return $this->responsiveInvoiceViewAuthorizeNetCharger ?: ResponsiveInvoiceViewAuthorizeNetCharger::new();
    }

    /**
     * @param ResponsiveInvoiceViewAuthorizeNetCharger $responsiveInvoiceViewAuthorizeNetCharger
     * @return $this
     * @internal
     */
    public function setResponsiveInvoiceViewAuthorizeNetCharger(ResponsiveInvoiceViewAuthorizeNetCharger $responsiveInvoiceViewAuthorizeNetCharger): static
    {
        $this->responsiveInvoiceViewAuthorizeNetCharger = $responsiveInvoiceViewAuthorizeNetCharger;
        return $this;
    }
}
