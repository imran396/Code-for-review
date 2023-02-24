<?php
/**
 * SAM-10417: Stacked Tax
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Render;

/**
 * Trait StackedTaxRendererCreateTrait
 * @package Sam\Tax\StackedTax\Render
 */
trait StackedTaxRendererCreateTrait
{
    protected ?StackedTaxRenderer $stackedTaxRenderer = null;

    /**
     * @return StackedTaxRenderer
     */
    protected function createStackedTaxRenderer(): StackedTaxRenderer
    {
        return $this->stackedTaxRenderer ?: StackedTaxRenderer::new();
    }

    /**
     * @param StackedTaxRenderer $stackedTaxRenderer
     * @return $this
     * @internal
     */
    public function setStackedTaxRenderer(StackedTaxRenderer $stackedTaxRenderer): static
    {
        $this->stackedTaxRenderer = $stackedTaxRenderer;
        return $this;
    }
}
