<?php
/**
 * SAM-11338: Stacked Tax. Public page Invoice with CC surcharge and Service tax on surcharge
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 05, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\Internal\Payment\Amount;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Payment\InvoiceAdditional\Calculate\PaymentInvoiceAdditionalCalculationResult;

/**
 * Class InvoiceChargeAmountDetails
 * @package Sam\Invoice\Common\Charge\Responsive\Gate\Internal\Payment\Amoun
 */
class InvoiceChargeAmountDetails extends CustomizableClass
{
    public readonly float $paymentAmount;
    public readonly ?PaymentInvoiceAdditionalCalculationResult $surcharge;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(float $paymentAmount, ?PaymentInvoiceAdditionalCalculationResult $surcharge): static
    {
        $this->paymentAmount = $paymentAmount;
        $this->surcharge = $surcharge;
        return $this;
    }
}
