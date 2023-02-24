<?php
/**
 * SAM-9424: Disabled 'Display lot quantity in catalog' does not make effect at Compact view
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Quantity;

/**
 * Trait LotQuantityRendererCreateTrait
 * @package
 */
trait LotQuantityRendererCreateTrait
{
    protected ?LotQuantityRenderer $lotQuantityRenderer = null;

    /**
     * @return LotQuantityRenderer
     */
    protected function createLotQuantityRenderer(): LotQuantityRenderer
    {
        return $this->lotQuantityRenderer ?: LotQuantityRenderer::new();
    }

    /**
     * @param LotQuantityRenderer $lotQuantityRenderer
     * @return $this
     * @internal
     */
    public function setLotQuantityRenderer(LotQuantityRenderer $lotQuantityRenderer): static
    {
        $this->lotQuantityRenderer = $lotQuantityRenderer;
        return $this;
    }
}
