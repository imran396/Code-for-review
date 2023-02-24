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

namespace Sam\Invoice\Legacy\Generate\Item;

/**
 * Trait MultipleInvoiceItemProducerAwareTrait
 * @package Sam\Invoice\Legacy\Generate\Item
 */
trait LegacyMultipleInvoiceItemProducerAwareTrait
{
    /**
     * @var LegacyMultipleInvoiceItemProducer|null
     */
    protected ?LegacyMultipleInvoiceItemProducer $legacyMultipleInvoiceItemProducer = null;

    /**
     * @return LegacyMultipleInvoiceItemProducer
     */
    protected function getLegacyMultipleInvoiceItemProducer(): LegacyMultipleInvoiceItemProducer
    {
        if ($this->legacyMultipleInvoiceItemProducer === null) {
            $this->legacyMultipleInvoiceItemProducer = LegacyMultipleInvoiceItemProducer::new();
        }
        return $this->legacyMultipleInvoiceItemProducer;
    }

    /**
     * @param LegacyMultipleInvoiceItemProducer $legacyMultipleInvoiceItemProducer
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setLegacyMultipleInvoiceItemProducer(LegacyMultipleInvoiceItemProducer $legacyMultipleInvoiceItemProducer): static
    {
        $this->legacyMultipleInvoiceItemProducer = $legacyMultipleInvoiceItemProducer;
        return $this;
    }
}
