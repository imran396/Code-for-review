<?php
/**
 * SAM-10971: Stacked Tax. Public My Invoice pages. Extract Authorize.net invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\AuthorizeNet\Charge;

use Invoice;
use Sam\Core\Service\CustomizableClass;

class ResponsiveInvoiceViewAuthorizeNetChargingInput extends CustomizableClass
{
    public readonly int $editorUserId;
    public readonly Invoice $invoice;
    public readonly float $amount;
    public readonly string $orderDescription;
    public readonly string $ccCode;
    public readonly bool $wasCcEdited;
    public readonly int $paymentMethodId;
    public readonly array $billingParams;
    public readonly bool $isReadOnlyDb;


    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $editorUserId,
        Invoice $invoice,
        float $amount,
        string $orderDescription,
        string $ccCode,
        bool $wasCcEdited,
        int $paymentMethodId,
        array $billingParams,
        bool $isReadOnlyDb
    ): static {
        $this->editorUserId = $editorUserId;
        $this->invoice = $invoice;
        $this->amount = $amount;
        $this->orderDescription = $orderDescription;
        $this->ccCode = $ccCode;
        $this->wasCcEdited = $wasCcEdited;
        $this->paymentMethodId = $paymentMethodId;
        $this->billingParams = $billingParams;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }
}
