<?php
/**
 * SAM-4440 Refactor feed management logic to \Sam\Feed namespace
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 17, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\Availability;

/**
 * Trait FilterFeedAvailabilityAwareTrait
 */
trait FilterFeedAvailabilityAwareTrait
{
    /**
     * Filter results by these statuses
     * @var bool|null
     */
    private ?bool $filterFeedActive = null;

    /**
     * Define filtering by user statuses
     * @param bool $active
     * @return static
     */
    public function filterFeedActive(bool $active): static
    {
        $this->filterFeedActive = $active;
        return $this;
    }

    /**
     * Drop any filtering, so we get un-conditional loading
     * @return static
     */
    protected function clearFilterFeed(): static
    {
        $this->dropFilterFeedActive();
        return $this;
    }

    /**
     * Drop filtering by f.active
     * @return static
     */
    protected function dropFilterFeedActive(): static
    {
        $this->filterFeedActive = null;
        return $this;
    }

    /**
     * @return bool|null
     */
    protected function getFilterFeedActive(): ?bool
    {
        return $this->filterFeedActive;
    }

    /**
     * @return bool
     */
    protected function hasFilterFeedActive(): bool
    {
        return $this->filterFeedActive !== null;
    }
}
