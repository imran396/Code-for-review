<?php
/**
 * SAM-4111: Invoice and settlement fields renderers
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Invoice\Render;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class InvoicePureRenderer
 * @package Sam\Core\Invoice\Render
 */
class InvoicePureRenderer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $invoiceNo
     * @return string
     */
    public function makeInvoiceNo(?int $invoiceNo): string
    {
        return (string)$invoiceNo;
    }

    /**
     * @param int $invoiceStatus
     * @return string
     */
    public function makeInvoiceStatus(int $invoiceStatus): string
    {
        return Constants\Invoice::$invoiceStatusNames[$invoiceStatus];
    }

    /**
     * Render payment status based on invoice status
     * @param int $invoiceStatus
     * @return string
     */
    public function makePaymentStatus(int $invoiceStatus): string
    {
        return Constants\Invoice::$invoiceStatusNames[$invoiceStatus] ?? '';
    }

    /**
     * @param string $breakDown
     * @return string
     */
    public function makeBreakDown(string $breakDown): string
    {
        return Constants\Invoice::$breakDowns[$breakDown] ?? '';
    }
}
