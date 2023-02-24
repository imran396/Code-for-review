<?php
/**
 * SAM-9938: Invoice paid via frontend not reflecting proper
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Common\Total;

/**
 * Trait InvoiceTotalsUpdaterCreateTrait
 * @package Sam\Invoice\Common\Charge\Common\Total
 */
trait InvoiceTotalsUpdaterCreateTrait
{
    /**
     * @var InvoiceTotalsUpdater|null
     */
    protected ?InvoiceTotalsUpdater $invoiceTotalsUpdater = null;

    /**
     * @return InvoiceTotalsUpdater
     */
    protected function createInvoiceTotalsUpdater(): InvoiceTotalsUpdater
    {
        return $this->invoiceTotalsUpdater ?: InvoiceTotalsUpdater::new();
    }

    /**
     * @param InvoiceTotalsUpdater $invoiceTotalsUpdater
     * @return static
     * @internal
     */
    public function setInvoiceTotalsUpdater(InvoiceTotalsUpdater $invoiceTotalsUpdater): static
    {
        $this->invoiceTotalsUpdater = $invoiceTotalsUpdater;
        return $this;
    }
}
