<?php
/**
 * SAM-5788: Adjust proxy.php script
 * SAM-7980: Refactor proxy.php
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\CrossDomainAjaxRequestProxy\Internal;

/**
 * Trait CacheManagerCreateTrait
 * @package Sam\Application\Controller\Responsive\CrossDomainAjaxRequestProxy\Internal
 * @internal
 */
trait CacheManagerCreateTrait
{
    protected ?CacheManager $cacheManager = null;

    /**
     * @return CacheManager
     */
    protected function createCacheManager(): CacheManager
    {
        return $this->cacheManager ?: CacheManager::new();
    }

    /**
     * @param CacheManager $cacheManager
     * @return static
     * @internal
     */
    public function setCacheManager(CacheManager $cacheManager): static
    {
        $this->cacheManager = $cacheManager;
        return $this;
    }
}
