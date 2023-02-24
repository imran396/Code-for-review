<?php
/**
 * SAM-6693: Implement JWT for the needs of a PublicDataProvider
 * SAM-5729: Refactor User Authorization Identity management module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/19/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Auth\Identity\Storage;

/**
 * Trait StorageAwareTrait
 * @package
 */
trait AuthIdentityStoragePoolAwareTrait
{
    protected ?AuthIdentityStoragePool $authIdentityStoragePool = null;

    /**
     * @return AuthIdentityStoragePool
     * @internal getter
     */
    protected function getAuthIdentityStoragePool(): AuthIdentityStoragePool
    {
        if ($this->authIdentityStoragePool === null) {
            $this->authIdentityStoragePool = AuthIdentityStoragePool::new()->construct();
        }
        return $this->authIdentityStoragePool;
    }

    /**
     * @param AuthIdentityStoragePool $authIdentityStoragePool
     * @return $this
     * @internal setter
     */
    public function setAuthIdentityStoragePool(AuthIdentityStoragePool $authIdentityStoragePool): static
    {
        $this->authIdentityStoragePool = $authIdentityStoragePool;
        return $this;
    }
}
