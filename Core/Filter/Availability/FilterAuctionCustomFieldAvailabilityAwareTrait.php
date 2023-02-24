<?php
/**
 * AuctionCustField Entity availability filtering definition logic. It is intended for usage in Entity Loaders and Existence Checkers
 *
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
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
 * Trait FilterAuctionCustomFieldAvailabilityAwareTrait
 * @package Sam\Core\Filter\Availability
 */
trait FilterAuctionCustomFieldAvailabilityAwareTrait
{
    /**
     * Filter results by these statuses
     * @var bool[]|null
     */
    private ?array $filterAuctionCustomFieldActive = null;

    /**
     * Define filtering by Auction statuses
     * @param bool|bool[] $active
     * @return static
     */
    public function filterAuctionCustomFieldActive(bool|array $active): static
    {
        $this->filterAuctionCustomFieldActive = ArrayCast::makeBoolArray($active);
        return $this;
    }

    /**
     * Drop any filtering, so we get un-conditional loading
     * @return static
     */
    protected function clearFilterAuctionCustomField(): static
    {
        $this->dropFilterAuctionCustomFieldActive();
        return $this;
    }

    /**
     * @return static
     */
    protected function dropFilterAuctionCustomFieldActive(): static
    {
        $this->filterAuctionCustomFieldActive = null;
        return $this;
    }

    /**
     * @return bool[]|null
     */
    protected function getFilterAuctionCustomFieldActive(): ?array
    {
        return $this->filterAuctionCustomFieldActive;
    }

    /**
     * @return bool
     */
    protected function hasFilterAuctionCustomFieldActive(): bool
    {
        return $this->filterAuctionCustomFieldActive !== null;
    }
}
