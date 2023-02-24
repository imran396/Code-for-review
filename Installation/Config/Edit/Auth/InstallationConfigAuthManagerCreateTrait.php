<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/7/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Auth;

/**
 * Trait InstallationConfigAuthManagerCreateTrait
 * @package Sam\Installation\Config
 */
trait InstallationConfigAuthManagerCreateTrait
{
    /**
     * @var AuthManager|null
     */
    protected ?AuthManager $installationConfigAuthManager = null;

    /**
     * @return AuthManager
     */
    protected function createInstallationConfigAuthManager(): AuthManager
    {
        $installationConfigAuthManager = $this->installationConfigAuthManager ?: AuthManager::new();
        return $installationConfigAuthManager;
    }

    /**
     * @param AuthManager $installationConfigAuthManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setInstallationConfigAuthManager(AuthManager $installationConfigAuthManager): static
    {
        $this->installationConfigAuthManager = $installationConfigAuthManager;
        return $this;
    }
}
