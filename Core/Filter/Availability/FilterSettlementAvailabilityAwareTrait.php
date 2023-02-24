<?php
/**
 * Settlement Entity availability filtering definition logic. It is intended for usage in Entity Loaders and Existence Checkers
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
 * Trait FilterSettlementAvailabilityAwareTrait
 * @package Sam\Core\Filter\Availability
 */
trait FilterSettlementAvailabilityAwareTrait
{
    /**
     * Filter results by these statuses
     * @var int[]|null
     */
    private ?array $filterSettlementStatusId = null;

    /**
     * Define filtering by user statuses
     * @param int|int[] $settlementStatusId
     * @return static
     */
    public function filterSettlementStatusId(int|array $settlementStatusId): static
    {
        $this->filterSettlementStatusId = ArrayCast::makeIntArray(
            $settlementStatusId,
            Constants\Settlement::$settlementStatuses
        );
        return $this;
    }

    /**
     * Drop any filtering, so we get un-conditional loading
     * @return static
     */
    protected function clearFilterSettlement(): static
    {
        $this->dropFilterSettlementStatusId();
        return $this;
    }

    /**
     * Drop filtering by u.user_status_id
     * @return static
     */
    protected function dropFilterSettlementStatusId(): static
    {
        $this->filterSettlementStatusId = null;
        return $this;
    }

    /**
     * @return int[]|null
     */
    protected function getFilterSettlementStatusId(): ?array
    {
        return $this->filterSettlementStatusId;
    }

    /**
     * @return bool
     */
    protected function hasFilterSettlementStatusId(): bool
    {
        return $this->filterSettlementStatusId !== null;
    }
}
