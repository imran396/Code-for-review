<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\Printing\Single\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\Printing\Single\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Settlement\Check\Action\Printing\Single\Validate\Result\SingleSettlementCheckPrintingValidationResult as Result;
use SettlementCheck;

/**
 * Class SingleSettlementCheckPrintingValidator
 * @package Sam\Settlement\Check
 */
class SingleSettlementCheckPrintingValidator extends CustomizableClass
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
     * Validate the existing settlement check entity for availability of "Print" action on bulk printing (Settlement check list page).
     * @param int|null $settlementCheckId
     * @param bool $isReadOnlyDb
     * @param bool $onSinglePrint
     * @return Result
     */
    public function validate(?int $settlementCheckId, bool $isReadOnlyDb = false, bool $onSinglePrint = false): Result
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

        return $this->validateCheck($settlementCheck, $onSinglePrint);
    }

    /**
     * Validate the existing settlement check entity for availability of "Print" action.
     * @param SettlementCheck $settlementCheck
     * @param bool $onSinglePrint
     * @return Result
     */
    public function validateCheck(SettlementCheck $settlementCheck, bool $onSinglePrint = true): Result
    {
        $result = Result::new()->construct();

        if ($onSinglePrint) {
            if (!$settlementCheck->hasCheckNo()) {
                $result->addError(Result::ERR_EMPTY_CHECK_NO);
            }
        } else { // multiple checks print
            /** @noinspection NestedPositiveIfStatementsInspection */
            if ($settlementCheck->hasCheckNo()) {
                $result->addError(Result::ERR_HAS_CHECK_NO_ON_MULTI_PRINT);
            }
        }

        if ($settlementCheck->isPrinted()) {
            $result->addError(Result::ERR_ALREADY_PRINTED_ON);
        }

        if ($settlementCheck->isVoided()) {
            $result->addError(Result::ERR_ALREADY_VOIDED_ON);
        }

        return $result;
    }
}
