<?php
/**
 * SAM-9365: Refactor BidIncrementCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\BidIncrement\Internal\Validate\Internal;

use Sam\Bidding\BidIncrement\Validate\BidIncrementValidatorCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\BidIncrement\Internal\Dto\RowInput;
use Sam\Import\Csv\BidIncrement\Internal\Validate\Internal\RowValidationResult as Result;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class RowValidator
 * @package Sam\Import\Csv\BidIncrement\Internal\Validate\Internal
 */
class RowValidator extends CustomizableClass
{
    use BidIncrementValidatorCreateTrait;
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check range amount and increment value
     *
     * @param RowInput $rowInput
     * @param string $auctionType
     * @param int $systemAccountId
     * @return RowValidationResult
     */
    public function validate(RowInput $rowInput, string $auctionType, int $systemAccountId): Result
    {
        $result = Result::new()->construct();
        $numberFormatter = $this->getNumberFormatter()->construct($systemAccountId);
        $bidIncrementValidator = $this->createBidIncrementValidator();
        $bidIncrementValidator->setNumberFormatter($numberFormatter);

        $errorAmount = $bidIncrementValidator->validateAmount($rowInput->amount, null, null, null, $auctionType);
        if ($errorAmount) {
            $result->addInvalidAmountError(lcfirst($errorAmount));
        }

        $errorIncrement = $bidIncrementValidator->validateIncrement($rowInput->increment);
        if ($errorIncrement) {
            $result->addInvalidIncrementError(lcfirst($errorIncrement));
        }

        if ($numberFormatter->validateNumberFormat($rowInput->amount)->isValidNumberWithThousandSeparator()) {
            $result->addError(Result::ERR_AMOUNT_WITH_THOUSAND_SEPARATOR);
        }

        if ($numberFormatter->validateNumberFormat($rowInput->increment)->isValidNumberWithThousandSeparator()) {
            $result->addError(Result::ERR_INCREMENT_WITH_THOUSAND_SEPARATOR);
        }

        return $result;
    }
}
