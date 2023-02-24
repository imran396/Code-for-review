<?php
/**
 * SAM-4364: Settlement item list loading optimization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\Settlement\Calculate;

/**
 * Trait SettlementPureCalculatorCreateTrait
 * @package Sam\Core\Entity\Model\Settlement\Calculate
 */
trait SettlementPureCalculatorCreateTrait
{
    protected ?SettlementPureCalculator $settlementPureCalculator = null;

    /**
     * @return SettlementPureCalculator
     */
    protected function createSettlementPureCalculator(): SettlementPureCalculator
    {
        return $this->settlementPureCalculator ?: SettlementPureCalculator::new();
    }

    /**
     * @param SettlementPureCalculator $settlementPureCalculator
     * @return static
     * @internal
     */
    public function setSettlementPureCalculator(SettlementPureCalculator $settlementPureCalculator): static
    {
        $this->settlementPureCalculator = $settlementPureCalculator;
        return $this;
    }
}
