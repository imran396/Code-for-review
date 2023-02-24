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

/**
 * @package Sam\Application\Url\RequestUri
 */
trait RequestUriSanitizerCreateTrait
{
    /**
     * @var RequestUriSanitizer|null
     */
    protected ?RequestUriSanitizer $requestUriSanitizer = null;

    /**
     * @return RequestUriSanitizer
     */
    protected function createRequestUriSanitizer(): RequestUriSanitizer
    {
        return $this->requestUriSanitizer ?: RequestUriSanitizer::new();
    }

    /**
     * @param RequestUriSanitizer $requestUriSanitizer
     * @return $this
     * @internal
     */
    public function setRequestUriSanitizer(RequestUriSanitizer $requestUriSanitizer): static
    {
        $this->requestUriSanitizer = $requestUriSanitizer;
        return $this;
    }
}
