<?php
/**
 * Back-url completing service. It decides, keep back-page url full (absolute) or cut domain part to make it relative.
 * This service is pure from application layer dependencies or side-effects (UrlParser is pure too),
 * but it still operates with application concepts and values based on web request.
 * Since it is intended to be used in UrlBuilder module only, we locate it near in \Internal sub-namespace,
 * otherwise this would be candidate for \Sam\Core\Url namespace.
 *
 * SAM-5140: Url Builder class
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           10-13, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\BackUrl\Core;

use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;

/**
 * Class BackUrlAdjuster
 * @package Sam\Application\Url\Build
 */
class BackUrlPureAdjuster extends CustomizableClass
{
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
     * Expects a full url (with scheme and host) and transform it to relative view.
     *
     * @param string $url
     * @param Ui $ui current application UI type
     * @param string $domain schema-domain part of url from running web session
     * @return string
     */
    public function adjust(string $url, Ui $ui, string $domain = ''): string
    {
        if ($ui->isCli()) {
            return $url;
        }

        if ($ui->isWeb()) {
            return $this->transformToRelativeUrl($url, $domain);
        }

        return '';
    }

    /**
     * Detect server domain with scheme and
     * Convert absolute url to relative if scheme and hosts are the same for both input and server domain.
     *
     * @param string $url
     * @param string $domain
     * @return string
     */
    protected function transformToRelativeUrl(string $url, string $domain): string
    {
        $urlParser = $this->getUrlParser();
        $urlScheme = $urlParser->extractScheme($url);
        $urlHost = $urlParser->extractHost($url);
        if (
            empty($urlScheme)
            || empty($urlHost)
        ) {
            return $url;
        }

        $domainScheme = $urlParser->extractScheme($domain);
        $domainHost = $urlParser->extractHost($domain);
        if (
            $domainScheme === $urlScheme
            && $domainHost === $urlHost
        ) {
            $url = $urlParser->removeSchemeAndHost($url);
            return $url;
        }

        return $url;
    }

}
