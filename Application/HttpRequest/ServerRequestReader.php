<?php
/**
 * SAM-9508: Server request and url building logic adjustments for v3-6
 * SAM-4824: Encapsulate $_SERVER access
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/23/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\HttpRequest;

use Sam\Application\HttpRequest\Internal\Url\UrlMaker;
use Sam\Application\HttpRequest\ProxiedFeature\ProxiedFeatureAvailabilityCheckerCreateTrait;
use Sam\Application\Url\RequestUri\RequestUriSanitizerCreateTrait;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class ServerRequestReader
 * @package Sam\Application\HttpRequest
 */
class ServerRequestReader extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use MemoryCacheManagerAwareTrait;
    use ProxiedFeatureAvailabilityCheckerCreateTrait;
    use RequestUriSanitizerCreateTrait;
    use ServerRequestAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Sanitize and return request uri from request data. Cache value in memory.
     * @return string
     */
    public function requestUri(): string
    {
        $fn = function () {
            $requestUriOriginal = $this->readServerValueByKey('REQUEST_URI');
            $requestUri = $this->createRequestUriSanitizer()->sanitize($requestUriOriginal);
            if ($requestUriOriginal !== $requestUri) {
                log_warning('Request URI needed sanitizing' . composeSuffix(['Original' => $requestUriOriginal, 'Sanitized' => $requestUri]));
            }
            return $requestUri;
        };
        $cacheKey = Constants\MemoryCache::SERVER_REQUEST_REQUEST_URI;
        $requestUri = $this->getMemoryCacheManager()->load($cacheKey, $fn);
        return $requestUri;
    }

    /**
     * Return current request full url, including query string parameters
     * @return string
     */
    public function currentUrl(): string
    {
        return $this->hostUrl() . $this->requestUri();
    }

    /**
     * Return current request url WITHOUT query string parameters
     * @return string
     */
    public function cleanUrl(): string
    {
        return UrlParser::new()->removeQueryString($this->currentUrl());
    }

    /**
     * @return string
     */
    public function hostUrl(): string
    {
        /**
         * Use "HTTP_HOST" server request parameter instead of "SERVER_NAME",
         * because of multiple names in nginx configuration of "server_name" directive,
         * then the first name becomes the primary server name.
         */
        return UrlMaker::new()->makeHostUrl(
            $this->scheme(),
            $this->httpHost(),
            $this->serverPort(),
            (bool)$this->cfg()->get('core->app->httpHostIgnoreServerPort')
        );
    }

    /**
     * Detect scheme for current web request
     * @return string
     */
    public function scheme(): string
    {
        $scheme = 'http';
        if ($this->isHttps()) {
            $scheme .= "s";
        }
        return $scheme;
    }

    /**
     * Detect https state of request.
     * @return bool
     */
    public function isHttps(): bool
    {
        if (
            strcasecmp('on', $this->readServerValueByKey('HTTPS')) === 0
            || strcasecmp('https', $this->readServerValueByKey('REQUEST_SCHEME')) === 0
            || strcasecmp('https', $this->readServerValueByKey('HTTP_SCHEME')) === 0
            || (
                $this->createProxiedFeatureAvailabilityChecker()->isEnabled()
                && strcasecmp('https', $this->readServerValueByKey('HTTP_X_FORWARDED_PROTO')) === 0
            )
        ) {
            return true;
        }

        return false;
    }

    /**
     * Check if url has https scheme
     * @return bool
     */
    public function isHttpsScheme(): bool
    {
        $isHttpsScheme = UrlParser::new()->hasHttpsScheme($this->currentUrl());
        return $isHttpsScheme;
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        $isPost = strtoupper($this->requestMethod()) === 'POST';
        return $isPost;
    }

    /**
     * @return bool
     */
    public function isGet(): bool
    {
        $isGet = strtoupper($this->requestMethod()) === 'GET';
        return $isGet;
    }

    /**
     * @return string
     */
    public function scriptFilename(): string
    {
        return $this->readServerValueByKey('SCRIPT_FILENAME');
    }

    /**
     * @return string
     */
    public function scriptName(): string
    {
        return $this->readServerValueByKey('SCRIPT_NAME');
    }

    /**
     * @return int|null
     */
    public function serverPort(): ?int
    {
        if ($this->createProxiedFeatureAvailabilityChecker()->isEnabled()) {
            $xForwardedPort = $this->readServerValueByKey('HTTP_X_FORWARDED_PORT');
            if ($xForwardedPort !== '') {
                return (int)$xForwardedPort;
            }

            $xForwardedHost = $this->readServerValueByKey('HTTP_X_FORWARDED_HOST');
            $urlParser = UrlParser::new();
            if ($urlParser->hasPort($xForwardedHost)) {
                return $urlParser->extractPort($xForwardedHost);
            }
        }

        return (int)$this->readServerValueByKey('SERVER_PORT') ?: null;
    }

    /**
     * @return string
     */
    public function serverName(): string
    {
        return $this->readServerValueByKey('SERVER_NAME');
    }

    /**
     * @return string
     */
    public function serverAddr(): string
    {
        return $this->readServerValueByKey('SERVER_ADDR');
    }

    /**
     * @return string
     */
    public function queryString(): string
    {
        $queryString = $this->readServerValueByKey('QUERY_STRING');
        $queryString = UrlParser::new()->normalizeQueryString($queryString);
        return $queryString;
    }

    /**
     * @return string
     */
    public function httpHost(): string
    {
        if ($this->createProxiedFeatureAvailabilityChecker()->isEnabled()) {
            $xForwardedHost = $this->readServerValueByKey('HTTP_X_FORWARDED_HOST');
            $xForwardedHost = preg_replace('/:\d+$/', '', $xForwardedHost);
            if ($xForwardedHost) {
                return $xForwardedHost;
            }
        }

        return $this->readServerValueByKey('HTTP_HOST');
    }

    /**
     * Write to $_SERVER['HTTP_HOST'] global array
     * @param string $httpHost
     * @return $this
     */
    public function writeHttpHost(string $httpHost): ServerRequestReader
    {
        $_SERVER['HTTP_HOST'] = trim($httpHost);
        return $this;
    }

    /**
     * @return string
     */
    public function pathInfo(): string
    {
        return $this->readServerValueByKey('PATH_INFO');
    }

    /**
     * @return string
     */
    public function requestMethod(): string
    {
        return $this->readServerValueByKey('REQUEST_METHOD');
    }

    /**
     * @return string
     */
    public function remoteAddr(): string
    {
        return $this->readServerValueByKey('REMOTE_ADDR');
    }

    /**
     * @return int|null
     */
    public function remotePort(): ?int
    {
        return (int)$this->readServerValueByKey('REMOTE_PORT') ?: null;
    }

    /**
     * @return string
     */
    public function httpXUser(): string
    {
        return $this->readServerValueByKey('HTTP_X_USER');
    }

    /**
     * @return string
     */
    public function httpXPassword(): string
    {
        return $this->readServerValueByKey('HTTP_X_PASSWORD');
    }

    /**
     * @return string
     */
    public function httpReferer(): string
    {
        return $this->readServerValueByKey('HTTP_REFERER');
    }

    public function httpOrigin(): string
    {
        return $this->readServerValueByKey('HTTP_ORIGIN');
    }

    /**
     * @return string
     */
    public function httpUserAgent(): string
    {
        return $this->readServerValueByKey('HTTP_USER_AGENT');
    }

    /**
     * @return int|null
     */
    public function contentLength(): ?int
    {
        return (int)$this->readServerValueByKey('CONTENT_LENGTH') ?: null;
    }

    /**
     * @return string
     */
    public function httpIfModifiedSince(): string
    {
        return $this->readServerValueByKey('HTTP_IF_MODIFIED_SINCE');
    }

    /**
     * @return string
     */
    public function httpIfNoneMatch(): string
    {
        return $this->readServerValueByKey('HTTP_IF_NONE_MATCH');
    }

    /**
     * @return string
     */
    public function httpAcceptEncoding(): string
    {
        return $this->readServerValueByKey('HTTP_ACCEPT_ENCODING');
    }

    public function httpAuthorization(): string
    {
        return $this->readServerValueByKey('HTTP_AUTHORIZATION');
    }

    /**
     * @return string
     */
    public function serverProtocol(): string
    {
        return $this->readServerValueByKey('SERVER_PROTOCOL');
    }

    /**
     * @return string
     */
    public function httpXRequestedWith(): string
    {
        return $this->readServerValueByKey('HTTP_X_REQUESTED_WITH');
    }

    /**
     * @param string $key
     * @return string
     */
    public function readServerValueByKey(string $key): string
    {
        $serverParams = $this->getServerRequest()->getServerParams();
        $serverValue = isset($serverParams[$key]) ? trim($serverParams[$key]) : '';
        return $serverValue;
    }

    /**
     * Checks, if it is QCodo's AJAX request
     * @return bool
     */
    public function isAjaxRequest(): bool
    {
        $key = Constants\Qform::FORM_CALL_TYPE;
        $post = $this->getServerRequest()->getParsedBody();
        $is = isset($post[$key]) && $post[$key] === "Ajax";
        return $is;
    }
}
