<?php
/**
 *
 * SAM-4365: Settlement Calculator module
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/9/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Calculate;

/**
 * Trait SettlementCalculatorAwareTrait
 * @package Sam\Settlement\Calculate
 */
trait SettlementCalculatorAwareTrait
{
    protected ?SettlementCalculator $settlementCalculator = null;

    /**
     * @return SettlementCalculator
     */
    protected function getSettlementCalculator(): SettlementCalculator
    {
        if ($this->settlementCalculator === null) {
            $this->settlementCalculator = SettlementCalculator::new();
        }
        return $this->settlementCalculator;
    }

    /**
     * @param SettlementCalculator $settlementCalculator
     * @return static
     * @internal
     */
    public function setSettlementCalculator(SettlementCalculator $settlementCalculator): static
    {
        $this->settlementCalculator = $settlementCalculator;
        return $this;
    }
}
