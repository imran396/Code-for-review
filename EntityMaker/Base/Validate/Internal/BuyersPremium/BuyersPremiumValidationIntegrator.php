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

namespace Sam\EntityMaker\Base\Validate\Internal\BuyersPremium;

use Sam\Core\RangeTable\BuyersPremium\Validate\BuyersPremiumRangesValidationResult;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Validate\AuctionMakerValidator;
use Sam\EntityMaker\Base\Validate\Internal\BuyersPremium\BuyersPremiumValidationInput as Input;
use Sam\EntityMaker\Base\Validate\Internal\BuyersPremium\Internal\Validate\BuyersPremiumValidatorCreateTrait;
use Sam\EntityMaker\LotItem\Validate\LotItemMakerValidator;
use Sam\EntityMaker\User\Validate\UserMakerValidator;

/**
 * Class BuyersPremiumValidationIntegrator
 * @package
 */
class BuyersPremiumValidationIntegrator extends CustomizableClass
{
    use BuyersPremiumValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionMakerValidator|LotItemMakerValidator|UserMakerValidator $entityMakerValidator
     * @param BuyersPremiumValidationInput $input
     * @param int $errorCode
     * @return void
     */
    public function validate(
        AuctionMakerValidator|LotItemMakerValidator|UserMakerValidator $entityMakerValidator,
        Input $input,
        int $errorCode
    ): void {
        $result = $this->createBuyersPremiumValidator()->validate($input);
        if ($result->hasSuccess()) {
            return;
        }

        if ($result->hasSyntaxError()) {
            $entityMakerValidator->addError($errorCode, $result->errorMessage());
            return;
        }

        if ($result->hasRangesError()) {
            $rangesValidationResult = $result->rangesValidationResult;
            $errorMessage = $this->buildBuyersPremiumRangesErrorMessage($entityMakerValidator, $rangesValidationResult, $errorCode);
            $entityMakerValidator->addError($errorCode, $errorMessage, null, $rangesValidationResult);
            return;
        }
    }

    /**
     * Create error message with detailed info that elaborates message in CSV and SOAP outputs.
     * @param AuctionMakerValidator|LotItemMakerValidator|UserMakerValidator $entityMakerValidator
     * @param BuyersPremiumRangesValidationResult $rangesValidationResult
     * @param int $errorCode
     * @return string
     */
    protected function buildBuyersPremiumRangesErrorMessage(
        AuctionMakerValidator|LotItemMakerValidator|UserMakerValidator $entityMakerValidator,
        BuyersPremiumRangesValidationResult $rangesValidationResult,
        int $errorCode
    ): string {
        $glue = "\n";
        $errorLines[] = $entityMakerValidator->getErrorMessage($errorCode);
        $mode = $entityMakerValidator->getConfigDto()->mode;
        if ($mode->isSoap() || $mode->isCsv()) {
            foreach ($rangesValidationResult->errorRowResults() as $rowResult) {
                if ($rowResult->hasFormatError()) {
                    $errorLines[] = $rowResult->getFormatErrorMessage()
                        . composeSuffix(['Row#' => $rowResult->index, 'Values' => $rowResult->row]);
                }
                if ($rowResult->hasStartAmountError()) {
                    $errorLines[] = $rowResult->getStartAmountErrorMessage()
                        . composeSuffix(['Row#' => $rowResult->index, 'Amount' => $rowResult->toStringStartAmount()]);
                }
                if ($rowResult->hasFixedValueError()) {
                    $errorLines[] = $rowResult->getFixedValueErrorMessage()
                        . composeSuffix(['Row#' => $rowResult->index, 'Fixed' => $rowResult->toStringFixedValue()]);
                }
                if ($rowResult->hasPercentValueError()) {
                    $errorLines[] = $rowResult->getPercentValueErrorMessage()
                        . composeSuffix(['Row#' => $rowResult->index, 'Percent' => $rowResult->toStringPercentValue()]);
                }
                if ($rowResult->hasModeValueError()) {
                    $errorLines[] = $rowResult->getModeValueErrorMessage()
                        . composeSuffix(['Row#' => $rowResult->index, 'Mode' => $rowResult->toStringModeValue()]);
                }
            }
            if ($mode->isCsv()) {
                $glue = '<br />';
            }
        }
        $message = implode($glue, $errorLines);
        return $message;
    }

}
