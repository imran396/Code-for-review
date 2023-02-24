<?php
/**
 * SAM-4819: Entity aware traits
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/28/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\AwareTrait;

use Account;

/**
 * Trait SystemAccountAwareTrait
 * @package Sam\Storage\Entity\AwareTrait
 */
interface SystemAccountAwareInterface
{
    /**
     * @return bool
     */
    public function hasSystemAccountId(): bool;

    /**
     * @return int
     */
    public function getSystemAccountId(): int;

    /**
     * @param int $accountId
     * @return self
     */
    public function setSystemAccountId(int $accountId): self;

    /**
     * @return bool
     */
    public function hasSystemAccount(): bool;

    /**
     * Return Account|null object
     * @param bool $isReadOnlyDb
     * @return Account
     */
    public function getSystemAccount(bool $isReadOnlyDb = false): Account;

    /**
     * @param Account|null $account
     * @return self
     */
    public function setSystemAccount(?Account $account): self;

    /**
     * Check, if current system account is portal sub-account of multiple tenant installation.
     * In case of single tenant installation, then return false.
     * @return bool
     */
    public function isPortalSystemAccount(): bool;

    /**
     * Check, if currently we are visiting main account.
     * It is always true for Single Tenant installation.
     * @return bool
     */
    public function isMainSystemAccount(): bool;

    /**
     * Check, if currently we are visiting main account in SAM Portal installation
     * @return bool
     */
    public function isMainSystemAccountForMultipleTenant(): bool;
}
