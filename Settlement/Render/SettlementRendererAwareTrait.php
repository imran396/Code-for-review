<?php
/**
 * Trait for Settlement Renderer
 *
 * SAM-4111: Invoice and settlement fields renderers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 21, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Render;

/**
 * Trait SettlementRendererAwareTrait
 * @package Sam\Settlement\Render
 */
trait SettlementRendererAwareTrait
{
    protected ?SettlementRenderer $settlementRenderer = null;

    /**
     * @return SettlementRenderer
     */
    protected function getSettlementRenderer(): SettlementRenderer
    {
        if ($this->settlementRenderer === null) {
            $this->settlementRenderer = SettlementRenderer::new();
        }
        return $this->settlementRenderer;
    }

    /**
     * @param SettlementRenderer $settlementRenderer
     * @return static
     * @internal
     */
    public function setSettlementRenderer(SettlementRenderer $settlementRenderer): static
    {
        $this->settlementRenderer = $settlementRenderer;
        return $this;
    }
}
