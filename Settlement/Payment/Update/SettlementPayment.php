<?php
/**
 * SAM-9960: Check Printing for Settlements: Payment List management at the "Settlement Edit" page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Payment\Update;

use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementPayment
 * @package Sam\Settlement\Payment\Update
 */
class SettlementPayment extends CustomizableClass
{
    public readonly ?int $paymentId;
    public readonly int $settlementId;
    public readonly ?int $paymentMethodId;
    public readonly ?float $amount;
    public readonly string $note;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(?int $paymentId, int $settlementId, ?int $paymentMethodId, ?float $amount, string $note = ''): static
    {
        $this->paymentId = $paymentId;
        $this->settlementId = $settlementId;
        $this->paymentMethodId = $paymentMethodId;
        $this->amount = $amount;
        $this->note = trim($note);
        return $this;
    }

    public function constructNew(int $settlementId, ?int $paymentMethodId, ?float $amount, string $note = ''): static
    {
        return $this->construct(null, $settlementId, $paymentMethodId, $amount, $note);
    }

    public function constructExisting(int $paymentId, int $settlementId, ?int $paymentMethodId, ?float $amount, string $note = ''): static
    {
        return $this->construct($paymentId, $settlementId, $paymentMethodId, $amount, $note);
    }
}
