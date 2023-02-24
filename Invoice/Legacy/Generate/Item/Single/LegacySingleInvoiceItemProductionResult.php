<?php
/**
 * SAM-9721: Refactor and implement unit test for single invoice producer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Generate\Item\Single;

use InvoiceAdditional;
use InvoiceItem;
use Sam\Core\Service\CustomizableClass;

class LegacySingleInvoiceItemProductionResult extends CustomizableClass
{
    /** @var InvoiceItem */
    public InvoiceItem $invoiceItem;
    /** @var InvoiceAdditional|null */
    public ?InvoiceAdditional $invoiceAdditional;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(InvoiceItem $invoiceItem, ?InvoiceAdditional $invoiceAdditional): static
    {
        $this->invoiceItem = $invoiceItem;
        $this->invoiceAdditional = $invoiceAdditional;
        return $this;
    }
}
