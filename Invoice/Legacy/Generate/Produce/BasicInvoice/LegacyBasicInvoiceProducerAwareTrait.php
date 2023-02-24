<?php
/**
 * SAM-4377: Invoice producer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           25.12.2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Generate\Produce\BasicInvoice;

trait LegacyBasicInvoiceProducerAwareTrait
{
    /**
     * @var LegacyBasicInvoiceProducer|null
     */
    protected ?LegacyBasicInvoiceProducer $legacyBasicInvoiceProducer = null;

    /**
     * @return LegacyBasicInvoiceProducer
     */
    protected function getLegacyBasicInvoiceProducer(): LegacyBasicInvoiceProducer
    {
        if ($this->legacyBasicInvoiceProducer === null) {
            $this->legacyBasicInvoiceProducer = LegacyBasicInvoiceProducer::new();
        }
        return $this->legacyBasicInvoiceProducer;
    }

    /**
     * @param LegacyBasicInvoiceProducer $legacyBasicInvoiceProducer
     * @return static
     * @internal
     */
    public function setLegacyBasicInvoiceProducer(LegacyBasicInvoiceProducer $legacyBasicInvoiceProducer): static
    {
        $this->legacyBasicInvoiceProducer = $legacyBasicInvoiceProducer;
        return $this;
    }
}
