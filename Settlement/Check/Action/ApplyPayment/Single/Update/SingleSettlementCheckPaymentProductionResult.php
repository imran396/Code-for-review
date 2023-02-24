<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 05, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\ApplyPayment\Single\Update;

use Payment;
use Sam\Core\Service\CustomizableClass;
use SettlementCheck;

/**
 * Class SingleSettlementCheckPaymentProductionResult
 * @package Sam\Settlement\Check\Action\ApplyPayment\Single\Update
 */
class SingleSettlementCheckPaymentProductionResult extends CustomizableClass
{
    public readonly SettlementCheck $settlementCheck;
    public readonly Payment $payment;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param SettlementCheck $settlementCheck
     * @param Payment $payment
     * @return $this
     */
    public function construct(SettlementCheck $settlementCheck, Payment $payment): static
    {
        $this->settlementCheck = $settlementCheck;
        $this->payment = $payment;
        return $this;
    }

    public function toArray(): array
    {
        return [$this->settlementCheck, $this->payment];
    }

}
