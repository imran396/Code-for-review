<?php
/**
 * SAM-8004: Refactor \Util_Storage
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Infrastructure\Storage;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * This class contains methods for working with cookies
 *
 * Class Cookie
 * @package Sam\Infrastructure\Storage
 */
class CookieStorage extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Read cookie value
     *
     * @param string $key
     * @return string|null
     */
    public function get(string $key): ?string
    {
        if (!$this->isAvailable()) {
            throw new \RuntimeException('Cookies is not available');
        }

        $value = $_COOKIE[$key] ?? null;
        return $value;
    }

    /**
     * Set cookie value
     *
     * @param string $key
     * @param string $value
     */
    public function set(string $key, string $value): void
    {
        if (!$this->isAvailable()) {
            throw new \RuntimeException('Cookies is not available');
        }

        $cookieParams = session_get_cookie_params();
        $options = [
            'expires' => $cookieParams["lifetime"],
            'path' => $this->cfg()->get('core->app->session->cookie->path'),
            'domain' => $this->cfg()->get('core->app->session->cookie->domain'),
            'secure' => $this->cfg()->get('core->app->session->cookie->secure'),
            'httponly' => $this->cfg()->get('core->app->session->cookie->httpOnly'),
            'samesite' => $this->cfg()->get('core->app->session->cookie->sameSite')
        ];
        setcookie($key, $value, $options);
        $_COOKIE[$key] = $value;
    }

    /**
     * Delete cookie
     *
     * @param string $key
     */
    public function remove(string $key): void
    {
        if (!$this->isAvailable()) {
            throw new \RuntimeException('Cookies is not available');
        }

        setcookie(
            $key,
            '',
            time() - 3600,
            $this->cfg()->get('core->app->session->cookie->path'),
            $this->cfg()->get('core->app->session->cookie->domain'),
            $this->cfg()->get('core->app->session->cookie->secure'),
            $this->cfg()->get('core->app->session->cookie->httpOnly')
        );
        unset($_COOKIE[$key]);
    }

    /**
     * Read all cookies
     *
     * @return array
     */
    public function all(): array
    {
        if (!$this->isAvailable()) {
            throw new \RuntimeException('Cookies is not available');
        }
        return $_COOKIE;
    }

    /**
     * Detect if cookies is available in the current context (WEB / CLI / etc.)
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        $isAvailable = $this->isWebRequest();
        return $isAvailable;
    }

    /**
     * Checks if PHP's serving a web request
     * @return bool
     */
    protected function isWebRequest(): bool
    {
        return PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg';
    }
}
