<?php
/**
 * SAM-10978: Stacked Tax. Public My Invoice pages. Extract Eway invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\Eway\Charge;

/**
 * Trait PublicInvoiceViewEwayChargerCreateTrait
 * @package Sam\Invoice\Common\Charge\Responsive\Gate\Eway\Charge
 */
trait ResponsiveInvoiceViewEwayChargerCreateTrait
{
    protected ?ResponsiveInvoiceViewEwayCharger $responsiveInvoiceViewEwayCharger = null;

    /**
     * @return ResponsiveInvoiceViewEwayCharger
     */
    protected function createResponsiveInvoiceViewEwayCharger(): ResponsiveInvoiceViewEwayCharger
    {
        return $this->responsiveInvoiceViewEwayCharger ?: ResponsiveInvoiceViewEwayCharger::new();
    }

    /**
     * @param ResponsiveInvoiceViewEwayCharger $responsiveInvoiceViewEwayCharger
     * @return $this
     * @internal
     */
    public function setResponsiveInvoiceViewEwayCharger(ResponsiveInvoiceViewEwayCharger $responsiveInvoiceViewEwayCharger): static
    {
        $this->responsiveInvoiceViewEwayCharger = $responsiveInvoiceViewEwayCharger;
        return $this;
    }
}
