<?php
/**
 * SAM-10096: Refactor auto-completer data loading end-points for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Account;

/**
 * Trait FilterAccountDetectorCreateTrait
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Account
 */
trait FilterAccountDetectorCreateTrait
{
    /**
     * @var FilterAccountDetector|null
     */
    protected ?FilterAccountDetector $filterAccountDetector = null;

    /**
     * @return FilterAccountDetector
     */
    protected function createFilterAccountDetector(): FilterAccountDetector
    {
        return $this->filterAccountDetector ?: FilterAccountDetector::new();
    }

    /**
     * @param FilterAccountDetector $filterAccountDetector
     * @return $this
     * @internal
     */
    public function setFilterAccountDetector(FilterAccountDetector $filterAccountDetector): static
    {
        $this->filterAccountDetector = $filterAccountDetector;
        return $this;
    }
}
