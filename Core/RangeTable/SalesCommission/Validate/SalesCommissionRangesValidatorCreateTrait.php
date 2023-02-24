<?php
/**
 * SAM-8106: Improper validations displayed for invalid inputs
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\RangeTable\SalesCommission\Validate;

/**
 * Trait SalesCommissionRangesValidatorCreateTrait
 * @package Sam\Core\RangeTable\SalesCommission\Validate
 */
trait SalesCommissionRangesValidatorCreateTrait
{
    /**
     * @var SalesCommissionRangesValidator|null
     */
    protected ?SalesCommissionRangesValidator $salesCommissionRangesValidator = null;

    /**
     * @return SalesCommissionRangesValidator
     */
    protected function createSalesCommissionRangesValidator(): SalesCommissionRangesValidator
    {
        return $this->salesCommissionRangesValidator ?: SalesCommissionRangesValidator::new();
    }

    /**
     * @param SalesCommissionRangesValidator $salesCommissionRangesValidator
     * @return $this
     * @internal
     */
    public function setSalesCommissionRangesValidator(SalesCommissionRangesValidator $salesCommissionRangesValidator): static
    {
        $this->salesCommissionRangesValidator = $salesCommissionRangesValidator;
        return $this;
    }
}
