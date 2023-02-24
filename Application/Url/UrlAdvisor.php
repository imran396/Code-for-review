<?php
/**
 * SAM-5138: Url Advisor class
 *
 * Logic depends on application and current request contexts.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url;

use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\ApplicationAwareTrait;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Url\UrlParser;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Security\Ssl\Feature\SslAvailabilityCheckerCreateTrait;

/**
 * Class UrlAdvisor
 * @package Sam\Application\HttpRequest
 */
class UrlAdvisor extends CustomizableClass
{
    use ApplicationAwareTrait;
    use BackUrlParserAwareTrait;
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
     * Add back link parameter
     *
     * @param string $url
     * @return string
     */
    public function addBackUrl(string $url): string
    {
        $backUrl = $this->getServerRequestReader()->requestUri();
        $url = $this->getBackUrlParser()->replace($url, $backUrl);
        return $url;
    }

    /**
     * Return full link (absolute url) for passed relative path, or schemeless, or full url
     *
     * @param string $url
     * @return string
     */
    public function completeFullUrl(string $url): string
    {
        $urlParser = UrlParser::new();
        $scheme = $urlParser->extractScheme($url) ?: $this->detectScheme();
        $host = $urlParser->extractHost($url);
        if (!$host) {
            $httpHost = $this->cfg()->get('core->app->httpHost');
            if ($this->cfg()->get('core->portal->enabled')) {
                $subDomain = $this->getApplication()->getSubDomain();
                if (empty($subDomain)) {
                    $httpHost = $this->getServerRequestReader()->httpHost() ?: $this->cfg()->get('core->app->httpHost');
                } else {
                    $baseDomain = str_replace('www.', '', $this->cfg()->get('core->app->httpHost'));
                    $httpHost = $subDomain . '.' . $baseDomain;
                }
            }
            $url = $scheme . '://' . $httpHost . $url;
        } elseif (!$scheme) {
            $url = $scheme . '://' . $url;
        }
        return $url;
    }

    /**
     * Complete url with main account host
     * @param string $url
     * @return string
     */
    public function completeFullUrlForMain(string $url): string
    {
        if (0 !== stripos($url, "http")) {
            $url = $this->detectScheme() . '://' . $this->cfg()->get('core->app->httpHost') . $url;
        }
        return $url;
    }

    /**
     * Determine url-scheme (protocol) by system parameters
     * @return string
     */
    public function detectScheme(): string
    {
        // Ssl Configuration should be get from main account
        $scheme = 'http';
        if ($this->createSslAvailabilityChecker()->isEnabled()) {
            $scheme = 'https';
        }
        return $scheme;
    }
}
