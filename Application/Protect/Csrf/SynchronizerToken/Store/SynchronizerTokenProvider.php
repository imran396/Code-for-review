<?php
/**
 * Csrf synchronizer token generating and management in session
 *
 * SAM-5296: CSRF/XSRF Cross Site Request Forgery vulnerability
 * SAM-5675: Refactor Synchronizer token related logic and implement unit tests
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Tom Blondeau
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/10/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Csrf\SynchronizerToken\Store;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Protect\Csrf\SynchronizerToken\Build\SynchronizerTokenBuilderCreateTrait;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForPostAwareTrait;
use Sam\Core\Service\CustomizableClass;

class SynchronizerTokenProvider extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use ParamFetcherForPostAwareTrait;
    use ServerRequestReaderAwareTrait;
    use SynchronizerTokenBuilderCreateTrait;

    private const SESSION_KEY = 'SynchronizerToken';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return csrf token value from session, or generate it and init session value, when absent.
     * @return string|null
     */
    public function getSessionTokenOrCreate(): ?string
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            if (!isset($_SESSION[self::SESSION_KEY])) {
                $this->initSessionToken();
            }
            return $_SESSION[self::SESSION_KEY];
        }

        log_trace('Csrf token was not created, because session service not available' . composeSuffix(['session_status()' => session_status()]));
        return null;
    }

    /**
     * Return csrf token value from session, or null if absent.
     * @return string - empty means absent
     */
    public function getSessionToken(): string
    {
        $csrfSessionToken = $this->hasSessionToken() ? (string)$_SESSION[self::SESSION_KEY] : '';
        return $csrfSessionToken;
    }

    /**
     * @param string $token
     * @return static
     */
    protected function setSessionToken(string $token): static
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION[self::SESSION_KEY] = $token;
        }
        return $this;
    }

    /**
     * @return bool
     */
    protected function hasSessionToken(): bool
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return isset($_SESSION[self::SESSION_KEY]);
        }
        return false;
    }

    /**
     * Generate token and store in session
     * @return static
     */
    protected function initSessionToken(): static
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            if (!$this->hasSessionToken()) {
                // only set if it is not set yet
                $this->setSessionToken($this->generateToken());
            }
        }
        return $this;
    }

    /**
     * Generate and return a random token
     * @return string
     */
    protected function generateToken(): string
    {
        return $this->createSynchronizerTokenBuilder()->generate();
    }
}
