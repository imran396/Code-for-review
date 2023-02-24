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

namespace Sam\EntityMaker\User\Lock;

/**
 * Trait UserMakerLockerCreateTrait
 * @package Sam\EntityMaker\User\Lock
 */
trait UserMakerLockerCreateTrait
{
    protected ?UserMakerLocker $userMakerLocker = null;

    /**
     * @return UserMakerLocker
     */
    protected function createUserMakerLocker(): UserMakerLocker
    {
        return $this->userMakerLocker ?: UserMakerLocker::new();
    }

    /**
     * @param UserMakerLocker $userMakerLocker
     * @return static
     * @internal
     */
    public function setUserMakerLocker(UserMakerLocker $userMakerLocker): static
    {
        $this->userMakerLocker = $userMakerLocker;
        return $this;
    }
}
