<?php
/**
 * SAM-9594: Account management access checking
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Access\Management;

/**
 * Trait AccountManagementAccessCheckerCreateTrait
 * @package Sam\Account\Access\Management
 */
trait AccountManagementAccessCheckerCreateTrait
{
    protected ?AccountManagementAccessChecker $accountManagementAccessChecker = null;

    /**
     * @return AccountManagementAccessChecker
     */
    protected function createAccountManagementAccessChecker(): AccountManagementAccessChecker
    {
        return $this->accountManagementAccessChecker ?: AccountManagementAccessChecker::new();
    }

    /**
     * @param AccountManagementAccessChecker $accountManagementAccessChecker
     * @return $this
     * @internal
     */
    public function setAccountManagementAccessChecker(AccountManagementAccessChecker $accountManagementAccessChecker): static
    {
        $this->accountManagementAccessChecker = $accountManagementAccessChecker;
        return $this;
    }
}
