<?php
/**
 * SAM-7972: Refactor \Net_Helper
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Net;

/**
 * Trait HttpCacheManagerCreateTrait
 * @package Sam\Infrastructure\Net
 */
trait HttpCacheManagerCreateTrait
{
    protected ?HttpCacheManager $httpCacheManager = null;

    /**
     * @return HttpCacheManager
     */
    protected function createHttpCacheManager(): HttpCacheManager
    {
        return $this->httpCacheManager ?: HttpCacheManager::new();
    }

    /**
     * @param HttpCacheManager $httpCacheManager
     * @return static
     * @internal
     */
    public function setHttpCacheManager(HttpCacheManager $httpCacheManager): static
    {
        $this->httpCacheManager = $httpCacheManager;
        return $this;
    }
}
