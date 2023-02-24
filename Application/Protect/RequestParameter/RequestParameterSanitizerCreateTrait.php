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

/**
 * Trait RequestParameterSanitizerCreateTrait
 * @package Sam\Application\Protect\RequestParameter
 */
trait RequestParameterSanitizerCreateTrait
{
    /**
     * @var RequestParameterSanitizer|null
     */
    protected ?RequestParameterSanitizer $requestParameterSanitizer = null;

    /**
     * @return RequestParameterSanitizer
     */
    protected function createRequestParameterSanitizer(): RequestParameterSanitizer
    {
        return $this->requestParameterSanitizer ?: RequestParameterSanitizer::new();
    }

    /**
     * @param RequestParameterSanitizer $requestParameterSanitizer
     * @return static
     * @internal
     */
    public function setRequestParameterSanitizer(RequestParameterSanitizer $requestParameterSanitizer): static
    {
        $this->requestParameterSanitizer = $requestParameterSanitizer;
        return $this;
    }
}
