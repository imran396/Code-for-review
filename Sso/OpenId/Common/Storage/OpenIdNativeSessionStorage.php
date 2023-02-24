<?php
/**
 * SAM-10584: SAM SSO
 * SAM-10724: Login through SSO
 *
 * Project        sam
 * @author        Georgi Nikolov
 * @version       SVN: $Id: $
 * @since         Jun 15, 2022
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Sso\OpenId\Common\Storage;

use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Storage\PhpSessionStorageCreateTrait;

/**
 * operate with native session storage.
 */
class OpenIdNativeSessionStorage extends CustomizableClass
{
    use PhpSessionStorageCreateTrait;

    private const OPENID_STATE = 'openid_state';
    private const OPENID_REFRESH_TOKEN = 'openid_refresh_token';
    private const OPENID_ACCESS_TOKEN = 'openid_access_token';
    private const OPENID_TOKEN_EXPIRATION_TS = 'openid_token_exp';
    private const OPENID_AFTER_LOGIN_REDIRECT_URL = 'openid_after_login_redirect_url';

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- OPENID_STATE ---

    public function hasState(): bool
    {
        return $this->has(self::OPENID_STATE);
    }

    public function getState(): string
    {
        return (string)$this->get(self::OPENID_STATE);
    }

    public function setState(string $state): static
    {
        $this->set(self::OPENID_STATE, $state);
        return $this;
    }

    public function deleteState(): void
    {
        $this->delete(self::OPENID_STATE);
    }

    // --- Back Url ---

    public function hasBackUrl(): bool
    {
        return $this->has(self::OPENID_AFTER_LOGIN_REDIRECT_URL);
    }

    public function hasBackUrlFilled(): bool
    {
        return $this->hasBackUrl()
            && $this->getBackUrl() !== '';
    }

    public function getBackUrl(): string
    {
        return (string)$this->get(self::OPENID_AFTER_LOGIN_REDIRECT_URL);
    }

    public function setBackUrl(string $state): static
    {
        $this->set(self::OPENID_AFTER_LOGIN_REDIRECT_URL, $state);
        return $this;
    }

    public function deleteBackUrl(): void
    {
        $this->delete(self::OPENID_AFTER_LOGIN_REDIRECT_URL);
    }

    // --- Access token ---

    public function hasAccessToken(): bool
    {
        return $this->has(self::OPENID_ACCESS_TOKEN);
    }

    public function getAccessToken(): string
    {
        return (string)$this->get(self::OPENID_ACCESS_TOKEN);
    }

    public function setAccessToken(string $token): static
    {
        $this->set(self::OPENID_ACCESS_TOKEN, $token);
        return $this;
    }

    public function deleteAccessToken(): static
    {
        $this->delete(self::OPENID_ACCESS_TOKEN);
        return $this;
    }

    // --- Refresh token ---

    public function hasRefreshToken(): bool
    {
        return $this->has(self::OPENID_REFRESH_TOKEN);
    }

    public function getRefreshToken(): string
    {
        return (string)$this->get(self::OPENID_REFRESH_TOKEN);
    }

    public function setRefreshToken(string $refreshToken): static
    {
        $this->set(self::OPENID_REFRESH_TOKEN, $refreshToken);
        return $this;
    }

    public function deleteRefreshToken(): void
    {
        $this->delete(self::OPENID_REFRESH_TOKEN);
    }

    // --- Token Expiration ---

    public function hasTokenExpirationTs(): bool
    {
        return $this->has(self::OPENID_TOKEN_EXPIRATION_TS);
    }

    public function getTokenExpirationTs(): int
    {
        return $this->get(self::OPENID_TOKEN_EXPIRATION_TS);
    }

    public function setTokenExpirationTs(int $exp): static
    {
        $this->set(self::OPENID_TOKEN_EXPIRATION_TS, $exp);
        return $this;
    }

    public function deleteTokenExpirationTs(): void
    {
        $this->delete(self::OPENID_TOKEN_EXPIRATION_TS);
    }

    // --- Common ---

    public function isSessionAvailable(): bool
    {
        return $this->createPhpSessionStorage()->isAvailable();
    }

    public function hasTokens(): bool
    {
        return $this->hasAccessToken()
            && $this->hasRefreshToken();
    }

    public function deleteTokens(): void
    {
        $this->deleteAccessToken();
        $this->deleteRefreshToken();
    }

    // --- Internal ---

    protected function has(string $key): bool
    {
        return $this->createPhpSessionStorage()->has($key);
    }

    protected function get(string $key): mixed
    {
        return $this->createPhpSessionStorage()->get($key);
    }

    protected function set(string $key, mixed $value): static
    {
        $this->createPhpSessionStorage()->set($key, $value);
        return $this;
    }

    protected function delete(string $key): void
    {
        $this->createPhpSessionStorage()->delete($key);
    }
}
