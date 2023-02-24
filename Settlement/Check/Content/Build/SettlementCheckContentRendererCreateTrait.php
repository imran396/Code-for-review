<?php
/**
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           11-03, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Build;

/**
 * Trait SettlementCheckContentBuilderCreateTrait
 * @package Sam\Settlement\Check
 */
trait SettlementCheckContentRendererCreateTrait
{
    protected ?SettlementCheckContentRenderer $settlementCheckContentRenderer = null;

    /**
     * @return SettlementCheckContentRenderer
     */
    protected function createSettlementCheckContentRenderer(): SettlementCheckContentRenderer
    {
        return $this->settlementCheckContentRenderer ?: SettlementCheckContentRenderer::new();
    }

    /**
     * @param SettlementCheckContentRenderer $settlementCheckContentRenderer
     * @return $this
     * @internal
     */
    public function setSettlementCheckContentRenderer(SettlementCheckContentRenderer $settlementCheckContentRenderer): static
    {
        $this->settlementCheckContentRenderer = $settlementCheckContentRenderer;
        return $this;
    }
}




