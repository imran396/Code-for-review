<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Internal;

/**
 * Trait SyncDataCacheCreateTrait
 * @package Sam\AuctionLot\Sync\Internal
 */
trait SyncDataCacheCreateTrait
{
    protected ?SyncDataCache $syncDataCache = null;

    /**
     * @return SyncDataCache
     */
    protected function createSyncDataCache(): SyncDataCache
    {
        return $this->syncDataCache ?: SyncDataCache::new();
    }

    /**
     * @param SyncDataCache $syncDataCache
     * @return static
     * @internal
     */
    public function setSyncDataCache(SyncDataCache $syncDataCache): static
    {
        $this->syncDataCache = $syncDataCache;
        return $this;
    }
}
