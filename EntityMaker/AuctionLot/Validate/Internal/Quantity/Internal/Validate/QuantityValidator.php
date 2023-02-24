<?php
/**
 * SAM-8005: Allow decimals in quantity
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Internal\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Internal\Validate\QuantityValidationInput as Input;
use Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Internal\Validate\QuantityValidationResult as Result;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class QuantityValidator
 * @package Sam\EntityMaker\AuctionLot\Validate\Internal\Quantity\Internal\Validate
 */
class QuantityValidator extends CustomizableClass
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

    public function validate(Input $input): Result
    {
        $result = Result::new()->construct();
        if ((string)$input->quantity === '') {
            return $result;
        }

        $quantity =
            $this->getNumberFormatter()
                ->construct($input->systemAccountId)
                ->removeFormat($input->quantity);

        if (!NumberValidator::new()->isRealPositive($quantity)) {
            $result->addError(Result::ERR_INVALID);
            return $result;
        }

        [$integerPart, $fractionalPart] = explode('.', $quantity . '.');
        $fractionalPart = rtrim($fractionalPart, '0');
        $integerPartLength = strlen($integerPart);
        $fractionalPartLength = strlen($fractionalPart);

        if ($integerPartLength + $fractionalPartLength > Constants\Lot::LOT_QUANTITY_MAX_PRECISION) {
            $result->addError(
                Result::ERR_INVALID_PRECISION,
                sprintf(Result::ERROR_MESSAGES[Result::ERR_INVALID_PRECISION], Constants\Lot::LOT_QUANTITY_MAX_PRECISION)
            );
            return $result;
        }

        if ($integerPartLength > Constants\Lot::LOT_QUANTITY_MAX_INTEGER_DIGITS) {
            $result->addError(
                Result::ERR_TOO_BIG,
                sprintf(Result::ERROR_MESSAGES[Result::ERR_TOO_BIG], Constants\Lot::LOT_QUANTITY_MAX_INTEGER_DIGITS)
            );
            return $result;
        }

        if ($fractionalPartLength > $input->quantityScale) {
            $result->addError(
                Result::ERR_INVALID_FRACTIONAL_PART_LENGTH,
                sprintf(Result::ERROR_MESSAGES[Result::ERR_INVALID_FRACTIONAL_PART_LENGTH], $input->quantityScale)
            );
        }
        return $result;
    }
}
