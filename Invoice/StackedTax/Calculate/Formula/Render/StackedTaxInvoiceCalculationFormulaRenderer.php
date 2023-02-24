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

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Calculate\Formula\Descriptor\FormulaDescriptor;

/**
 * Class InvoiceCalculationFormulaRenderer
 * @package Sam\Invoice\Legacy\Calculate\Formula
 */
class StackedTaxInvoiceCalculationFormulaRenderer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function renderFormulaForLogWithBalanceDueIntroMessage(FormulaDescriptor $formulaDescriptor, array $logData = []): string
    {
        return "--- Below is Balance Due calculation formula" . composeSuffix($logData) . " ---\n"
            . $this->renderFormulaForLog($formulaDescriptor);
    }

    public function renderFormulaForLog(FormulaDescriptor $formulaDescriptor): string
    {
        $outputs = $this->flattenFormulaClarifications($formulaDescriptor);
        return implode("\n", $outputs);
    }

    protected function flattenFormulaClarifications(FormulaDescriptor $formulaDescriptor, int $nesting = 0): array
    {
        $outputs = [];
        $leftPad = str_repeat(' ', $nesting * 2);
        $outputs[] = $leftPad
            . sprintf(
                '%s[%s]: %s',
                $formulaDescriptor->key,
                $formulaDescriptor->value,
                $formulaDescriptor->formula
            );
        foreach ($formulaDescriptor->clarifications as $clarificationKey => $clarification) {
            if ($clarification instanceof FormulaDescriptor) {
                $results = $this->flattenFormulaClarifications($clarification, $nesting + 1);
                foreach ($results as $result) {
                    $outputs[] = $result;
                }
            } else {
                $leftPad = str_repeat(' ', ($nesting + 1) * 2);
                $outputs[] = $leftPad . sprintf('%s: %s', $clarificationKey, $clarification);
            }
        }
        return $outputs;
    }

}
