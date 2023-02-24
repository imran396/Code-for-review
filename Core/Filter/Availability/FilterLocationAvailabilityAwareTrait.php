<?php
/**
 * Location Entity availability filtering definition logic. It is intended for usage in Entity Loaders and Existence Checkers
 *
 * SAM-4922: Entity Loader and Existence Checker approach integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           02/28/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\Availability;

/**
 * Trait FilterLocationAvailabilityAwareTrait
 * @package Sam\Core\Filter\Availability
 */
trait FilterLocationAvailabilityAwareTrait
{
    /**
     * Filter results by these statuses
     * @var bool|null - null if not initialized at \Sam\Core\Filter\EntityLoader\LocationAllFilterTrait::initFilter
     */
    private ?bool $filterLocationActive = null;

    /**
     * Define filtering by user statuses
     * @param bool $active
     * @return static
     */
    public function filterLocationActive(bool $active): static
    {
        $this->filterLocationActive = $active;
        return $this;
    }

    /**
     * Drop any filtering, so we get un-conditional loading
     * @return static
     */
    protected function clearFilterLocation(): static
    {
        $this->dropFilterLocationActive();
        return $this;
    }

    /**
     * Drop filtering by u.user_status_id
     * @return static
     */
    protected function dropFilterLocationActive(): static
    {
        $this->filterLocationActive = null;
        return $this;
    }

    /**
     * @return bool|null
     */
    protected function getFilterLocationActive(): ?bool
    {
        return $this->filterLocationActive;
    }

    /**
     * @return bool
     */
    protected function hasFilterLocationActive(): bool
    {
        return $this->filterLocationActive !== null;
    }
}
