<?php
/**
 * SingleUseFS class checks that SSO link has not been used yet
 *
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 8, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Cache\File;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Sso\TokenLink\Cache\TokenLinkCacherInterface;

/**
 * Class SingleUseFS
 * @package Sam\Sso\TokenLink
 */
class TokenLinkFileCacher extends CustomizableClass implements TokenLinkCacherInterface
{
    use FilesystemCacheManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->getFilesystemCacheManager()->setNamespace(Constants\TokenLink::CACHE_NAMESPACE);
        return $this;
    }

    /**
     * Remove the PHP session ID string associated with the encrypted string and signature
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        return $this->getFilesystemCacheManager()->delete($key);
    }

    /**
     * @param string $key
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function has(string $key): bool
    {
        return $this->getFilesystemCacheManager()->has($key);
    }

    /**
     * @param string $key
     * @return string
     * @throws \InvalidArgumentException
     */
    public function get(string $key): string
    {
        return $this->getFilesystemCacheManager()->get($key);
    }

    /**
     * @param string $encryptedString
     * @param string $signature
     * @return string
     */
    public function makeKey(string $encryptedString, string $signature): string
    {
        return sprintf('%s~%s', $encryptedString, $signature);
    }

    /**
     * Save the PHP session ID string associated with the encrypted string and signature (first use)
     * @param string $key
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function set(string $key): bool
    {
        return $this->getFilesystemCacheManager()->set($key, session_id());
    }
}
