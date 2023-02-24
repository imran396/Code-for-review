<?php
/**
 * SAM-11097: Stacked Tax. Multiple Invoice Printing view
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\View\InvoicePrint\Multiple;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\View\InvoicePrint\Single\SingleStackedTaxInvoicePrintViewRendererCreateTrait;

/**
 * Class MultipleStackedTaxInvoicePrintViewRenderer
 * @package Sam\Invoice\StackedTax\View\InvoicePrint\Multiple
 */
class MultipleStackedTaxInvoicePrintViewRenderer extends CustomizableClass
{
    use SingleStackedTaxInvoicePrintViewRendererCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function render(array $invoiceIds, int $languageId): string
    {
        $renderer = $this->createSingleStackedTaxInvoicePrintViewRenderer();
        return array_reduce($invoiceIds, fn(string $output, int $invoiceId) => $output . $renderer->render($invoiceId, $languageId), '');
    }
}
