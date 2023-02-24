<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/3/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth\Identity;

/**
 * Trait AuthIdentityManagerCreateTrait
 * @package Sam\User\Auth\Identity
 */
trait AuthIdentityManagerCreateTrait
{
    protected ?AuthIdentityManager $authIdentityManager = null;

    /**
     * @return AuthIdentityManager
     */
    protected function createAuthIdentityManager(): AuthIdentityManager
    {
        return $this->authIdentityManager ?: AuthIdentityManager::new();
    }

    /**
     * @param AuthIdentityManager $authIdentityManager
     * @return static
     * @internal
     */
    public function setAuthIdentityManager(AuthIdentityManager $authIdentityManager): static
    {
        $this->authIdentityManager = $authIdentityManager;
        return $this;
    }
}
