<?php
/**
 * Pure logic for extracting and adding back-page url parameter.
 *
 * SAM-5305: Back-page url Parser
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Url\BackPage;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;

/**
 * Class BackUrlPureParser
 * @package Sam\Core\Url\BackPage
 */
class BackUrlPureParser extends CustomizableClass
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
     * Read back-page url parameter value from url's query string.
     * @param string $url
     * @return string
     * #[Pure]
     */
    public function get(string $url): string
    {
        $backUrl = UrlParser::new()->extractParam($url, Constants\UrlParam::BACK_URL);
        return $backUrl;
    }

    /**
     * Remove back-page url parameter.
     * @param string $url
     * @return string
     * #[Pure]
     */
    public function remove(string $url): string
    {
        $url = UrlParser::new()->removeParams($url, [Constants\UrlParam::BACK_URL]);
        return $url;
    }

    /**
     * Add back-page url parameter to input url as it is.
     * @param string $url
     * @param string $backUrl
     * @return string
     * #[Pure]
     */
    public function replace(string $url, string $backUrl): string
    {
        if ($backUrl) {
            $url = UrlParser::new()->replaceParams($url, [Constants\UrlParam::BACK_URL => $backUrl]);
        }
        return $url;
    }

}
