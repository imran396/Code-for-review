<?php
/**
 * SAM-10806: Stacked Tax. Feature configuration
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Feature;

/**
 * Trait StackedTaxFeatureAvailabilityCheckerCreateTrait
 * @package Sam\Tax\StackedTax\Feature
 */
trait StackedTaxFeatureAvailabilityCheckerCreateTrait
{
    protected ?StackedTaxFeatureAvailabilityChecker $stackedTaxFeatureAvailabilityChecker = null;

    /**
     * @return StackedTaxFeatureAvailabilityChecker
     */
    protected function createStackedTaxFeatureAvailabilityChecker(): StackedTaxFeatureAvailabilityChecker
    {
        return $this->stackedTaxFeatureAvailabilityChecker ?: StackedTaxFeatureAvailabilityChecker::new();
    }

    /**
     * @param StackedTaxFeatureAvailabilityChecker $stackedTaxFeatureAvailabilityChecker
     * @return $this
     * @internal
     */
    public function setStackedTaxFeatureAvailabilityChecker(StackedTaxFeatureAvailabilityChecker $stackedTaxFeatureAvailabilityChecker): static
    {
        $this->stackedTaxFeatureAvailabilityChecker = $stackedTaxFeatureAvailabilityChecker;
        return $this;
    }
}
