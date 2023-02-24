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

namespace Sam\Settlement\Check\Action\ApplyPayment\Single\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\ApplyPayment\Single\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Settlement\Check\Action\ApplyPayment\Single\Validate\Result\SingleSettlementCheckPaymentApplyingValidationResult as Result;
use SettlementCheck;

/**
 * Class SettlementCheckPaymentApplyingValidator
 * @package Sam\Settlement\Check
 */
class SingleSettlementCheckPaymentApplyingValidator extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $settlementCheckId null leads to unknown check id error
     * @param bool $isReadOnlyDb
     * @return Result
     */
    public function validate(?int $settlementCheckId, bool $isReadOnlyDb = false): Result
    {
        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();

        if (!$settlementCheckId) {
            return $result->addError(Result::ERR_UNKNOWN_ID);
        }

        $settlementCheck = $dataProvider->loadSettlementCheck($settlementCheckId, $isReadOnlyDb);
        if (!$settlementCheck) {
            return $result->addError(Result::ERR_CHECK_NOT_FOUND);
        }

        return $this->validateCheck($settlementCheck);
    }

    /**
     * Validate the existing settlement check entity for availability of "Apply as payment" action.
     * @param SettlementCheck $settlementCheck
     * @return Result
     */
    public function validateCheck(SettlementCheck $settlementCheck): Result
    {
        $result = Result::new()->construct();

        if ($settlementCheck->hasPayment()) {
            $result->addError(Result::ERR_ALREADY_PAYMENT_APPLIED);
        }

        if ($settlementCheck->isVoided()) {
            $result->addError(Result::ERR_ALREADY_VOIDED);
        }

        return $result;
    }

}
