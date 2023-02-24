<?php
/**
 *
 * SAM-4741: SyncNamespace loader and existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-03-06
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\Availability;

/**
 * Trait FilterSyncNamespaceAvailabilityAwareTrait
 * @package Sam\Core\Filter\Availability
 */
trait FilterSyncNamespaceAvailabilityAwareTrait
{
    /**
     * @var bool|null
     */
    private ?bool $filterSyncNamespaceActive = null;

    /**
     * @param bool $active
     * @return static
     */
    public function filterSyncNamespaceActive(bool $active): static
    {
        $this->filterSyncNamespaceActive = $active;
        return $this;
    }

    /**
     * Drop any filtering, so we get un-conditional loading
     * @return static
     */
    protected function clearFilterSyncNamespace(): static
    {
        $this->dropFilterSyncNamespaceActive();
        return $this;
    }

    /**
     * Drop filtering by `active` column
     * @return static
     */
    protected function dropFilterSyncNamespaceActive(): static
    {
        $this->filterSyncNamespaceActive = null;
        return $this;
    }

    /**
     * @return bool|null
     */
    protected function getFilterSyncNamespaceActive(): ?bool
    {
        return $this->filterSyncNamespaceActive;
    }

    /**
     * @return bool
     */
    protected function hasFilterSyncNamespaceActive(): bool
    {
        return $this->filterSyncNamespaceActive !== null;
    }
}
