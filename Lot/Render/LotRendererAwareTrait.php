<?php
/**
 * Trait for Lot Renderer
 *
 * SAM-4116: Lot Renderer class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 23, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Render;

/**
 * Trait LotRendererAwareTrait
 * @package Sam\Lot\Render
 */
trait LotRendererAwareTrait
{
    protected ?LotRendererInterface $lotRenderer = null;

    /**
     * @return LotRendererInterface
     */
    protected function getLotRenderer(): LotRendererInterface
    {
        if ($this->lotRenderer === null) {
            $this->lotRenderer = LotRenderer::new();
        }
        return $this->lotRenderer;
    }

    /**
     * @param LotRendererInterface $lotRenderer
     * @return static
     * @internal
     */
    public function setLotRenderer(LotRendererInterface $lotRenderer): static
    {
        $this->lotRenderer = $lotRenderer;
        return $this;
    }
}
