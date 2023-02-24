<?php
/**
 *
 * SAM-4556: Apply \Sam\Invoice\Legacy\PackingSlip namespace
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/7/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\PackingSlip;

trait LegacyInvoicePackingSlipFactoryAwareTrait
{
    /**
     * @var LegacyInvoicePackingSlipFactory|null
     */
    protected ?LegacyInvoicePackingSlipFactory $legacyInvoicePackingSlipFactory = null;

    /**
     * @return LegacyInvoicePackingSlipFactory
     */
    protected function getLegacyInvoicePackingSlipFactory(): LegacyInvoicePackingSlipFactory
    {
        if ($this->legacyInvoicePackingSlipFactory === null) {
            $this->legacyInvoicePackingSlipFactory = LegacyInvoicePackingSlipFactory::new();
        }
        return $this->legacyInvoicePackingSlipFactory;
    }

    /**
     * @param LegacyInvoicePackingSlipFactory $legacyInvoicePackingSlipFactory
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLegacyInvoicePackingSlipFactory(LegacyInvoicePackingSlipFactory $legacyInvoicePackingSlipFactory): static
    {
        $this->legacyInvoicePackingSlipFactory = $legacyInvoicePackingSlipFactory;
        return $this;
    }
}
