<?php
/**
 * LotItem Entity availability filtering definition logic. It is intended for usage in Entity Loaders and Existence Checkers
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
 * Trait FilterLotItemAvailabilityAwareTrait
 * @package Sam\Core\Filter\Availability
 */
trait FilterLotItemAvailabilityAwareTrait
{
    /**
     * Filter results by these statuses
     * @var bool[]|null
     */
    private ?array $filterLotItemActive = null;

    /**
     * Define filtering by user statuses
     * @param bool|bool[] $active
     * @return static
     */
    public function filterLotItemActive(bool|array $active): static
    {
        $this->filterLotItemActive = ArrayCast::makeBoolArray($active);
        return $this;
    }

    /**
     * Drop any filtering, so we get un-conditional loading
     * @return static
     */
    protected function clearFilterLotItem(): static
    {
        $this->dropFilterLotItemActive();
        return $this;
    }

    /**
     * Drop filtering by u.user_status_id
     * @return static
     */
    protected function dropFilterLotItemActive(): static
    {
        $this->filterLotItemActive = null;
        return $this;
    }

    /**
     * @return bool[]|null
     */
    protected function getFilterLotItemActive(): ?array
    {
        return $this->filterLotItemActive;
    }

    /**
     * @return bool
     */
    protected function hasFilterLotItemActive(): bool
    {
        return $this->filterLotItemActive !== null;
    }
}
