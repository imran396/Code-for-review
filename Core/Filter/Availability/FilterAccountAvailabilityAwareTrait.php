<?php
/**
 * Account Entity availability filtering definition logic. It is intended for usage in Entity Loaders and Existence Checkers
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

use Sam\Core\Data\TypeCast\ArrayCast;

/**
 * Trait FilterAccountAvailabilityAwareTrait
 * @package Sam\Core\Filter\Availability
 */
trait FilterAccountAvailabilityAwareTrait
{
    /**
     * Filter results by these statuses
     * @var bool[]|null
     */
    private ?array $filterAccountActive = null;

    /**
     * Define filtering by user statuses
     * @param bool|bool[] $active
     * @return static
     */
    public function filterAccountActive(bool|array $active): static
    {
        $this->filterAccountActive = ArrayCast::makeBoolArray($active);
        return $this;
    }

    /**
     * Drop any filtering, so we get un-conditional loading
     * @return static
     */
    protected function clearFilterAccount(): static
    {
        $this->dropFilterAccountActive();
        return $this;
    }

    /**
     * Drop filtering by u.user_status_id
     * @return static
     */
    protected function dropFilterAccountActive(): static
    {
        $this->filterAccountActive = null;
        return $this;
    }

    /**
     * @return bool[]|null
     */
    protected function getFilterAccountActive(): ?array
    {
        return $this->filterAccountActive;
    }

    /**
     * @return bool
     */
    protected function hasFilterAccountActive(): bool
    {
        return $this->filterAccountActive !== null;
    }
}
