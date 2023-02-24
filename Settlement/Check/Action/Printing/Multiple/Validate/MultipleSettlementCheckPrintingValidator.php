<?php
/**
 * SAM-9890: Check Printing for Settlements: Implementation of printing content rendering
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\Printing\Multiple\Validate;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\Printing\Multiple\Validate\Result\MultipleSettlementCheckPrintingValidationResult as Result;
use Sam\Settlement\Check\Action\Printing\Single\Validate\SingleSettlementCheckPrintingValidatorCreateTrait;

/**
 * Class MultipleSettlementCheckPrintingValidator
 * @package Sam\Settlement\Check
 */
class MultipleSettlementCheckPrintingValidator extends CustomizableClass
{
    use SingleSettlementCheckPrintingValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(array $settlementCheckIds, string $startingCheckNo, bool $isReadOnlyDb = false): Result
    {
        $normalizedStartingCheckNo = Cast::toInt($startingCheckNo, Constants\Type::F_INT_POSITIVE_OR_ZERO);
        if (
            $normalizedStartingCheckNo === null
            || $normalizedStartingCheckNo < 0
        ) {
            return Result::new()
                ->construct()
                ->addError(Result::ERR_INVALID_STARTING_CHECK_NO);
        }

        return $this->validateMultipleSettlementChecks($settlementCheckIds, $isReadOnlyDb);
    }

    protected function validateMultipleSettlementChecks(array $settlementCheckIds, bool $isReadOnlyDb = false): Result
    {
        $result = Result::new()->construct();
        $singleSettlementCheckValidator = $this->createSingleSettlementCheckPrintingValidator();
        foreach ($settlementCheckIds as $settlementCheckId) {
            $singleResult = $singleSettlementCheckValidator->validate($settlementCheckId, $isReadOnlyDb);
            if ($singleResult->hasError()) {
                $result->addErrorBySingleResult($singleResult, $settlementCheckId);
            }
        }
        return $result;
    }
}
