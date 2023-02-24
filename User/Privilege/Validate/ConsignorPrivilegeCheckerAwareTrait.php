<?php
/**
 * SAM-5624: Separate PrivilegeCheckersAwareTrait to traits per role
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/14/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Privilege\Validate;

/**
 * Trait ConsignorPrivilegeCheckerAwareTrait
 * @package Sam\User\Privilege\Validate
 */
trait ConsignorPrivilegeCheckerAwareTrait
{
    protected ?ConsignorPrivilegeChecker $consignorPrivilegeChecker = null;

    /**
     * @return ConsignorPrivilegeChecker
     */
    protected function getConsignorPrivilegeChecker(): ConsignorPrivilegeChecker
    {
        if ($this->consignorPrivilegeChecker === null) {
            $this->consignorPrivilegeChecker = ConsignorPrivilegeChecker::new();
        }
        return $this->consignorPrivilegeChecker;
    }

    /**
     * @param ConsignorPrivilegeChecker $consignorPrivilegeChecker
     * @return static
     * @internal
     */
    public function setConsignorPrivilegeChecker(ConsignorPrivilegeChecker $consignorPrivilegeChecker): static
    {
        $this->consignorPrivilegeChecker = $consignorPrivilegeChecker;
        return $this;
    }
}
