<?php
/**
 * SAM-4666: User customer no adviser
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\CustomerNo\Suggest;

/**
 * Trait UserCustomerNoAdviserCreateTrait
 * @package Sam\User\CustomerNo\Suggest
 */
trait UserCustomerNoAdviserCreateTrait
{
    protected ?UserCustomerNoAdviser $userCustomerNoAdviser = null;

    /**
     * @return UserCustomerNoAdviser
     */
    protected function createUserCustomerNoAdviser(): UserCustomerNoAdviser
    {
        return $this->userCustomerNoAdviser ?: UserCustomerNoAdviser::new();
    }

    /**
     * @param UserCustomerNoAdviser $userCustomerNoAdviser
     * @return static
     * @internal
     */
    public function setUserCustomerNoAdviser(UserCustomerNoAdviser $userCustomerNoAdviser): static
    {
        $this->userCustomerNoAdviser = $userCustomerNoAdviser;
        return $this;
    }
}
