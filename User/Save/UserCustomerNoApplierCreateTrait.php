<?php
/**
 * Trait for UserCustomerNoApplier
 *
 * SAM-6733: Duplicate customer number at signup.
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Save;

/**
 * Trait UserCustomerNoApplierCreateTrait
 * @package
 */
trait UserCustomerNoApplierCreateTrait
{
    protected ?UserCustomerNoApplier $userCustomerNoApplier = null;

    /**
     * @return UserCustomerNoApplier
     */
    protected function createUserCustomerNoApplier(): UserCustomerNoApplier
    {
        return $this->userCustomerNoApplier ?: UserCustomerNoApplier::new();
    }

    /**
     * @param UserCustomerNoApplier $userCustomerNoApplier
     * @return $this
     * @internal
     */
    public function setUserCustomerNoApplier(UserCustomerNoApplier $userCustomerNoApplier): static
    {
        $this->userCustomerNoApplier = $userCustomerNoApplier;
        return $this;
    }
}
