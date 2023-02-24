<?php
/**
 * Timezone Entity availability filtering definition logic. It is intended for usage in Entity Loaders and Existence Checkers
 *
 * SAM-4922: Entity Loader and Existence Checker approach integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           05/31/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\Availability;

use Sam\Core\Data\TypeCast\ArrayCast;

/**
 * Trait FilterTimezoneAvailabilityAwareTrait
 * @package Sam\Core\Filter\Availability
 */
trait FilterTimezoneAvailabilityAwareTrait
{
    /**
     * Filter results by these statuses
     * @var bool[]|null
     */
    private ?array $filterTimezoneActive = null;

    /**
     * Define filtering by user statuses
     * @param bool|bool[] $active
     * @return static
     */
    public function filterTimezoneActive(bool|array $active): static
    {
        $this->filterTimezoneActive = ArrayCast::makeBoolArray($active);
        return $this;
    }

    /**
     * Drop any filtering, so we get un-conditional loading
     * @return static
     */
    protected function clearFilterTimezone(): static
    {
        $this->dropFilterTimezoneStatusId();
        return $this;
    }

    /**
     * Drop filtering by u.user_status_id
     * @return static
     */
    protected function dropFilterTimezoneStatusId(): static
    {
        $this->filterTimezoneActive = null;
        return $this;
    }

    /**
     * @return bool[]|null
     */
    protected function getFilterTimezoneActive(): ?array
    {
        return $this->filterTimezoneActive;
    }

    /**
     * @return bool
     */
    protected function hasFilterTimezoneActive(): bool
    {
        return $this->filterTimezoneActive !== null;
    }
}
