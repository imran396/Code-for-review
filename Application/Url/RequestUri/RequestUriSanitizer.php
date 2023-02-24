<?php
/**
 * SAM-4824: Encapsulate $_SERVER access
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 01, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\RequestUri;

use Sam\Core\Service\CustomizableClass;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Core\Url\BackPage\BackUrlPureParser;
use Sam\Core\Url\UrlParserAwareTrait;

/**
 * Class RequestUriParser
 * @package ${NAMESPACE}
 */
class RequestUriSanitizer extends CustomizableClass
{
    use BackUrlParserAwareTrait;
    use UrlParserAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Request uri should be either empty or should be started with single slash (rfc3986)
     * @param string $requestUri
     * @return string
     */
    public function sanitize(string $requestUri): string
    {
        $requestUri = trim($requestUri);
        if ($requestUri === '') {
            return $requestUri;
        }
        // Non empty request URI should be started with single slash (rfc3986)
        $requestUri = '/' . ltrim($requestUri, '/');
        $requestUri = $this->getUrlParser()->sanitize($requestUri);
        $requestUri = $this->sanitizeBackUrl($requestUri);
        return $requestUri;
    }

    /**
     * Sanitize back-page url, if it presents among query-string parameters of request uri
     * @param string $requestUri
     * @return string
     */
    protected function sanitizeBackUrl(string $requestUri): string
    {
        // Sanitize back-page url, if exists in parameters of query-string part
        $backUrlParser = $this->getBackUrlParser();
        $backUrl = BackUrlPureParser::new()->get($requestUri);
        if ($backUrl !== '') {
            $backUri = $backUrlParser->sanitize($backUrl);
            $requestUri = $backUrlParser->replace($requestUri, $backUri);
        }
        return $requestUri;
    }
}
