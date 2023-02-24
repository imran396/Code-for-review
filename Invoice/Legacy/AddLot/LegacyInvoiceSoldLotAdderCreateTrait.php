<?php
/**
 * SAM-10902: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract the "Add New Sold Lots" button action
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\AddLot;

trait LegacyInvoiceSoldLotAdderCreateTrait
{
    protected ?LegacyInvoiceSoldLotAdder $legacyInvoiceSoldLotAdder = null;

    /**
     * @return LegacyInvoiceSoldLotAdder
     */
    protected function createLegacyInvoiceSoldLotAdder(): LegacyInvoiceSoldLotAdder
    {
        return $this->legacyInvoiceSoldLotAdder ?: LegacyInvoiceSoldLotAdder::new();
    }

    /**
     * @param LegacyInvoiceSoldLotAdder $legacyInvoiceSoldLotAdder
     * @return $this
     * @internal
     */
    public function setLegacyInvoiceSoldLotAdder(LegacyInvoiceSoldLotAdder $legacyInvoiceSoldLotAdder): static
    {
        $this->legacyInvoiceSoldLotAdder = $legacyInvoiceSoldLotAdder;
        return $this;
    }
}
