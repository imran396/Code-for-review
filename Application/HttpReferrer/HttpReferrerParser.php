<?php
/**
 * SAM-4742: Http referrer host parser
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/26/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\HttpReferrer;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;

/**
 * Class HttpReferrerParser
 * @package Sam\Application\HttpReferrer
 */
class HttpReferrerParser extends CustomizableClass
{
    use UrlParserAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Parse http referrer to Entity->Referrer, Entity->ReferrerHost values
     * @param string $httpReferrer
     * @return array [string, string]
     */
    public function parse(string $httpReferrer): array
    {
        $referrer = trim($httpReferrer);
        $referrerHost = '';
        if ($referrer) {
            $referrerHost = $this->getReferrerHost($referrer);
        }
        return [$referrer, $referrerHost];
    }

    /**
     * @param string $referrer
     * @return string
     */
    protected function getReferrerHost(string $referrer): string
    {
        if (filter_var($referrer, FILTER_VALIDATE_URL) === false) {
            return '';
        }
        return $this->getUrlParser()->extractHost($referrer);
    }
}
