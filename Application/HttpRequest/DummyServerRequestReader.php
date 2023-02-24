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

use Sam\Core\Service\Dummy\DummyServiceTrait;

/**
 * Class DummyServerRequestReader
 * @package Sam\Application\HttpRequest
 */
class DummyServerRequestReader extends ServerRequestReader
{
    use DummyServiceTrait;

    public const REMOTE_PORT = 1234;
    public const SERVER_PORT = 2345;

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
        return __FUNCTION__;
    }

    /**
     * Return current request full url, including query string parameters
     * @return string
     */
    public function currentUrl(): string
    {
        return __FUNCTION__;
    }

    /**
     * Return current request url WITHOUT query string parameters
     * @return string
     */
    public function cleanUrl(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return string
     */
    public function hostUrl(): string
    {
        return __FUNCTION__;
    }

    /**
     * Detect scheme for current web request
     * @return string
     */
    public function scheme(): string
    {
        return __FUNCTION__;
    }

    /**
     * Check if url has https scheme
     * @return bool
     */
    public function isHttpsScheme(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isGet(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function scriptFilename(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return string
     */
    public function scriptName(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return bool
     */
    public function isHttps(): bool
    {
        return true;
    }

    /**
     * @return int|null
     */
    public function serverPort(): ?int
    {
        return self::SERVER_PORT;
    }

    /**
     * @return string
     */
    public function serverName(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return string
     */
    public function serverAddr(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return string
     */
    public function queryString(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return string
     */
    public function httpHost(): string
    {
        return __FUNCTION__;
    }

    /**
     * Write to $_SERVER['HTTP_HOST'] global array
     * @param string $httpHost
     * @return $this
     */
    public function writeHttpHost(string $httpHost): ServerRequestReader
    {
        return $this;
    }

    /**
     * @return string
     */
    public function pathInfo(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return string
     */
    public function requestMethod(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return string
     */
    public function remoteAddr(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return int|null
     */
    public function remotePort(): ?int
    {
        return self::REMOTE_PORT;
    }

    /**
     * @return string
     */
    public function httpXUser(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return string
     */
    public function httpXPassword(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return string
     */
    public function httpReferer(): string
    {
        return __FUNCTION__;
    }

    public function httpOrigin(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return string
     */
    public function httpUserAgent(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return int|null
     */
    public function contentLength(): ?int
    {
        return 0;
    }

    /**
     * @return string
     */
    public function httpIfModifiedSince(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return string
     */
    public function httpIfNoneMatch(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return string
     */
    public function httpAcceptEncoding(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return string
     */
    public function serverProtocol(): string
    {
        return __FUNCTION__;
    }

    /**
     * @return string
     */
    public function httpXRequestedWith(): string
    {
        return __FUNCTION__;
    }

    /**
     * @param string $key
     * @return string
     */
    public function readServerValueByKey(string $key): string
    {
        return $this->toString(func_get_args());
    }

    /**
     * Checks, if it is QCodo's AJAX request
     * @return bool
     */
    public function isAjaxRequest(): bool
    {
        return false;
    }
}
