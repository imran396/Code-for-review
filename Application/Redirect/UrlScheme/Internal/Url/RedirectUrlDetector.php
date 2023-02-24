<?php
/**
 * Pure service that detects url for redirection.
 *
 * SAM-9507: Move url scheme redirection to controller layer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Redirect\UrlScheme\Internal\Url;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;

/**
 * Class UrlMaker
 * @package
 */
class RedirectUrlDetector extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $url
     * @param bool $isSslEnable
     * @return string
     */
    public function detect(string $url, bool $isSslEnable): string
    {
        $urlParser = UrlParser::new();
        $isHttpsScheme = $urlParser->hasHttpsScheme($url);
        if ($isHttpsScheme) {
            if (!$isSslEnable) {
                $url = $urlParser->replaceScheme($url, 'http');
                log_debug('Ssl disabled for main account - redirect to http' . composeSuffix(['url' => $url]));
                return $url;
            }
        } elseif ($isSslEnable) {
            $url = $urlParser->replaceScheme($url, 'https');
            log_debug('Ssl enabled for main account - redirect to https' . composeSuffix(['url' => $url]));
            return $url;
        }
        return '';
    }

}
