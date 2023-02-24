<?php
/**
 * SAM-9454: Refactor Invoice Line item editor for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 11, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\LineItem\Edit\Save;

use InvoiceLineItem;
use InvoiceLineItemLotCat;
use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceLineItemProductionResult
 * @package Sam\Invoice\Common\LineItem\Edit\Save
 */
class InvoiceLineItemProductionResult extends CustomizableClass
{
    /** @var InvoiceLineItem */
    public InvoiceLineItem $invoiceLineItem;

    /** @var InvoiceLineItemLotCat[] */
    public array $invoiceLineItemLotCats;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param InvoiceLineItem $invoiceLineItem
     * @param InvoiceLineItemLotCat[] $invoiceLineItemLotCats
     * @return $this
     */
    public function construct(InvoiceLineItem $invoiceLineItem, array $invoiceLineItemLotCats): static
    {
        $this->invoiceLineItem = $invoiceLineItem;
        $this->invoiceLineItemLotCats = $invoiceLineItemLotCats;
        return $this;
    }
}
