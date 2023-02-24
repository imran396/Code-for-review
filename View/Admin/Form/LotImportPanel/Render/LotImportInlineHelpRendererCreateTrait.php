<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 23, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotImportPanel\Render;

/**
 * Trait LotImportInlineHelpRendererCreateTrait
 * @package Sam\View\Admin\Form\LotImportPanel\Render
 */
trait LotImportInlineHelpRendererCreateTrait
{
    protected ?LotImportInlineHelpRenderer $lotImportInlineHelpRenderer = null;

    /**
     * @return LotImportInlineHelpRenderer
     */
    protected function createLotImportInlineHelpRenderer(): LotImportInlineHelpRenderer
    {
        return $this->lotImportInlineHelpRenderer ?: LotImportInlineHelpRenderer::new();
    }

    /**
     * @param LotImportInlineHelpRenderer $lotImportInlineHelpRenderer
     * @return static
     * @internal
     */
    public function setLotImportInlineHelpRenderer(LotImportInlineHelpRenderer $lotImportInlineHelpRenderer): static
    {
        $this->lotImportInlineHelpRenderer = $lotImportInlineHelpRenderer;
        return $this;
    }
}
