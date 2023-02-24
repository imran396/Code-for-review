<?php
/**
 * SAM-6349: Adjust the labels for bulk vs piecemeal lots.
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 13, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\BulkGroup\Render;

/**
 * Trait LotBulkGroupCompeteLabelRendererCreateTrait
 * @package Sam\AuctionLot\BulkGroup
 */
trait LotBulkGroupCompeteLabelRendererCreateTrait
{
    /**
     * @var LotBulkGroupCompeteLabelRenderer|null
     */
    protected ?LotBulkGroupCompeteLabelRenderer $lotBulkGroupCompeteLabelRenderer = null;

    /**
     * @return LotBulkGroupCompeteLabelRenderer
     */
    protected function createLotBulkGroupCompeteLabelRenderer(): LotBulkGroupCompeteLabelRenderer
    {
        return $this->lotBulkGroupCompeteLabelRenderer ?: LotBulkGroupCompeteLabelRenderer::new();
    }

    /**
     * @param LotBulkGroupCompeteLabelRenderer $lotBulkGroupCompeteLabelRenderer
     * @return $this
     * @internal
     */
    public function setLotBulkGroupCompeteLabelRenderer(LotBulkGroupCompeteLabelRenderer $lotBulkGroupCompeteLabelRenderer): static
    {
        $this->lotBulkGroupCompeteLabelRenderer = $lotBulkGroupCompeteLabelRenderer;
        return $this;
    }
}
