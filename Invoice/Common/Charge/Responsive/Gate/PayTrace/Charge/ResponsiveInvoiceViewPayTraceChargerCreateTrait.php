<?php
/**
 * SAM-10975: Stacked Tax. Public My Invoice pages. Extract PayTrace invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\PayTrace\Charge;

/**
 * Trait ResponsiveInvoiceViewPayTraceChargerCreateTrait
 * @package Sam\Invoice\Common\Charge\Responsive\Gate\PayTrace\Charge
 */
trait ResponsiveInvoiceViewPayTraceChargerCreateTrait
{
    protected ?ResponsiveInvoiceViewPayTraceCharger $responsiveInvoiceViewPayTraceCharger = null;

    /**
     * @return ResponsiveInvoiceViewPayTraceCharger
     */
    protected function createResponsiveInvoiceViewPayTraceCharger(): ResponsiveInvoiceViewPayTraceCharger
    {
        return $this->responsiveInvoiceViewPayTraceCharger ?: ResponsiveInvoiceViewPayTraceCharger::new();
    }

    /**
     * @param ResponsiveInvoiceViewPayTraceCharger $responsiveInvoiceViewPayTraceCharger
     * @return $this
     * @internal
     */
    public function setResponsiveInvoiceViewPayTraceCharger(ResponsiveInvoiceViewPayTraceCharger $responsiveInvoiceViewPayTraceCharger): static
    {
        $this->responsiveInvoiceViewPayTraceCharger = $responsiveInvoiceViewPayTraceCharger;
        return $this;
    }
}
