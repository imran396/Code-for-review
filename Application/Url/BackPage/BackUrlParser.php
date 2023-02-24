<?php
/**
 * Can add and remove back-page url to query string ("url" key)
 * Can sanitize back-page url value
 *
 * SAM-5305: Back-page url Parser
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           7/17/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\BackPage;

use Sam\Core\Service\CustomizableClass;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Url\BackPage\BackUrlPureParser;
use Sam\Core\Url\UrlParser;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Security\Ssl\Feature\SslAvailabilityCheckerCreateTrait;

/**
 * Class BackUrlParser
 * @package Sam\Application\Url
 */
class BackUrlParser extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use ServerRequestReaderAwareTrait;
    use SslAvailabilityCheckerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Sanitize back-page url and add this parameter to input url.
     * @param string $url
     * @param string|null $backUrl
     * @return string
     */
    public function replace(string $url, ?string $backUrl): string
    {
        $backUrl = $this->sanitize((string)$backUrl);
        return BackUrlPureParser::new()->replace($url, $backUrl);
    }

    /**
     * Filter (and validate) back link url
     * @param string $backUrl - non-string value will produce '/'
     * @return string
     */
    public function sanitize(string $backUrl): string
    {
        if ($this->validate($backUrl)) {
            $backUrl = $this->normalizeUrlScheme($backUrl);
            $backUrl = UrlParser::new()->sanitize($backUrl);
        } else {
            $backUrl = '/';
        }
        return $backUrl;
    }

    /**
     * @param string $backUrl
     * @return string
     */
    private function normalizeUrlScheme(string $backUrl): string
    {
        $output = $backUrl;
        $urlParser = UrlParser::new();
        if ($urlParser->extractScheme($backUrl) !== '') {
            $scheme = $this->createSslAvailabilityChecker()->isEnabled() ? 'https' : 'http';
            $output = $urlParser->replaceScheme($backUrl, $scheme);
        }
        return $output;
    }

    /**
     * Check if passed url can be used as back-page url parameter.
     * @param string $backUrl
     * @return bool
     */
    private function validate(string $backUrl): bool
    {
        $httpHost = $this->detectHttpHost();
        $parts = parse_url($backUrl);
        $host = empty($parts['host']) ? '' : $parts['host'];
        $path = empty($parts['path']) ? '' : $parts['path'];
        if ($host === $httpHost) {
            return true;
        }

        if ($host === '') {
            if (preg_match('/^\//', $path)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    private function detectHttpHost(): string
    {
        $httpHost = $this->getServerRequestReader()->httpHost() ?: $this->cfg()->get('core->app->httpHost');
        return $httpHost;
    }
}
