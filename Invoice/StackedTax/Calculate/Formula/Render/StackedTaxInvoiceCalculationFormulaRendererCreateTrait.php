<?php
/**
 * SAM-10770: Explain invoice calculation formula in support log
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 11, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Calculate\Formula\Render;

/**
 * Trait InvoiceCalculationFormulaRendererCreateTrait
 * @package Sam\Invoice\Legacy\Calculate\Formula\Render
 */
trait StackedTaxInvoiceCalculationFormulaRendererCreateTrait
{
    protected ?StackedTaxInvoiceCalculationFormulaRenderer $stackedTaxInvoiceCalculationFormulaRenderer = null;

    /**
     * @return StackedTaxInvoiceCalculationFormulaRenderer
     */
    protected function createStackedTaxInvoiceCalculationFormulaRenderer(): StackedTaxInvoiceCalculationFormulaRenderer
    {
        return $this->stackedTaxInvoiceCalculationFormulaRenderer ?: StackedTaxInvoiceCalculationFormulaRenderer::new();
    }

    /**
     * @param StackedTaxInvoiceCalculationFormulaRenderer $stackedTaxInvoiceCalculationFormulaRenderer
     * @return $this
     * @internal
     */
    public function setStackedTaxInvoiceCalculationFormulaRenderer(StackedTaxInvoiceCalculationFormulaRenderer $stackedTaxInvoiceCalculationFormulaRenderer): static
    {
        $this->stackedTaxInvoiceCalculationFormulaRenderer = $stackedTaxInvoiceCalculationFormulaRenderer;
        return $this;
    }
}
