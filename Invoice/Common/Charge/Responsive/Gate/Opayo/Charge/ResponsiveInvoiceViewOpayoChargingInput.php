<?php
/**
 * SAM-10967: Stacked Tax. Public My Invoice pages. Extract Opayo invoice charging.
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 20, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Responsive\Gate\Opayo\Charge;

use Invoice;
use Sam\Core\Service\CustomizableClass;

class ResponsiveInvoiceViewOpayoChargingInput extends CustomizableClass
{
    public readonly int $editorUserId;
    public readonly Invoice $invoice;
    public readonly float $amount;
    public readonly string $ccCode;
    public readonly string $paymentUrl;
    public readonly string $sessionId;
    public readonly bool $wasCcEdited;
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
        string $ccCode,
        string $paymentUrl,
        string $sessionId,
        bool $wasCcEdited,
        array $billingParams,
        bool $isReadOnlyDb = false,
    ): static {
        $this->editorUserId = $editorUserId;
        $this->invoice = $invoice;
        $this->amount = $amount;
        $this->ccCode = $ccCode;
        $this->paymentUrl = $paymentUrl;
        $this->sessionId = $sessionId;
        $this->wasCcEdited = $wasCcEdited;
        $this->billingParams = $billingParams;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }
}
