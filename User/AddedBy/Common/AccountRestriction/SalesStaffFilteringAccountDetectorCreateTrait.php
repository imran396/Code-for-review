<?php
/**
 * SAM-6928: Sales staff user assignment and filtering control adjustments at the "User Edit" and the "Sales Report" pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\AddedBy\Common\AccountRestriction;

/**
 * Trait SalesStaffFilteringAccountDetectorCreateTrait
 * @package Sam\User\AddedBy\Internal\Detect
 */
trait SalesStaffFilteringAccountDetectorCreateTrait
{
    protected ?SalesStaffFilteringAccountDetector $salesStaffFilteringAccountDetector = null;

    /**
     * @return SalesStaffFilteringAccountDetector
     */
    protected function createSalesStaffFilteringAccountDetector(): SalesStaffFilteringAccountDetector
    {
        return $this->salesStaffFilteringAccountDetector ?: SalesStaffFilteringAccountDetector::new();
    }

    /**
     * @param SalesStaffFilteringAccountDetector $salesStaffFilteringAccountDetector
     * @return $this
     * @internal
     */
    public function setSalesStaffFilteringAccountDetector(SalesStaffFilteringAccountDetector $salesStaffFilteringAccountDetector): static
    {
        $this->salesStaffFilteringAccountDetector = $salesStaffFilteringAccountDetector;
        return $this;
    }
}
