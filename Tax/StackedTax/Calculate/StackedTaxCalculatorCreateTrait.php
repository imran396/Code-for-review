<?php
/**
 * SAM-11061: Stacked Tax. Invoice Management pages. Implement Stacked Tax Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Calculate;

/**
 * Trait StackedTaxCalculatorCreateTrait
 * @package Sam\Tax\StackedTax\Calculate
 */
trait StackedTaxCalculatorCreateTrait
{
    protected ?StackedTaxCalculator $stackedTaxCalculator = null;

    /**
     * @return StackedTaxCalculator
     */
    protected function createStackedTaxCalculator(): StackedTaxCalculator
    {
        return $this->stackedTaxCalculator ?: StackedTaxCalculator::new();
    }

    /**
     * @param StackedTaxCalculator $stackedTaxCalculator
     * @return $this
     * @internal
     */
    public function setStackedTaxCalculator(StackedTaxCalculator $stackedTaxCalculator): static
    {
        $this->stackedTaxCalculator = $stackedTaxCalculator;
        return $this;
    }
}
