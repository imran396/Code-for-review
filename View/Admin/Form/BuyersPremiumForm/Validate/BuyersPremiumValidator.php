<?php
/**
 * SAM-8107: Issues related to Validation and Values of Buyer's Premium
 * SAM-9727: Refactor \Qform_BuyersPremiumHelper
 * SAM-10477: Reject assigning both BP rules on the same level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyersPremiumForm\Validate;

use Sam\BuyersPremium\Validate\BuyersPremiumExistenceCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\RangeTable\BuyersPremium\Validate\BuyersPremiumRangesValidatorCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\BuyersPremiumForm\Dto\BuyersPremiumDto;
use Sam\View\Admin\Form\BuyersPremiumForm\Validate\BuyersPremiumValidationResult as Result;

/**
 * Class BuyersPremiumValidator
 * @package Sam\View\Admin\Form\BuyersPremiumForm\Validate
 */
class BuyersPremiumValidator extends CustomizableClass
{
    use BuyersPremiumExistenceCheckerCreateTrait;
    use BuyersPremiumRangesValidatorCreateTrait;
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(BuyersPremiumDto $dto, int $accountId): Result
    {
        $result = Result::new()->construct();

        $isDefaultBp = in_array($dto->shortName, Constants\Auction::AUCTION_TYPES, true);
        if (!$isDefaultBp) {
            if ($dto->name === '') {
                $result->addError(Result::ERR_NAME_REQUIRED);
            }
            if ($dto->shortName === '') {
                $result->addError(Result::ERR_SHORT_NAME_REQUIRED);
            }
        }

        $existenceChecker = $this->createBuyersPremiumExistenceChecker();
        if (
            $dto->shortName
            && $existenceChecker->existCustomBp($dto->shortName, $accountId, (array)$dto->id)
        ) {
            $result->addError(Result::ERR_SHORT_NAME_EXIST);
        }

        if (!in_array($dto->calculationMethod, Constants\BuyersPremium::$rangeCalculations, true)) {
            $result->addError(Result::ERR_CALCULATION_METHOD_INVALID);
        }

        if ($dto->additionalBpInternet !== '') {
            $additionalBpInternet = $this->getNumberFormatter()->removeFormat($dto->additionalBpInternet, $accountId);
            if (!NumberValidator::new()->isRealPositiveOrZero($additionalBpInternet)) {
                $result->addError(Result::ERR_ADDITIONAL_BP_INTERNET_INVALID);
            }
        }

        $rangesValidationResult = $this->createBuyersPremiumRangesValidator()->validate($dto->ranges);
        $result->setRangesValidationResult($rangesValidationResult);

        return $result;
    }

}
