<?php
/**
 * SAM-11061: Stacked Tax. Invoice Management pages. Implement Stacked Tax Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Calculate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Tax\StackedTax\Definition\Calculate\Sliding\TaxDefinitionSlidingPureCalculator;
use Sam\Tax\StackedTax\Definition\Calculate\Tiered\TaxDefinitionTieredBpPureCalculator;
use Sam\Tax\StackedTax\Definition\Load\TaxDefinitionLoaderCreateTrait;
use Sam\Tax\StackedTax\Definition\Load\TaxDefinitionRangeLoaderCreateTrait;
use TaxDefinition;
use TaxSchema;

/**
 * Class StackedTaxCalculator
 * @package Sam\Tax\StackedTax\Calculate
 */
class StackedTaxCalculator extends CustomizableClass
{
    use TaxDefinitionLoaderCreateTrait;
    use TaxDefinitionRangeLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function calculate(float $amount, TaxSchema $taxSchema, bool $isReadOnlyDb = false): StackedTaxCalculationResult
    {
        $result = StackedTaxCalculationResult::new()->construct($taxSchema);
        $taxDefinitions = $this->createTaxDefinitionLoader()->loadForTaxSchema($taxSchema->Id, $isReadOnlyDb);
        foreach ($taxDefinitions as $taxDefinition) {
            $result->addTax(
                $taxDefinition,
                $this->calcByTaxDefinition($amount, $taxDefinition)
            );
        }
        return $result;
    }

    public function calculateTaxAmount(float $amount, TaxSchema $taxSchema, bool $isReadOnlyDb = false): float
    {
        return $this->calculate($amount, $taxSchema, $isReadOnlyDb)->getTaxAmount();
    }

    public function calcByTaxDefinition(float $amount, TaxDefinition $taxDefinition, bool $isReadOnlyDb = false): float
    {
        $taxDefinitionRangeLoader = $this->createTaxDefinitionRangeLoader();
        if ($taxDefinition->RangeCalculation === Constants\StackedTax::RCM_TIERED) {
            $taxDefinitionRanges = $taxDefinitionRangeLoader->loadTaxDefinitionRangesByAmount($taxDefinition->Id, $amount, $isReadOnlyDb);
            $taxAmount = TaxDefinitionTieredBpPureCalculator::new()->calculate($amount, $taxDefinitionRanges);
            if ($taxDefinition->TaxType === Constants\StackedTax::TT_EXEMPTION) {
                $taxAmount *= -1;
            }
            return $taxAmount;
        }

        $taxDefinitionRange = $taxDefinitionRangeLoader->loadTaxDefinitionRangeByAmount($taxDefinition->Id, $amount, $isReadOnlyDb);
        if (!$taxDefinitionRange) {
            log_error('Tax Definition Range record cannot be found' . composeSuffix(['tdef' => $taxDefinition->Id, 'amount' => $amount]));
            return 0.;
        }

        $taxAmount = TaxDefinitionSlidingPureCalculator::new()->calculate($amount, $taxDefinitionRange);
        if ($taxDefinition->TaxType === Constants\StackedTax::TT_EXEMPTION) {
            $taxAmount *= -1;
        }
        return $taxAmount;
    }
}
