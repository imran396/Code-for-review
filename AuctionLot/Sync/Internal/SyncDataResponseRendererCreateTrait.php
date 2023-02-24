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
 * Trait SyncDataResponseRendererCreateTrait
 * @package Sam\AuctionLot\Sync\Internal
 */
trait SyncDataResponseRendererCreateTrait
{
    protected ?SyncDataResponseRenderer $syncDataResponseRenderer = null;

    /**
     * @return SyncDataResponseRenderer
     */
    protected function createSyncDataResponseRenderer(): SyncDataResponseRenderer
    {
        return $this->syncDataResponseRenderer ?: SyncDataResponseRenderer::new();
    }

    /**
     * @param SyncDataResponseRenderer $syncDataResponseRenderer
     * @return static
     * @internal
     */
    public function setSyncDataResponseRenderer(SyncDataResponseRenderer $syncDataResponseRenderer): static
    {
        $this->syncDataResponseRenderer = $syncDataResponseRenderer;
        return $this;
    }
}
