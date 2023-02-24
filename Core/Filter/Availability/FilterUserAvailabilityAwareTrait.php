<?php
/**
 * User Entity availability filtering definition logic. It is intended for usage in Entity Loaders and Existence Checkers
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
 * Trait FilterUserAvailabilityAwareTrait
 * @package Sam\Core\Filter\Availability
 */
trait FilterUserAvailabilityAwareTrait
{
    /**
     * Filter results by these statuses
     * @var int[]|null
     */
    private ?array $filterUserStatusId = null;

    /**
     * Define filtering by user statuses
     * @param int|int[] $userStatusId
     * @return static
     */
    public function filterUserStatusId(int|array $userStatusId): static
    {
        $this->filterUserStatusId = ArrayCast::makeIntArray($userStatusId, Constants\User::USER_STATUSES);
        return $this;
    }

    /**
     * Drop any filtering, so we get un-conditional loading
     * @return static
     */
    protected function clearFilterUser(): static
    {
        $this->dropFilterUserStatusId();
        return $this;
    }

    /**
     * Drop filtering by u.user_status_id
     * @return static
     */
    protected function dropFilterUserStatusId(): static
    {
        $this->filterUserStatusId = null;
        return $this;
    }

    /**
     * @return int[]|null
     */
    protected function getFilterUserStatusId(): ?array
    {
        return $this->filterUserStatusId;
    }

    /**
     * @return bool
     */
    protected function hasFilterUserStatusId(): bool
    {
        return $this->filterUserStatusId !== null;
    }
}
