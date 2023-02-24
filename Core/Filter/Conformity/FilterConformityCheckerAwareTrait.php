<?php
/**
 * Trait for FilterConformityChecker
 *
 * SAM-4922: Entity Loader and Existence Checker approach integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 28, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\Conformity;

/**
 * Trait FilterConformityCheckerAwareTrait
 * @package Sam\Core\Filter\Conformity
 */
trait FilterConformityCheckerAwareTrait
{
    /**
     * @var FilterConformityChecker|null
     */
    protected ?FilterConformityChecker $filterConformityChecker = null;

    /**
     * @return FilterConformityChecker
     */
    protected function getFilterConformityChecker(): FilterConformityChecker
    {
        if ($this->filterConformityChecker === null) {
            $this->filterConformityChecker = FilterConformityChecker::new();
        }
        return $this->filterConformityChecker;
    }

    /**
     * @param FilterConformityChecker $filterConformityChecker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setFilterConformityChecker(FilterConformityChecker $filterConformityChecker): static
    {
        $this->filterConformityChecker = $filterConformityChecker;
        return $this;
    }
}
