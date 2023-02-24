<?php
/**
 * SAM-8787: Credit card payment gateway availability checker
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Availability;

/**
 * Trait BillingGateAvailabilityCheckerCreateTrait
 * @package Sam\Billing\Gate\Availability
 */
trait BillingGateAvailabilityCheckerCreateTrait
{
    /**
     * @var BillingGateAvailabilityChecker|null
     */
    protected ?BillingGateAvailabilityChecker $billingGateAvailabilityChecker = null;

    /**
     * @return BillingGateAvailabilityChecker
     */
    protected function createBillingGateAvailabilityChecker(): BillingGateAvailabilityChecker
    {
        return $this->billingGateAvailabilityChecker ?: BillingGateAvailabilityChecker::new();
    }

    /**
     * @param BillingGateAvailabilityChecker $billingGateAvailabilityChecker
     * @return $this
     * @internal
     */
    public function setBillingGateAvailabilityChecker(BillingGateAvailabilityChecker $billingGateAvailabilityChecker): static
    {
        $this->billingGateAvailabilityChecker = $billingGateAvailabilityChecker;
        return $this;
    }
}
