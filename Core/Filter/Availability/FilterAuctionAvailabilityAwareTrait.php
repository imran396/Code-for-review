<?php
/**
 * Auction Entity availability filtering definition logic. It is intended for usage in Entity Loaders and Existence Checkers
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
 * Trait FilterAuctionAvailabilityAwareTrait
 * @package Sam\Core\Filter\Availability
 */
trait FilterAuctionAvailabilityAwareTrait
{
    /**
     * Filter results by these statuses
     * @var int[]|null
     */
    private ?array $filterAuctionStatusId = null;

    /**
     * Define filtering by user statuses
     * @param int|int[] $auctionStatusId
     * @return static
     */
    public function filterAuctionStatusId(int|array $auctionStatusId): static
    {
        $this->filterAuctionStatusId = ArrayCast::makeIntArray($auctionStatusId, Constants\Auction::$auctionStatuses);
        return $this;
    }

    /**
     * Drop any filtering, so we get un-conditional loading
     * @return static
     */
    protected function clearFilterAuction(): static
    {
        $this->dropFilterAuctionStatusId();
        return $this;
    }

    /**
     * Drop filtering by a.auction_status_id
     * @return static
     */
    protected function dropFilterAuctionStatusId(): static
    {
        $this->filterAuctionStatusId = null;
        return $this;
    }

    /**
     * @return array|null
     */
    protected function getFilterAuctionStatusId(): ?array
    {
        return $this->filterAuctionStatusId;
    }

    /**
     * @return bool
     */
    protected function hasFilterAuctionStatusId(): bool
    {
        return $this->filterAuctionStatusId !== null;
    }
}
