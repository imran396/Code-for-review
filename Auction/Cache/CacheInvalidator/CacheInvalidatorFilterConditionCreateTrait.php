<?php
/**
 * SAM-6292: Move fields from auction_cache to auction_details_cache
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache\CacheInvalidator;


/**
 * Trait CacheInvalidatorFilterConditionCreateTrait
 * @package Sam\Auction\Cache\CacheInvalidator
 */
trait CacheInvalidatorFilterConditionCreateTrait
{
    protected ?CacheInvalidatorFilterCondition $cacheInvalidatorFilterCondition = null;

    /**
     * @return CacheInvalidatorFilterCondition
     */
    protected function createCacheInvalidatorFilterCondition(): CacheInvalidatorFilterCondition
    {
        return $this->cacheInvalidatorFilterCondition ?: CacheInvalidatorFilterCondition::new();
    }

    /**
     * @param CacheInvalidatorFilterCondition $cacheInvalidatorFilterCondition
     * @return static
     * @internal
     */
    public function setCacheInvalidatorFilterCondition(CacheInvalidatorFilterCondition $cacheInvalidatorFilterCondition): static
    {
        $this->cacheInvalidatorFilterCondition = $cacheInvalidatorFilterCondition;
        return $this;
    }
}
