<?php
/**
 *
 * SAM-4365: Settlement Calculator module
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/11/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Calculate;

/**
 * Trait SettlementSummaryCalculatorCreateTrait
 * @package Sam\Settlement\Calculate
 */
trait SettlementSummaryCalculatorCreateTrait
{
    protected ?SettlementSummaryCalculator $settlementSummaryCalculator = null;

    /**
     * @return SettlementSummaryCalculator
     */
    protected function createSettlementSummaryCalculator(): SettlementSummaryCalculator
    {
        return $this->settlementSummaryCalculator ?: SettlementSummaryCalculator::new();
    }

    /**
     * @param SettlementSummaryCalculator $settlementSummaryCalculator
     * @return static
     * @internal
     */
    public function setSettlementSummaryCalculator(SettlementSummaryCalculator $settlementSummaryCalculator): static
    {
        $this->settlementSummaryCalculator = $settlementSummaryCalculator;
        return $this;
    }
}
