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

namespace Sam\Settlement\Payment\Delete;

/**
 * Trait SettlementPaymentDeleterCreateTrait
 * @package Sam\Settlement\Payment\Delete
 */
trait SettlementPaymentDeleterCreateTrait
{
    protected ?SettlementPaymentDeleter $settlementPaymentDeleter = null;

    /**
     * @return SettlementPaymentDeleter
     */
    protected function createSettlementPaymentDeleter(): SettlementPaymentDeleter
    {
        return $this->settlementPaymentDeleter ?: SettlementPaymentDeleter::new();
    }

    /**
     * @param SettlementPaymentDeleter $settlementPaymentDeleter
     * @return static
     * @internal
     */
    public function setSettlementPaymentDeleter(SettlementPaymentDeleter $settlementPaymentDeleter): static
    {
        $this->settlementPaymentDeleter = $settlementPaymentDeleter;
        return $this;
    }
}
