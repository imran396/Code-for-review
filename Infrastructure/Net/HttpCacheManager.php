<?php
/**
 * SAM-7972: Refactor \Net_Helper
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Net;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Contains methods for working with the HTTP cache headers
 *
 * Class HttpCacheManager
 * @package Sam\Infrastructure\Net
 */
class HttpCacheManager extends CustomizableClass
{
    use ServerRequestReaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Sends headers for conditional GET
     * Use this function before sending dynamic content.
     * Pass a last-modified timestamp.
     * The function will pass header parameters to the browser
     * (last-modified and ETag) and if the dynamic content was
     * not modified an "HTTP/1.0 304 Not Modified" header and exit
     *
     * @param int $modificationTimestamp
     */
    public function sendHeadersAndExitIfNotModified(int $modificationTimestamp): void
    {
        $this->sendLastModifiedHeaders($modificationTimestamp);
        if (!$this->isModified($modificationTimestamp)) {
            $this->sendNotModifiedHeader();
            exit(Constants\Cli::EXIT_SUCCESS);
        }
    }

    /**
     * Send Last-Modified and Etag headers
     *
     * @param int $modificationTimestamp
     */
    protected function sendLastModifiedHeaders(int $modificationTimestamp): void
    {
        $lastModified = $this->makeLastModified($modificationTimestamp);
        header("Last-Modified: $lastModified");
        header(sprintf('Etag: "%s"', md5($lastModified)));
    }

    /**
     * Use if nothing has changed since last request
     */
    protected function sendNotModifiedHeader(): void
    {
        // Nothing has changed since their last request - serve a 304 and exit
        header('HTTP/1.1 304 Not Modified');
    }

    /**
     * Check if anything has changed since the last request
     *
     * @param int $modificationTimestamp
     * @return bool
     */
    protected function isModified(int $modificationTimestamp): bool
    {
        $httpIfModifiedSince = stripslashes($this->getServerRequestReader()->httpIfModifiedSince());
        $httpIfNoneMatch = stripslashes($this->getServerRequestReader()->httpIfNoneMatch());
        // See if the client has provided the required headers
        if ($httpIfModifiedSince === '' && $httpIfNoneMatch === '') {
            return true;
        }
        $lastModified = $this->makeLastModified($modificationTimestamp);
        // At least one of the headers is there - check them
        if ($httpIfModifiedSince && $httpIfModifiedSince !== $lastModified) {
            return true; // if-modified-since is there but doesn't match
        }
        $etag = sprintf('"%s"', md5($lastModified));
        if ($httpIfNoneMatch && $httpIfNoneMatch !== $etag) {
            return true; // etag is there but doesn't match
        }
        return false;
    }

    /**
     * @param int $modificationTimestamp
     * @return string
     */
    protected function makeLastModified(int $modificationTimestamp): string
    {
        $lastModified = substr(date('r', $modificationTimestamp), 0, -5) . 'GMT';
        return $lastModified;
    }
}
