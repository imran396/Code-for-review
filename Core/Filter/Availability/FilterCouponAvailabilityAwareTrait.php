<?php
/**
 * Coupon Entity availability filtering definition logic. It is intended for usage in Entity Loaders and Existence Checkers
 *
 * SAM-4922: Entity Loader and Existence Checker approach integration
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           06/04/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\Availability;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;

/**
 * Trait FilterCouponAvailabilityAwareTrait
 * @package Sam\Core\Filter\Availability
 */
trait FilterCouponAvailabilityAwareTrait
{
    /**
     * Filter results by these statuses
     * @var int[]|null
     */
    private ?array $filterCouponStatusId = null;

    /**
     * Define filtering by user statuses
     * @param int|int[] $couponStatusId
     * @return static
     */
    public function filterCouponStatusId(int|array $couponStatusId): static
    {
        $this->filterCouponStatusId = ArrayCast::makeIntArray($couponStatusId, Constants\Coupon::$couponStatuses);
        return $this;
    }

    /**
     * Drop any filtering, so we get un-conditional loading
     * @return static
     */
    protected function clearFilterCoupon(): static
    {
        $this->dropFilterCouponStatusId();
        return $this;
    }

    /**
     * Drop filtering by u.user_status_id
     * @return static
     */
    protected function dropFilterCouponStatusId(): static
    {
        $this->filterCouponStatusId = null;
        return $this;
    }

    /**
     * @return array|null
     */
    protected function getFilterCouponStatusId(): ?array
    {
        return $this->filterCouponStatusId;
    }

    /**
     * @return bool
     */
    protected function hasFilterCouponStatusId(): bool
    {
        return $this->filterCouponStatusId !== null;
    }
}
