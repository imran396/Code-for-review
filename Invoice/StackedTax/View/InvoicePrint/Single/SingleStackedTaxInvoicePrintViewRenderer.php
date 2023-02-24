<?php
/**
 * SAM-11160: Stacked Tax. Admin/Public Single Invoice Printing View page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\View\InvoicePrint\Single;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\View\IsolatedInvoiceView\StackedTaxIsolatedInvoiceViewRendererCreateTrait;

/**
 * Class SingleStackedTaxInvoicePrintViewRenderer
 * @package Sam\Invoice\StackedTax\View\InvoicePrint\Single
 */
class SingleStackedTaxInvoicePrintViewRenderer extends CustomizableClass
{
    use StackedTaxIsolatedInvoiceViewRendererCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function render(int $invoiceId, int $languageId): string
    {
        return $this->createStackedTaxIsolatedInvoiceViewRenderer()->render($invoiceId, $languageId);
    }
}
