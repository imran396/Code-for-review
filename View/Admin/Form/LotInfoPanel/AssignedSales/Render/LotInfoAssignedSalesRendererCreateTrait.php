<?php
/**
 * SAM-6717: Refactor assigned sales label at Lot Item Edit page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 25, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoPanel\AssignedSales\Render;


/**
 * Trait AssignedSalesRendererCreateTrait
 * @package Sam\View\Admin\Form\LotInfoPanel\AssignedSales\Render
 */
trait LotInfoAssignedSalesRendererCreateTrait
{
    protected ?LotInfoAssignedSalesRenderer $lotInfoAssignedSalesRenderer = null;

    /**
     * @return LotInfoAssignedSalesRenderer
     */
    protected function createLotInfoAssignedSalesRenderer(): LotInfoAssignedSalesRenderer
    {
        return $this->lotInfoAssignedSalesRenderer ?: LotInfoAssignedSalesRenderer::new();
    }

    /**
     * @param LotInfoAssignedSalesRenderer $renderer
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    protected function setLotInfoAssignedSalesRenderer(LotInfoAssignedSalesRenderer $renderer): static
    {
        $this->lotInfoAssignedSalesRenderer = $renderer;
        return $this;
    }
}
