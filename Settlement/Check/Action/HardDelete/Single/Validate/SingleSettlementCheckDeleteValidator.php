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

namespace Sam\Settlement\Check\Action\HardDelete\Single\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\HardDelete\Single\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Settlement\Check\Action\HardDelete\Single\Validate\Result\SingleSettlementCheckDeleteValidationResult as Result;
use SettlementCheck;

/**
 * Class SingleSettlementCheckDeleteValidator
 * @package Sam\Settlement\Check
 */
class SingleSettlementCheckDeleteValidator extends CustomizableClass
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
     * Validate the existing settlement check entity for availability of "Hard Delete" action.
     * @param SettlementCheck $settlementCheck
     * @return Result
     */
    public function validateCheck(SettlementCheck $settlementCheck): Result
    {
        $result = Result::new()->construct();

        if ($settlementCheck->hasCheckNo()) {
            $result->addError(Result::ERR_CHECK_NO_ASSIGNED);
        }

        if ($settlementCheck->isPrinted()) {
            $result->addError(Result::ERR_PRINTED_ON_ASSIGNED);
        }

        if ($settlementCheck->hasPayment()) {
            $result->addError(Result::ERR_PAYMENT_APPLIED);
        }

        return $result;
    }

}
