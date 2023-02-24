<?php
/**
 * SAM-6499: Refactor Settlement Calculator module (2020 year)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Calculate\Internal\Load\Cache;


/**
 * Trait SettlementProcessingCacheAwareTrait
 * @package Sam\Settlement\Calculate\Internal\Load
 */
trait SettlementProcessingCacheAwareTrait
{
    protected ?SettlementProcessingCache $settlementProcessingCache = null;

    /**
     * @return SettlementProcessingCache
     */
    protected function getSettlementProcessingCache(): SettlementProcessingCache
    {
        if ($this->settlementProcessingCache === null) {
            $this->settlementProcessingCache = SettlementProcessingCache::new();
        }
        return $this->settlementProcessingCache;
    }

    /**
     * @param SettlementProcessingCache $settlementProcessingCache
     * @return static
     * @internal
     */
    public function setSettlementProcessingCache(SettlementProcessingCache $settlementProcessingCache): static
    {
        $this->settlementProcessingCache = $settlementProcessingCache;
        return $this;
    }
}
