<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\ApplyPayment\Multiple\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\ApplyPayment\Multiple\Validate\MultipleSettlementCheckPaymentApplyingValidationResult as Result;
use Sam\Settlement\Check\Action\ApplyPayment\Single\Validate\SingleSettlementCheckPaymentApplyingValidatorCreateTrait;

/**
 * Class MultipleSettlementCheckPaymentApplyingValidator
 * @package Sam\Settlement\Check
 */
class MultipleSettlementCheckPaymentApplyingValidator extends CustomizableClass
{
    use SingleSettlementCheckPaymentApplyingValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $settlementCheckIds
     * @param bool $isReadOnlyDb
     * @return Result
     */
    public function validate(array $settlementCheckIds, bool $isReadOnlyDb = false): Result
    {
        $result = Result::new()->construct();

        $paymentApplyingValidator = $this->createSingleSettlementCheckPaymentApplyingValidator();

        foreach ($settlementCheckIds as $settlementCheckId) {
            $singleResult = $paymentApplyingValidator->validate($settlementCheckId, $isReadOnlyDb);
            if ($singleResult->hasError()) {
                $result->addError($settlementCheckId, $singleResult);
            }
        }

        return $result;
    }

}
