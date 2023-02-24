<?php
/**
 * SAM-10974: Stacked Tax. Public My Invoice pages. Extract NMI invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\Nmi\Charge;

/**
 * Trait ResponsiveInvoiceViewNmiChargerCreateTrait
 * @package Sam\Invoice\Common\Charge\Responsive\Gate\Nmi\Charge
 */
trait ResponsiveInvoiceViewNmiChargerCreateTrait
{
    protected ?ResponsiveInvoiceViewNmiCharger $responsiveInvoiceViewNmiCharger = null;

    /**
     * @return ResponsiveInvoiceViewNmiCharger
     */
    protected function createResponsiveInvoiceViewNmiCharger(): ResponsiveInvoiceViewNmiCharger
    {
        return $this->responsiveInvoiceViewNmiCharger ?: ResponsiveInvoiceViewNmiCharger::new();
    }

    /**
     * @param ResponsiveInvoiceViewNmiCharger $responsiveInvoiceViewNmiCharger
     * @return $this
     * @internal
     */
    public function setResponsiveInvoiceViewNmiCharger(ResponsiveInvoiceViewNmiCharger $responsiveInvoiceViewNmiCharger): static
    {
        $this->responsiveInvoiceViewNmiCharger = $responsiveInvoiceViewNmiCharger;
        return $this;
    }
}
