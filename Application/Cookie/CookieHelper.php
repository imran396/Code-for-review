<?php
/**
 * SAM-4475: Cookie helper https://bidpath.atlassian.net/browse/SAM-4475
 *
 * @author        Vahagn Hovsepyan
 * @version       SAM 2.0
 * @since         Oct 10, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 */

namespace Sam\Application\Cookie;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class CookieHelper
 */
class CookieHelper extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    private const COOKIE_AUTHENTICATED = 'Authenticated';
    private const COOKIE_HTTP_REFERER = 'HttpReferer';
    private const COOKIE_JWT_AUTH_TOKEN = 'JwtAuth';
    private const COOKIE_MAPP = 'mapp';
    private const COOKIE_SAM_LANG = 'SamLang';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /** COOKIE_AUTHENTICATED */

    /**
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return !empty($_COOKIE[self::COOKIE_AUTHENTICATED]);
    }

    /**
     * @param bool $enable
     */
    public function enableAuthenticated(bool $enable): void
    {
        if ($this->isAuthenticated() === $enable) {
            return;
        }
        $this->setCookie(self::COOKIE_AUTHENTICATED, (string)$enable, session_get_cookie_params()['lifetime']);
    }

    // ---- Php Native Session ---

    public function dropPhpSessionId(): void
    {
        $this->setCookie(session_name(), '');
    }

    public function setPhpSessionId(string $sessionId): void
    {
        $_COOKIE[session_name()] = $sessionId;
    }

    /** COOKIE_HTTP_REFERER */

    /**
     * @return string
     */
    public function getHttpReferer(): string
    {
        return isset($_COOKIE[self::COOKIE_HTTP_REFERER]) ? (string)$_COOKIE[self::COOKIE_HTTP_REFERER] : '';
    }

    /**
     * @return bool
     */
    public function hasHttpReferer(): bool
    {
        return isset($_COOKIE[self::COOKIE_HTTP_REFERER]);
    }

    /**
     * @param string $httpReferer
     */
    public function setHttpReferer(string $httpReferer): void
    {
        $this->setCookie(self::COOKIE_HTTP_REFERER, $httpReferer, 60 * 60 * 2, null, true);
    }

    /** COOKIE_SAM_LANG */

    /**
     * @return int|null
     */
    public function getLanguageId(): ?int
    {
        return Cast::toInt($_COOKIE[self::COOKIE_SAM_LANG] ?? null);
    }

    /**
     * @return bool
     */
    public function hasLanguageId(): bool
    {
        return isset($_COOKIE[self::COOKIE_SAM_LANG]);
    }

    /**
     * @param int $languageId
     */
    public function setLanguageId(int $languageId): void
    {
        $this->setCookie(self::COOKIE_SAM_LANG, (string)$languageId, 60 * 60 * 24);
    }

    /** COOKIE_MAPP */

    /**
     * @return mixed
     */
    public function getMapp(): mixed
    {
        return $_COOKIE[self::COOKIE_MAPP];
    }

    /**
     * @return bool
     */
    public function hasMapp(): bool
    {
        return isset($_COOKIE[self::COOKIE_MAPP]);
    }

    /**
     * @param string $token
     */
    public function setJwtAuthIdentityToken(string $token): void
    {
        $this->setCookie(self::COOKIE_JWT_AUTH_TOKEN, $token, null, null, true);
    }

    /**
     * @return bool
     */
    public function hasJwtAuthIdentityToken(): bool
    {
        return isset($_COOKIE[self::COOKIE_JWT_AUTH_TOKEN])
            && $_COOKIE[self::COOKIE_JWT_AUTH_TOKEN] !== '';
    }

    /**
     * @return string
     */
    public function getJwtAuthIdentityToken(): string
    {
        return $this->hasJwtAuthIdentityToken()
            ? (string)$_COOKIE[self::COOKIE_JWT_AUTH_TOKEN]
            : '';
    }

    public function deleteJwtAuthIdentityToken(): void
    {
        if ($this->hasJwtAuthIdentityToken()) {
            $this->deleteCookie(self::COOKIE_JWT_AUTH_TOKEN);
        }
    }

    /**
     * @param string $name
     * @param string $value
     * @param int|null $time
     * @param string|null $path
     * @param bool $forceHttpOnly
     */
    public function setCookie(
        string $name,
        string $value,
        ?int $time = null,
        ?string $path = null,
        bool $forceHttpOnly = false
    ): void {
        $options = [
            'expires' => $time ? (time() + $time) : 0,
            'path' => $path ?: $this->cfg()->get('core->app->session->cookie->path'),
            'domain' => $this->cfg()->get('core->app->session->cookie->domain'),
            'secure' => $this->cfg()->get('core->app->session->cookie->secure'),
            'httponly' => $forceHttpOnly ? true : $this->cfg()->get('core->app->session->cookie->httpOnly'),
            'samesite' => $this->cfg()->get('core->app->session->cookie->sameSite')
        ];
        setcookie($name, $value, $options);
        $_COOKIE[$name] = $value;
    }

    public function deleteCookie(string $name): void
    {
        $this->setCookie($name, '', -3600, null, true);
    }

    public function has(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    public function get(string $name): mixed
    {
        return $this->has($name) ? $_COOKIE[$name] : null;
    }

    public function getString(string $name): string
    {
        return $this->has($name) ? $_COOKIE[$name] : "";
    }
}
