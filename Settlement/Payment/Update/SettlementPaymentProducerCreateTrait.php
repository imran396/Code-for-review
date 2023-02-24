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

/**
 * Trait SettlementPaymentProducerCreateTrait
 * @package Sam\Settlement\Payment\Update
 */
trait SettlementPaymentProducerCreateTrait
{
    protected ?SettlementPaymentProducer $settlementPaymentProducer = null;

    /**
     * @return SettlementPaymentProducer
     */
    protected function createSettlementPaymentProducer(): SettlementPaymentProducer
    {
        return $this->settlementPaymentProducer ?: SettlementPaymentProducer::new();
    }

    /**
     * @param SettlementPaymentProducer $settlementPaymentProducer
     * @return static
     * @internal
     */
    public function setSettlementPaymentProducer(SettlementPaymentProducer $settlementPaymentProducer): static
    {
        $this->settlementPaymentProducer = $settlementPaymentProducer;
        return $this;
    }
}
