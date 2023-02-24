<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\MarkPosted\Single\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Action\MarkPosted\Single\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Settlement\Check\Action\MarkPosted\Single\Validate\SingleSettlementCheckPostedMarkingValidationResult as Result;

/**
 * Class SingleSettlementCheckPostedMarkingValidator
 * @package Sam\Settlement\Check
 */
class SingleSettlementCheckPostedMarkingValidator extends CustomizableClass
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
     * @return SingleSettlementCheckPostedMarkingValidationResult
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

        if ($settlementCheck->isPosted()) {
            $result->addError(Result::ERR_ALREADY_POSTED_ON);
        }

        if (!$settlementCheck->hasCheckNo()) {
            $result->addError(Result::ERR_EMPTY_CHECK_NO);
        }

        if (!$settlementCheck->isPrinted()) {
            $result->addError(Result::ERR_NOT_PRINTED_YET);
        }

        return $result;
    }

}
