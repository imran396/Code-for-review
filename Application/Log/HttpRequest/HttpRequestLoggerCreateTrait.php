<?php
/**
 * SAM-5420: Http request info logger
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/10/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Log\HttpRequest;

/**
 * Class HttpRequestLoggerCreateTrait
 */
trait HttpRequestLoggerCreateTrait
{
    /** @var HttpRequestLogger|null */
    protected ?HttpRequestLogger $httpRequestLogger = null;

    /**
     * @return HttpRequestLogger
     */
    protected function createHttpRequestLogger(): HttpRequestLogger
    {
        return $this->httpRequestLogger ?: HttpRequestLogger::new();
    }

    /**
     * @param HttpRequestLogger $httpRequestLogger
     * @return static
     * @internal
     */
    public function setHttpRequestLogger(HttpRequestLogger $httpRequestLogger): static
    {
        $this->httpRequestLogger = $httpRequestLogger;
        return $this;
    }
}
