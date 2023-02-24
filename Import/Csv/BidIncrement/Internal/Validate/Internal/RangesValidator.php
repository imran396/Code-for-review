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

use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\BidIncrement\Internal\Dto\RowInput;
use Sam\Import\Csv\BidIncrement\Internal\Validate\Internal\RangesValidationResult as Result;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * This class contains methods for validating increments ranges
 *
 * Class RangesValidator
 * @package Sam\Import\Csv\BidIncrement\Internal\Validate\Internal
 */
class RangesValidator extends CustomizableClass
{
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
     * Validate increments ranges.
     * One of the ranges must start at zero and all ranges must be unique
     *
     * @param RowInput[] $dtos
     * @param int $systemAccountId
     * @return RangesValidationResult
     */
    public function validate(array $dtos, int $systemAccountId): Result
    {
        $this->getNumberFormatter()->construct($systemAccountId);
        $result = Result::new()->construct();
        if (
            $dtos
            && !$this->hasIncrementWithZeroAmount($dtos)
        ) {
            $result->addError(Result::ERR_ABSENT_ZERO_AMOUNT);
        }
        if ($this->hasDuplicates($dtos)) {
            $result->addError(Result::ERR_DUPLICATE_RANGE);
        }
        return $result;
    }

    /**
     * @param RowInput[] $dtos
     * @return bool
     */
    protected function hasIncrementWithZeroAmount(array $dtos): bool
    {
        foreach ($dtos as $dto) {
            $amount = $this->getNumberFormatter()->removeFormat($dto->amount);
            if ($amount !== '' && Floating::eq($amount, 0)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param RowInput[] $dtos
     * @return bool
     */
    protected function hasDuplicates(array $dtos): bool
    {
        $incrementAmounts = [];
        foreach ($dtos as $dto) {
            $amount = $this->getNumberFormatter()->removeFormat($dto->amount);
            if ($amount !== '') { //Is not an empty and non-acceptable number
                $incrementAmounts[] = (float)$amount;
            }
        }

        return count($incrementAmounts) !== count(array_unique($incrementAmounts, SORT_NUMERIC));
    }
}
