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

namespace Sam\Invoice\StackedTax\AddLot;

trait StackedTaxInvoiceSoldLotAdderCreateTrait
{
    protected ?StackedTaxInvoiceSoldLotAdder $stackedTaxInvoiceSoldLotAdder = null;

    /**
     * @return StackedTaxInvoiceSoldLotAdder
     */
    protected function createStackedTaxInvoiceSoldLotAdder(): StackedTaxInvoiceSoldLotAdder
    {
        return $this->stackedTaxInvoiceSoldLotAdder ?: StackedTaxInvoiceSoldLotAdder::new();
    }

    /**
     * @param StackedTaxInvoiceSoldLotAdder $stackedTaxInvoiceSoldLotAdder
     * @return $this
     * @internal
     */
    public function setStackedTaxInvoiceSoldLotAdder(StackedTaxInvoiceSoldLotAdder $stackedTaxInvoiceSoldLotAdder): static
    {
        $this->stackedTaxInvoiceSoldLotAdder = $stackedTaxInvoiceSoldLotAdder;
        return $this;
    }
}
