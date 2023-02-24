<?php
/**
 * SAM-7985: Move User related data loader methods from global to Sam namespace
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Commission\Load;

/**
 * Trait UserSalesCommissionLoaderCreateTrait
 * @package Sam\Commission\Load
 */
trait UserSalesCommissionLoaderCreateTrait
{
    protected ?UserSalesCommissionLoader $userSalesCommissionLoader = null;

    /**
     * @return UserSalesCommissionLoader
     */
    protected function createUserSalesCommissionLoader(): UserSalesCommissionLoader
    {
        return $this->userSalesCommissionLoader ?: UserSalesCommissionLoader::new();
    }

    /**
     * @param UserSalesCommissionLoader $userSalesCommissionLoader
     * @return static
     * @internal
     */
    public function setUserSalesCommissionLoader(UserSalesCommissionLoader $userSalesCommissionLoader): static
    {
        $this->userSalesCommissionLoader = $userSalesCommissionLoader;
        return $this;
    }
}
