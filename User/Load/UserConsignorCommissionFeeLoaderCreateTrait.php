<?php
/**
 * SAM-7974: Multiple Consignor commission rates and unsold commission extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May. 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Load;

/**
 * Trait UserConsignorCommissionFeeLoaderCreateTrait
 * @package Sam\User\Load
 */
trait UserConsignorCommissionFeeLoaderCreateTrait
{
    protected ?UserConsignorCommissionFeeLoader $userConsignorCommissionFeeLoader = null;

    /**
     * @return UserConsignorCommissionFeeLoader
     */
    protected function createUserConsignorCommissionFeeLoader(): UserConsignorCommissionFeeLoader
    {
        return $this->userConsignorCommissionFeeLoader ?: UserConsignorCommissionFeeLoader::new();
    }

    /**
     * @param UserConsignorCommissionFeeLoader $userConsignorCommissionFeeLoader
     * @return static
     * @internal
     */
    public function setUserConsignorCommissionFeeLoader(UserConsignorCommissionFeeLoader $userConsignorCommissionFeeLoader): static
    {
        $this->userConsignorCommissionFeeLoader = $userConsignorCommissionFeeLoader;
        return $this;
    }
}
