<?php
/**
 * SAM-4364: Settlement item list loading optimization
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base\SettlementItemList\Internal;

/**
 * Trait SettlementItemTaxCalculatorCreateTrait
 * @package Sam\View\Base\SettlementItemList\Internal
 * @internal
 */
trait SettlementItemTaxCalculatorCreateTrait
{
    protected ?SettlementItemTaxCalculator $settlementItemTaxCalculator = null;

    /**
     * @return SettlementItemTaxCalculator
     */
    protected function createSettlementItemTaxCalculator(): SettlementItemTaxCalculator
    {
        return $this->settlementItemTaxCalculator ?: SettlementItemTaxCalculator::new();
    }

    /**
     * @param SettlementItemTaxCalculator $settlementItemTaxCalculator
     * @return static
     * @internal
     */
    public function setSettlementItemTaxCalculator(SettlementItemTaxCalculator $settlementItemTaxCalculator): static
    {
        $this->settlementItemTaxCalculator = $settlementItemTaxCalculator;
        return $this;
    }
}
