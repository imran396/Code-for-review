<?php
/**
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Calculate;

/**
 * Trait SettlementCheckCalculatorCreateTrait
 * @package Sam\Settlement\Check
 */
trait SettlementCheckCalculatorCreateTrait
{
    protected ?SettlementCheckCalculator $settlementCheckCalculator = null;

    /**
     * @return SettlementCheckCalculator
     */
    protected function createSettlementCheckCalculator(): SettlementCheckCalculator
    {
        return $this->settlementCheckCalculator ?: SettlementCheckCalculator::new();
    }

    /**
     * @param SettlementCheckCalculator $settlementCheckCalculator
     * @return $this
     * @internal
     */
    public function setSettlementCheckCalculator(SettlementCheckCalculator $settlementCheckCalculator): static
    {
        $this->settlementCheckCalculator = $settlementCheckCalculator;
        return $this;
    }
}
