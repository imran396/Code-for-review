<?php
/**
 * SAM-5549 : Http request parameter sanitizer
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           30.11.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Application\Protect\RequestParameter;

use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\RequestParam\ParamFetcherForPostAwareTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class RequestParameterSanitizer
 * @package Sam\Application\Protect\RequestParameter
 */
class RequestParameterSanitizer extends CustomizableClass
{
    use BackUrlParserAwareTrait;
    use ParamFetcherForGetAwareTrait;
    use ParamFetcherForPostAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return void
     */
    public function sanitize(): void
    {
        $this->sanitizeBackUrl();
    }

    /**
     * Sanitize back-page url value and publish it to request global arrays
     * @return void
     */
    protected function sanitizeBackUrl(): void
    {
        if ($this->getParamFetcherForGet()->has(Constants\UrlParam::BACK_URL)) {
            $backUrlGet = $this->getParamFetcherForGet()->getString(Constants\UrlParam::BACK_URL);
            $_GET[Constants\UrlParam::BACK_URL] = $this->getBackUrlParser()->sanitize($backUrlGet);
        }
        if ($this->getParamFetcherForPost()->has(Constants\UrlParam::BACK_URL)) {
            $backUrlPost = $this->getParamFetcherForPost()->getString(Constants\UrlParam::BACK_URL);
            $_POST[Constants\UrlParam::BACK_URL] = $this->getBackUrlParser()->sanitize($backUrlPost);
        }
    }
}
