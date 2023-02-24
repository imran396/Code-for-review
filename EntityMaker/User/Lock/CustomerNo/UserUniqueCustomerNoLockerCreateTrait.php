<?php
/**
 * SAM-10627: Supply uniqueness for user fields: customer#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Lock\CustomerNo;

/**
 * Trait UserUniqueCustomerNoLockerCreateTrait
 * @package Sam\EntityMaker\User\Lock\CustomerNo
 */
trait UserUniqueCustomerNoLockerCreateTrait
{
    protected ?UserUniqueCustomerNoLocker $userUniqueCustomerNoLocker = null;

    /**
     * @return UserUniqueCustomerNoLocker
     */
    protected function createUserUniqueCustomerNoLocker(): UserUniqueCustomerNoLocker
    {
        return $this->userUniqueCustomerNoLocker ?: UserUniqueCustomerNoLocker::new();
    }

    /**
     * @param UserUniqueCustomerNoLocker $userUniqueCustomerNoLocker
     * @return $this
     * @internal
     */
    public function setUserUniqueCustomerNoLocker(UserUniqueCustomerNoLocker $userUniqueCustomerNoLocker): static
    {
        $this->userUniqueCustomerNoLocker = $userUniqueCustomerNoLocker;
        return $this;
    }
}
