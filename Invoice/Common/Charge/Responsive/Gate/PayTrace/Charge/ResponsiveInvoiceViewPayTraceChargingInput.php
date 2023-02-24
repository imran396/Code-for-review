<?php
/**
 * SAM-10975: Stacked Tax. Public My Invoice pages. Extract PayTrace invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\PayTrace\Charge;

use Invoice;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ResponsiveInvoiceViewPayTraceChargingInput
 * @package Sam\Invoice\Common\Charge\Responsive\Gate\PayTrace\Charge
 */
class ResponsiveInvoiceViewPayTraceChargingInput extends CustomizableClass
{
    public readonly Invoice $invoice;
    public readonly int $editorUserId;
    public readonly ?int $paymentMethodId;
    public readonly float $balanceDue;
    public readonly string $ccCode;
    public readonly bool $wasCcEdited;
    public readonly array $billingParams;
    public readonly bool $isReadOnlyDb;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(
        int $editorUserId,
        Invoice $invoice,
        ?int $paymentMethodId,
        float $balanceDue,
        string $billingCcCode,
        bool $wasCcEdited,
        array $billingParams,
        bool $isReadOnlyDb
    ): static {
        $this->editorUserId = $editorUserId;
        $this->invoice = $invoice;
        $this->paymentMethodId = $paymentMethodId;
        $this->balanceDue = $balanceDue;
        $this->ccCode = $billingCcCode;
        $this->wasCcEdited = $wasCcEdited;
        $this->billingParams = $billingParams;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }
}
