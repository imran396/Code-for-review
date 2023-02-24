<?php
/**
 * SAM-6376 : Lot bulk group drop-down rendering
 * https://bidpath.atlassian.net/browse/SAM-6376
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 07, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\BulkGroup\Dropdown\Render;

/**
 * Trait LotBulkGroupDropdownOptionRendererCreateTrait
 * @package Sam\AuctionLot\BulkGroup\Dropdown\Render
 */
trait LotBulkGroupDropdownOptionRendererCreateTrait
{
    /**
     * @var LotBulkGroupDropdownOptionRenderer|null
     */
    protected ?LotBulkGroupDropdownOptionRenderer $lotBulkGroupDropdownOptionRenderer = null;

    /**
     * @return LotBulkGroupDropdownOptionRenderer
     */
    protected function createLotBulkGroupDropdownOptionRenderer(): LotBulkGroupDropdownOptionRenderer
    {
        return $this->lotBulkGroupDropdownOptionRenderer ?: LotBulkGroupDropdownOptionRenderer::new();
    }

    /**
     * @param LotBulkGroupDropdownOptionRenderer $lotBulkGroupDropdownOptionRenderer
     * @return $this
     * @internal
     */
    public function setLotBulkGroupDropdownOptionRenderer(
        LotBulkGroupDropdownOptionRenderer $lotBulkGroupDropdownOptionRenderer
    ): static {
        $this->lotBulkGroupDropdownOptionRenderer = $lotBulkGroupDropdownOptionRenderer;
        return $this;
    }
}
