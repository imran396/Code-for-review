<?php
/**
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Feature;

/**
 * Trait SettlementCheckFeatureAvailabilityCheckerCreateTrait
 * @package Sam\Settlement\Check
 */
trait SettlementCheckFeatureAvailabilityCheckerCreateTrait
{
    protected ?SettlementCheckFeatureAvailabilityChecker $settlementCheckFeatureAvailabilityChecker = null;

    /**
     * @return SettlementCheckFeatureAvailabilityChecker
     */
    protected function createSettlementCheckFeatureAvailabilityChecker(): SettlementCheckFeatureAvailabilityChecker
    {
        return $this->settlementCheckFeatureAvailabilityChecker ?: SettlementCheckFeatureAvailabilityChecker::new();
    }

    /**
     * @param SettlementCheckFeatureAvailabilityChecker $settlementCheckFeatureAvailabilityChecker
     * @return $this
     * @internal
     */
    public function setSettlementCheckFeatureAvailabilityChecker(SettlementCheckFeatureAvailabilityChecker $settlementCheckFeatureAvailabilityChecker): static
    {
        $this->settlementCheckFeatureAvailabilityChecker = $settlementCheckFeatureAvailabilityChecker;
        return $this;
    }
}
