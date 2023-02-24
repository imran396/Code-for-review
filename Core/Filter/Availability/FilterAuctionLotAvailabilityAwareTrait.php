<?php
/**
 * AuctionLotItem Entity availability filtering definition logic. It is intended for usage in Entity Loaders and Existence Checkers
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

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;

/**
 * Trait FilterAuctionLotAvailabilityAwareTrait
 * @package Sam\Core\Filter\Availability
 */
trait FilterAuctionLotAvailabilityAwareTrait
{
    /**
     * Filter results by these statuses
     * @var int[]|null
     */
    private ?array $filterLotStatusId = null;

    /**
     * Define filtering by user statuses
     * @param int|int[] $lotStatusId
     * @return static
     */
    public function filterLotStatusId(int|array $lotStatusId): static
    {
        $this->filterLotStatusId = ArrayCast::makeIntArray($lotStatusId, Constants\Lot::$lotStatuses);
        return $this;
    }

    /**
     * Drop any filtering, so we get un-conditional loading
     * @return static
     */
    protected function clearFilterAuctionLot(): static
    {
        $this->dropFilterLotStatusId();
        return $this;
    }

    /**
     * Drop filtering by u.user_status_id
     * @return static
     */
    protected function dropFilterLotStatusId(): static
    {
        $this->filterLotStatusId = null;
        return $this;
    }

    /**
     * @return array|null
     */
    protected function getFilterLotStatusId(): ?array
    {
        return $this->filterLotStatusId;
    }

    /**
     * @return bool
     */
    protected function hasFilterLotStatusId(): bool
    {
        return $this->filterLotStatusId !== null;
    }
}
