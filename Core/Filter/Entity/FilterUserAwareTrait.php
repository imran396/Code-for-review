<?php
/**
 * SAM-4616: Reports code refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           05/01/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\Entity;

use User;
use Sam\Storage\Entity\Aggregate\UserAggregate;

/**
 * Trait FilterUserAwareTrait
 * @package Sam\Core\Filter\Entity
 */
trait FilterUserAwareTrait
{
    protected ?UserAggregate $userAggregate = null;

    /**
     * @return int|null
     */
    public function getFilterUserId(): ?int
    {
        return $this->getUserAggregate()->getUserId();
    }

    /**
     * @param int|null $userId
     * @return static
     */
    public function filterUserId(int|null $userId): static
    {
        $this->getUserAggregate()->setUserId($userId);
        return $this;
    }

    /**
     * @return User|null
     */
    public function getFilterUser(): ?User
    {
        return $this->getUserAggregate()->getUser(true);
    }

    /**
     * @param User|null $user
     * @return static
     */
    public function filterUser(?User $user = null): static
    {
        $this->getUserAggregate()->setUser($user);
        return $this;
    }

    // --- UserAggregate ---

    /**
     * @return UserAggregate
     */
    protected function getUserAggregate(): UserAggregate
    {
        if ($this->userAggregate === null) {
            $this->userAggregate = UserAggregate::new();
        }
        return $this->userAggregate;
    }
}
