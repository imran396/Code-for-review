<?php
/**
 * SAM-4377: Invoice producer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           04.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Generate\Item\Single;

trait LegacySingleInvoiceItemProducerAwareTrait
{
    /**
     * @var LegacySingleInvoiceItemProducer|null
     */
    protected ?LegacySingleInvoiceItemProducer $legacySingleInvoiceItemProducer = null;

    /**
     * @return LegacySingleInvoiceItemProducer
     */
    protected function getLegacySingleInvoiceItemProducer(): LegacySingleInvoiceItemProducer
    {
        if ($this->legacySingleInvoiceItemProducer === null) {
            $this->legacySingleInvoiceItemProducer = LegacySingleInvoiceItemProducer::new();
        }
        return $this->legacySingleInvoiceItemProducer;
    }

    /**
     * @param LegacySingleInvoiceItemProducer $legacySingleInvoiceItemProducer
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setLegacySingleInvoiceItemProducer(LegacySingleInvoiceItemProducer $legacySingleInvoiceItemProducer): static
    {
        $this->legacySingleInvoiceItemProducer = $legacySingleInvoiceItemProducer;
        return $this;
    }
}
