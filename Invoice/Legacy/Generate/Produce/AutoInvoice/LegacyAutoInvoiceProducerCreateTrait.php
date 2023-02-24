<?php
/**
 * SAM-10948: Stacked Tax. Invoice Management pages. Prepare Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Generate\Produce\AutoInvoice;

/**
 * Trait AutoInvoiceProducerCreateTrait
 * @package Sam\Invoice\Legacy\Generate\Produce\AutoInvoice
 */
trait LegacyAutoInvoiceProducerCreateTrait
{
    protected ?LegacyAutoInvoiceProducer $legacyAutoInvoiceProducer = null;

    /**
     * @return LegacyAutoInvoiceProducer
     */
    protected function createLegacyAutoInvoiceProducer(): LegacyAutoInvoiceProducer
    {
        return $this->legacyAutoInvoiceProducer ?: LegacyAutoInvoiceProducer::new();
    }

    /**
     * @param LegacyAutoInvoiceProducer $legacyAutoInvoiceProducer
     * @return $this
     * @internal
     */
    public function setLegacyAutoInvoiceProducer(LegacyAutoInvoiceProducer $legacyAutoInvoiceProducer): static
    {
        $this->legacyAutoInvoiceProducer = $legacyAutoInvoiceProducer;
        return $this;
    }
}
