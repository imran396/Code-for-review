<?php
/**
 * SAM-10625: Supply uniqueness for user fields: username
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Lock;

/**
 * Trait UserModificationLockerCreateTrait
 * @package Sam\User\Lock
 */
trait UserModificationLockerCreateTrait
{
    protected ?UserModificationLocker $userModificationLocker = null;

    /**
     * @return UserModificationLocker
     */
    protected function createUserModificationLocker(): UserModificationLocker
    {
        return $this->userModificationLocker ?: UserModificationLocker::new();
    }

    /**
     * @param UserModificationLocker $userModificationLocker
     * @return static
     * @internal
     */
    public function setUserModificationLocker(UserModificationLocker $userModificationLocker): static
    {
        $this->userModificationLocker = $userModificationLocker;
        return $this;
    }
}
