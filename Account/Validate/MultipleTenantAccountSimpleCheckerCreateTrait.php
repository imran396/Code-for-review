<?php
/**
 * SAM-6657: Extract main/portal account detection logic to checker
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Validate;

/**
 * Trait MultipleTenantAccountSimpleCheckerCreateTrait
 * @package Sam\Account\Validate
 */
trait MultipleTenantAccountSimpleCheckerCreateTrait
{
    /**
     * @var MultipleTenantAccountSimpleChecker|null
     */
    protected ?MultipleTenantAccountSimpleChecker $multipleTenantAccountSimpleChecker = null;

    /**
     * @return MultipleTenantAccountSimpleChecker
     */
    protected function createMultipleTenantAccountSimpleChecker(): MultipleTenantAccountSimpleChecker
    {
        return $this->multipleTenantAccountSimpleChecker ?: MultipleTenantAccountSimpleChecker::new();
    }

    /**
     * @param MultipleTenantAccountSimpleChecker $multipleTenantAccountSimpleChecker
     * @return $this
     * @internal
     */
    public function setMultipleTenantAccountSimpleChecker(MultipleTenantAccountSimpleChecker $multipleTenantAccountSimpleChecker): static
    {
        $this->multipleTenantAccountSimpleChecker = $multipleTenantAccountSimpleChecker;
        return $this;
    }
}
