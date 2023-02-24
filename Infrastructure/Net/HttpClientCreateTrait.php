<?php
/**
 * SAM-7963: Replace Net_Curl with GuzzleHttp
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Net;

/**
 * Trait HttpClientCreateTrait
 * @package Sam\Infrastructure\Net
 */
trait HttpClientCreateTrait
{
    protected ?HttpClient $httpClient = null;

    /**
     * @return HttpClient
     */
    protected function createHttpClient(): HttpClient
    {
        return $this->httpClient ?: HttpClient::new();
    }

    /**
     * @param HttpClient $httpClient
     * @return static
     * @internal
     */
    public function setHttpClient(HttpClient $httpClient): static
    {
        $this->httpClient = $httpClient;
        return $this;
    }
}
