<?php
/**
 * Php session initialization class.
 *
 * SAM-5171: Application layer
 * SAM-6523: Request to No-session route leads to logout
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/3/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Index\Base\Concrete\NativeSession;

use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Index\Base\Concrete\NativeSession\Internal\AuthenticatedFlag\AuthenticatedCookieInitializator;
use Sam\Application\Index\Responsive\ResponsiveEntryPointConstants;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\Session\PhpSessionCloserCreateTrait;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Log\Support\Internal\Content\ContentBuilder;
use Sam\Log\Support\SupportLoggerAwareTrait;
use Sam\Security\Ssl\Feature\SslAvailabilityCheckerCreateTrait;

/**
 * Class PhpSessionInitializer
 * @package
 */
class PhpSessionInitializer extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CookieHelperCreateTrait;
    use ParamFetcherForGetAwareTrait;
    use PhpSessionCloserCreateTrait;
    use ServerRequestReaderAwareTrait;
    use SslAvailabilityCheckerCreateTrait;
    use SupportLoggerAwareTrait;
    use UrlParserAwareTrait;

    /** @var Ui */
    protected Ui $ui;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Ui $ui
     * @return $this
     */
    public function construct(Ui $ui): static
    {
        $this->ui = $ui;
        return $this;
    }

    public function initialize(): void
    {
        $isSessionAvailable = $this->isSessionFeatureAvailable();
        if ($isSessionAvailable) {
            // fix for issue w/ IE when embedding in Iframe
            header('P3P: CP="CAO PSA OUR"');

            $isSessionIdInRequest = $this->isSessionIdSetInRequest();
            $sessionId = $this->detectSessionIdFromRequest();

            if (
                $isSessionIdInRequest
                && !$this->validateSessionIdFormat($sessionId)
            ) {
                $this->protectAgainstInvalidSessionId($sessionId);
                return; // JIC, we are redirected before
            }

            $success = $this->startPhpSession();
            if (!$success) {
                $this->createApplicationRedirector()->badRequest();
            }

            /**
             * Call 'Authenticated' cookie update here in php session scope,
             * because its sense is highly related to php session technically and by business sense.
             * Actually 'Authenticated' cookie has sense only in disabled state, because enabled state always leads to session's side auth.
             *
             * We should run this logic only in case, when session should be available for running route.
             * We don't want to drop 'Authenticated' cookie for no-session routes. (SAM-6523)
             */
            if ($this->ui->isWebResponsive()) {
                AuthenticatedCookieInitializator::new()->initialize();
            }
        }
    }

    /**
     * @return bool
     */
    protected function isSessionFeatureAvailable(): bool
    {
        if (session_status() === PHP_SESSION_DISABLED) {
            log_debug("PHP Native session is disabled");
            return false;
        }

        /**
         * 1. Start session, if it is GET request for SSO token authorization.
         */
        $tokenParameterName = $this->cfg()->get('core->sso->tokenLink->tokenParameterName');
        if ($this->getParamFetcherForGet()->has($tokenParameterName)) {
            return true;
        }

        /**
         * Public side specific check, because admin side is always running under php session.
         */
        if ($this->ui->isWebResponsive()) {
// IK, 2020-10: commented out code, because we don't want to start session in every excess case
// that are registered among no-session routes in \Sam\Application\Index\Responsive\ResponsiveEntryPointConstants::$noSessionRoutes
// even when 'Authenticated' cookie is set.
//
//            /**
//             * 2. Always start the session, if 'Authenticated' cookie is set.
//             * We will verify authenticated user by its presence in php session.
//             * If user is not authenticated, then AuthenticatedCookieInitializator will drop 'Authenticated' cookie.
//             * SAM-1196 - this feature is only for public side.
//             */
//            if ($this->createCookieHelper()->isAuthenticated()) {
//                return true;
//            }

            /**
             * 3. Check session white-list and black-list routes.
             * Next logic reveals the sense of disabled 'Authenticated' cookie state - we can skip session start
             * in case of specific must-session and no-session routes.
             */
            $requestUri = $this->getServerRequestReader()->requestUri();
            $isMustSessionRoute = $this->getUrlParser()->inUrl(ResponsiveEntryPointConstants::$mustSessionRoutes, $requestUri);
            if (!$isMustSessionRoute) {
                $isNoSessionRoute = $this->getUrlParser()->inUrl(ResponsiveEntryPointConstants::$noSessionRoutes, $requestUri);
                if ($isNoSessionRoute) {
                    log_trace(
                        'Session is not available because visiting route is among no-session routes'
                        . composeSuffix(['requestUri' => $requestUri])
                    );
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function isSessionIdSetInRequest(): bool
    {
        $sessionName = session_name();
        $sessionCookies = (int)ini_get('session.use_cookies') === 1;
        $sessionCookiesOnly = (int)ini_get('session.use_only_cookies') === 1;
        if ($sessionCookies) {
            $sessionIdSet = isset($_COOKIE[$sessionName]);
        } else {
            $sessionIdSet = !$sessionCookiesOnly ? isset($_REQUEST[$sessionName]) : false;
        }
        return $sessionIdSet;
    }

    /**
     * @return string
     */
    protected function detectSessionIdFromRequest(): string
    {
        $sessionName = session_name();
        $sessionCookies = (int)ini_get('session.use_cookies') === 1;
        $sessionCookiesOnly = (int)ini_get('session.use_only_cookies') === 1;
        $paramFetcherForGet = $this->getParamFetcherForGet();
        if (!$sessionCookies) {
            $sessionId = $paramFetcherForGet->getString($sessionName);
        } elseif (isset($_COOKIE[$sessionName])) {
            $sessionId = $_COOKIE[$sessionName];
        } else {
            $sessionId = (!$sessionCookiesOnly
                && $paramFetcherForGet->has($sessionName))
                ? $paramFetcherForGet->getString($sessionName)
                : '';
        }
        return $sessionId;
    }

    /**
     * @param string $sessionId
     * @return bool
     */
    protected function validateSessionIdFormat(string $sessionId): bool
    {
        $success = (bool)preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $sessionId);
        return $success;
    }

    /**
     * Results with "400 - Bad Request" http response or restarts session
     * @param string $sessionId
     */
    protected function protectAgainstInvalidSessionId(string $sessionId): void
    {
        $requestUri = $this->getServerRequestReader()->requestUri();
        $userAgent = $this->getServerRequestReader()->httpUserAgent();
        log_warning(
            'Invalid php session id'
            . composeSuffix(
                [
                    session_name() => $sessionId,
                    'request uri' => $requestUri,
                    'reaction' => $this->cfg()->get('core->app->session->invalidIdReaction'),
                    'user agent' => $userAgent,
                ]
            )
        );
        if ($this->cfg()->get('core->app->session->invalidIdReaction') === Constants\PhpSession::IIR_RESET) {
            unset($_COOKIE[session_name()]); // JIC
            $this->startPhpSession();
            log_error('Reset session started with new id' . composeSuffix([session_name() => session_id()]));
            $this->createApplicationRedirector()->redirectToSelf();
        } else {
            $this->createApplicationRedirector()->badRequest();
        }
    }

    /**
     * @return bool
     */
    public function startPhpSession(): bool
    {
        /**
         * IK (2022/05): How do we want to react in case, if session is already started?
         * Do we want to skip session option initialization?
         * Do we want to restart session? (close/start)
         * Do we want to init options, but don't restart session? <current choice>
         *
         * Why and when such scenario is possible? (SAM-10660)
         */

        ini_set("session.use_only_cookies", (int)$this->cfg()->get('core->app->session->useOnlyCookies'));
        ini_set("session.cookie_lifetime", 0); // cookie will expire at the end of the session
        ini_set("session.cookie_path", $this->cfg()->get('core->app->session->cookie->path'));
        ini_set("session.cookie_httponly", $this->cfg()->get('core->app->session->cookie->httpOnly'));
        $isSecure = $this->cfg()->get('core->app->session->cookie->secure')
            && $this->createSslAvailabilityChecker()->isEnabled();
        ini_set("session.cookie_secure", $isSecure);
        ini_set("session.cookie_samesite", $this->cfg()->get('core->app->session->cookie->sameSite'));
        ini_set("session.cookie_domain", $this->cfg()->get('core->app->session->cookie->domain'));
        $time = 2 * 60 * 60 * 24;
        ini_set("session.gc_maxlifetime", $time);
        ini_set("session.use_strict_mode", true);
        $this->logSessionSettings();

        if (session_status() === PHP_SESSION_ACTIVE) {
            log_debug("PHP Native session is already active" . composeSuffix([session_name() => session_id()]));
            return true;
        }

        // error suppression, because of PHP Error "Unable to clear session lock record", when memcached and many requests
        $success = @session_start();
        if (!$success) {
            $lastError = error_get_last() ?? [];
            session_write_close(); // IK: Idk why, I see others do (Zend_Session)
            log_error("Php native session cannot be started" . composeSuffix($lastError));
        }

        return $success;
    }

    /**
     * Output to support log environment settings of php native session
     */
    protected function logSessionSettings(): void
    {
        $this->getSupportLogger()->trace(
            message: static function (): string {
                $logData = [
                    'use_strict_mode' => ini_get("session.use_strict_mode"),
                    'use_only_cookies' => ini_get("session.use_only_cookies"),
                    'cookie_lifetime' => ini_get("session.cookie_lifetime"),
                    'cookie_path' => ini_get("session.cookie_path"),
                    'cookie_httponly' => ini_get("session.cookie_httponly"),
                    'cookie_secure' => ini_get("session.cookie_secure"),
                    'cookie_samesite' => ini_get("session.cookie_samesite"),
                    'cookie_domain' => ini_get("session.cookie_domain"),
                    'gc_maxlifetime' => ini_get("session.gc_maxlifetime"),
                ];
                return "Php native session settings" . composeSuffix($logData);
            },
            optionals: [ContentBuilder::OP_EDITOR_USER_INFO => 0]
        );
    }
}
