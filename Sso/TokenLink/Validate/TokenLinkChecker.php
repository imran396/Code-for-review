<?php
/**
 * Checks that SSO link has not been used yet
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

namespace Sam\Sso\TokenLink\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Sso\TokenLink\Cache\TokenLinkCacherFactoryCreateTrait;
use Sam\Sso\TokenLink\Cache\TokenLinkCacherInterface;
use Sam\Sso\TokenLink\Config\TokenLinkConfiguratorAwareTrait;

/**
 * Class SingleUseFS
 * @package Sam\Sso\TokenLink
 */
class TokenLinkChecker extends CustomizableClass
{
    use TokenLinkCacherFactoryCreateTrait;
    use TokenLinkConfiguratorAwareTrait;

    protected TokenLinkCacherInterface $cacher;

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
        $this->cacher = $this->createTokenLinkCacherFactory()->create();
        return $this;
    }

    /**
     * Determine if a link was used based on base64 encoded encrypted string
     * and signature and PHP session ID all passed as strings
     * @param string $encryptedString
     * @param string $signature
     * @return bool
     */
    public function isAlreadyUsed(string $encryptedString, string $signature): bool
    {
        $cachedSessionId = null;
        $key = $this->cacher->makeKey($encryptedString, $signature);
        if ($this->cacher->has($key)) {
            $cachedSessionId = $this->cacher->get($key);
        }
        $isUsed = $cachedSessionId
            && $cachedSessionId !== session_id()
            && session_status() !== PHP_SESSION_NONE;
        return $isUsed;
    }

    /**
     * Check username is valid to be used for token link
     * @param string $username
     * @return bool
     */
    public function checkUsername(string $username): bool
    {
        $separator = $this->getTokenLinkConfigurator()->getUserDataSeparator();
        if (
            trim($username) === ''
            || str_contains($username, $separator)
        ) {
            return false;
        }
        return true;
    }

    /**
     * Check secret is valid to be used for token link
     * @param string $secret
     * @return bool
     */
    public function checkSecret(string $secret): bool
    {
        $separator = $this->getTokenLinkConfigurator()->getUserDataSeparator();
        if (
            trim($secret) === ''
            || str_contains($secret, $separator)
        ) {
            return false;
        }
        return true;
    }

    /**
     * @param string $token
     * @return bool
     */
    public function checkTokenFormat(string $token): bool
    {
        $encryptedData = explode(Constants\TokenLink::TOKEN_SEPARATOR, $token);
        if (count($encryptedData) !== 2) {
            return false;
        }
        return true;
    }
}
