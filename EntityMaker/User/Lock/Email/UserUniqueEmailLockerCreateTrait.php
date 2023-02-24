<?php
/**
 * SAM-10626: Supply uniqueness for user fields: email
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Lock\Email;

/**
 * Trait UserUniqueEmailLockerCreateTrait
 * @package Sam\EntityMaker\User\Lock\Email
 */
trait UserUniqueEmailLockerCreateTrait
{
    protected ?UserUniqueEmailLocker $userUniqueEmailLocker = null;

    /**
     * @return UserUniqueEmailLocker
     */
    protected function createUserUniqueEmailLocker(): UserUniqueEmailLocker
    {
        return $this->userUniqueEmailLocker ?: UserUniqueEmailLocker::new();
    }

    /**
     * @param UserUniqueEmailLocker $userUniqueEmailLocker
     * @return static
     * @internal
     */
    public function setUserUniqueEmailLocker(UserUniqueEmailLocker $userUniqueEmailLocker): static
    {
        $this->userUniqueEmailLocker = $userUniqueEmailLocker;
        return $this;
    }
}
