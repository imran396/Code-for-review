<?php
/**
 * SAM-8107: Issues related to Validation and Values of Buyer's Premium
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Validate\Internal\BuyersPremium\Internal\Validate;

use Sam\BuyersPremium\Csv\Parse\BuyersPremiumCsvParserCreateTrait;
use Sam\Core\RangeTable\BuyersPremium\Validate\BuyersPremiumRangesValidatorCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Validate\Internal\BuyersPremium\BuyersPremiumValidationInput as Input;
use Sam\EntityMaker\Base\Validate\Internal\BuyersPremium\Internal\Validate\BuyersPremiumValidationResult as Result;

/**
 * Class BuyersPremiumValidator
 * @package Sam\EntityMaker\Base\Validate\Internal\BuyersPremium
 */
class BuyersPremiumValidator extends CustomizableClass
{
    use BuyersPremiumCsvParserCreateTrait;
    use BuyersPremiumRangesValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(Input $input): Result
    {
        $result = Result::new()->construct();
        if (
            $input->mode->isCsv()
            && (string)$input->buyersPremiumString === ''
        ) {
            return $result;
        }

        if ($input->mode->isCsv()) {
            $csvParser = $this->createBuyersPremiumCsvParser();
            $isSyntaxCorrect = $csvParser->validateSyntax($input->buyersPremiumString);
            if (!$isSyntaxCorrect) {
                return $result->addError(Result::ERR_WRONG_SYNTAX, $csvParser->errorMessage());
            }
            $buyersPremiumDataRows = $csvParser->parse($input->buyersPremiumString, $input->entityContextAccountId);
        } else {
            $buyersPremiumDataRows = (array)$input->buyersPremiumDataRows;
        }

        $rangesValidationResult = $this->createBuyersPremiumRangesValidator()->validate($buyersPremiumDataRows);
        if ($rangesValidationResult->hasError()) {
            return $result->addRangesError($rangesValidationResult);
        }

        return $result;
    }

}
