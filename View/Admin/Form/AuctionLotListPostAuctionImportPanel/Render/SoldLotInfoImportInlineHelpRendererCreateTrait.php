<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListPostAuctionImportPanel\Render;

/**
 * Trait SoldLotInfoImportInlineHelpRendererCreateTrait
 * @package Sam\View\Admin\Form\AuctionLotListPostAuctionImportPanel\Render
 */
trait SoldLotInfoImportInlineHelpRendererCreateTrait
{
    protected ?SoldLotInfoImportInlineHelpRenderer $soldLotInfoImportInlineHelpRenderer = null;

    /**
     * @return SoldLotInfoImportInlineHelpRenderer
     */
    protected function createSoldLotInfoImportInlineHelpRenderer(): SoldLotInfoImportInlineHelpRenderer
    {
        return $this->soldLotInfoImportInlineHelpRenderer ?: SoldLotInfoImportInlineHelpRenderer::new();
    }

    /**
     * @param SoldLotInfoImportInlineHelpRenderer $soldLotInfoImportInlineHelpRenderer
     * @return static
     * @internal
     */
    public function setSoldLotInfoImportInlineHelpRenderer(SoldLotInfoImportInlineHelpRenderer $soldLotInfoImportInlineHelpRenderer): static
    {
        $this->soldLotInfoImportInlineHelpRenderer = $soldLotInfoImportInlineHelpRenderer;
        return $this;
    }
}
