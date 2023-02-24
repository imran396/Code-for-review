<?php
/**
 * SAM-3566: Refactoring for user authorization logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           3/26/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\User\Auth\Credentials\Validate;


/**
 * Trait CredentialsCheckerCreateTrait
 * @package Sam\User\Auth\Credentials\Validate
 */
trait CredentialsCheckerCreateTrait
{
    protected ?CredentialsChecker $credentialsChecker = null;

    /**
     * @return CredentialsChecker
     */
    protected function createCredentialsChecker(): CredentialsChecker
    {
        $credentialsChecker = $this->credentialsChecker ?: CredentialsChecker::new();
        return $credentialsChecker;
    }

    /**
     * @param CredentialsChecker $credentialsChecker
     * @return static
     */
    public function setCredentialsChecker(CredentialsChecker $credentialsChecker): static
    {
        $this->credentialsChecker = $credentialsChecker;
        return $this;
    }
}
