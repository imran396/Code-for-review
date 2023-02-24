<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Validate\Internal\ValidateRow;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Import\Csv\PostAuction\Internal\Dto\RowInput;
use Sam\Import\Csv\PostAuction\Internal\Validate\Internal\ValidateRow\RowValidationResult as Result;

/**
 * Class RowValidator
 */
class RowValidator extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate single post auction row content
     *
     * @param RowInput $dto
     * @return RowValidationResult
     */
    public function validate(RowInput $dto): Result
    {
        $result = Result::new()->construct();
        $numberValidator = NumberValidator::new();

        if (!$dto->lotNoParsed->ok()) {
            $result->addError(Result::ERR_LOT_NUMBER_INVALID);
        }

        $isEmptyHammerPrice = $dto->hammerPrice === '';  // zero expected
        if (
            !$isEmptyHammerPrice
            && !$numberValidator->isRealPositiveOrZero($dto->hammerPrice)
        ) {
            $result->addError(Result::ERR_HAMMER_PRICE_INVALID);
        }

        if (
            $isEmptyHammerPrice
            && $dto->userInputDto->email
        ) {
            $result->addError(Result::ERR_HAMMER_PRICE_REQUIRED);
        }

        return $result;
    }
}
