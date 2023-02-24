<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Produce\Internal\Calculate;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManager;
use Sam\Invoice\Common\Calculate\Basic\AnyInvoiceCalculator;
use Sam\Invoice\Common\Charge\Common\Total\InvoiceTotalsUpdater;


class Calculator extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function calculateBalanceDue(int $invoiceId, bool $isReadOnlyDb = false): float
    {
        return AnyInvoiceCalculator::new()->calcRoundedBalanceDueByInvoiceId($invoiceId, $isReadOnlyDb);
    }

    public function calculateCcSurcharge(?int $creditCardId, float $amount, int $accountId): array
    {
        return InvoiceAdditionalChargeManager::new()->buildCcSurcharge($creditCardId, $amount, $accountId);
    }

    public function calculateAndAssign(Invoice $invoice, $isReadOnlyDb = false): Invoice
    {
        return InvoiceTotalsUpdater::new()->calcAndAssign($invoice, $isReadOnlyDb);
    }
}
